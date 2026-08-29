<script>
    window.isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    window.loginUrl = '{{ route("login") }}';
    window.checkoutUrl = '{{ route("frontend.checkout") }}';
    window.cartLoginMessage = 'Please sign in to add items to your cart and make purchases.';
    window.apiRoutes = {
        cart: '{{ route("api.cart.index") }}',
        cartAdd: '{{ route("api.cart.add") }}',
        cartUpdate: '{{ route("api.cart.update") }}',
        favorites: '{{ route("api.favorites.index") }}',
        currencyRates: '{{ route("api.currency.rates") }}',
    };
    window.csrfToken = '{{ csrf_token() }}';
    window.currencyConfig = @json(config('currency.supported', []));

async function apiFetch(url, options = {}) {
    const defaults = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json',
        },
    };
    const response = await fetch(url, { ...defaults, ...options });
    if (response.status === 401) {
        window.location.href = window.loginUrl + '?redirect=' + encodeURIComponent(window.location.href);
        return null;
    }
    if (!response.ok) return null;
    return response.json();
}

document.addEventListener('alpine:init', () => {
    Alpine.store('currency', {
        code: 'USD',
        rates: {},
        loading: false,

        async init() {
            this.code = localStorage.getItem('currency') || 'USD';
            await this.fetchRates();
        },

        async fetchRates() {
            if (this.loading) return;
            this.loading = true;
            try {
                const response = await fetch(window.apiRoutes.currencyRates);
                if (response.ok) {
                    this.rates = await response.json();
                }
            } catch (e) {
                this.rates = { USD: 1, JPY: 149.5, NPR: 133.2 };
            }
            this.loading = false;
        },

        set(code) {
            this.code = code;
            localStorage.setItem('currency', code);
        },

        convert(amount) {
            if (!this.rates.USD || !this.rates[this.code]) return amount;
            return (amount / this.rates.USD) * this.rates[this.code];
        },

        format(amount) {
            const converted = this.convert(amount);
            const config = window.currencyConfig[this.code] || window.currencyConfig['USD'];
            const symbol = config.symbol;
            if (this.code === 'JPY') {
                return symbol + Math.round(converted).toLocaleString();
            }
            return symbol + converted.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    });

    Alpine.store('i18n', {
        locale: 'en',

        init() {
            this.locale = localStorage.getItem('locale') || window.defaultLocale || 'en';
            document.documentElement.lang = this.locale;
        },

        t(text) {
            if (this.locale === 'en' || !text) return text;
            const dict = window.i18nTranslations[this.locale] || {};
            return dict[text] || text;
        },

        setLocale(locale) {
            if (locale === this.locale) return;
            this.locale = locale;
            localStorage.setItem('locale', locale);
            document.documentElement.lang = locale;
            this.applyToDOM();
            this.persistToServer();
        },

        applyToDOM() {
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const text = el.getAttribute('data-i18n');
                if (!text) return;
                el.textContent = this.t(text);
            });
        },

        persistToServer() {
            fetch('/language', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ locale: this.locale }),
            });
        }
    });

    Alpine.store('cart', {
        items: [],
        loading: false,
        subtotal: 0,
        shipping: 0,
        tax: 0,
        total: 0,

        async init() {
            if (!window.isLoggedIn) return;
            this.loading = true;
            const data = await apiFetch(window.apiRoutes.cart);
            if (data) {
                this.items = data.items;
                this.subtotal = data.subtotal;
                this.shipping = data.shipping;
                this.tax = data.tax;
                this.total = data.total;
            }
            this.loading = false;
        },

        count() {
            return this.items.reduce((total, item) => total + item.quantity, 0);
        },

        requireAuth() {
            if (!window.isLoggedIn) {
                window.location.href = window.loginUrl + '?redirect=' + encodeURIComponent(window.location.href);
                return false;
            }
            return true;
        },

        syncFromResponse(data, noticeType = 'success') {
            this.items = data.items;
            this.subtotal = data.subtotal;
            this.shipping = data.shipping;
            this.tax = data.tax;
            this.total = data.total;
            if (data.stock_reached && data.message) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message, type: 'warning' } }));
            }
        },

        async add(item) {
            if (!this.requireAuth()) return;
            const existing = this.items.find(i => i.id === item.id);
            if (existing && existing.quantity >= existing.stock) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Only ${existing.stock} units of "${item.name}" are available in stock.`, type: 'warning' } }));
                return;
            }
            const data = await apiFetch(window.apiRoutes.cartAdd, {
                method: 'POST',
                body: JSON.stringify({ product_id: item.id, quantity: 1 }),
            });
            if (data) {
                this.syncFromResponse(data);
                if (!data.stock_reached) {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: item.name + ' added to cart', type: 'success' } }));
                }
            }
        },

        async addToCartQty(item, qty) {
            if (!this.requireAuth()) return;
            let adjustedMessage = null;
            const existing = this.items.find(i => i.id === item.id);
            if (existing) {
                const remaining = existing.stock - existing.quantity;
                if (remaining <= 0) {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Only ${existing.stock} units of "${item.name}" are available in stock.`, type: 'warning' } }));
                    return;
                }
                if (qty > remaining) {
                    qty = remaining;
                    adjustedMessage = `Only ${remaining} units of "${item.name}" left in stock. Quantity adjusted to ${remaining}.`;
                }
            }
            const data = await apiFetch(window.apiRoutes.cartAdd, {
                method: 'POST',
                body: JSON.stringify({ product_id: item.id, quantity: qty }),
            });
            if (data) {
                this.syncFromResponse(data);
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: adjustedMessage || item.name + ' added to cart', type: adjustedMessage ? 'warning' : 'success' } }));
            }
        },

        async buyNow(item) {
            if (!this.requireAuth()) return;
            const existing = this.items.find(i => i.id === item.id);
            if (existing && existing.quantity >= existing.stock) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Only ${existing.stock} units of "${item.name}" are available in stock.`, type: 'warning' } }));
                return;
            }
            const data = await apiFetch(window.apiRoutes.cartAdd, {
                method: 'POST',
                body: JSON.stringify({ product_id: item.id, quantity: 1 }),
            });
            if (data) {
                this.syncFromResponse(data);
                window.location.href = window.checkoutUrl;
            }
        },

        async buyNowQty(item, qty) {
            if (!this.requireAuth()) return;
            let adjustedMessage = null;
            const existing = this.items.find(i => i.id === item.id);
            if (existing) {
                const remaining = existing.stock - existing.quantity;
                if (remaining <= 0) {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Only ${existing.stock} units of "${item.name}" are available in stock.`, type: 'warning' } }));
                    return;
                }
                if (qty > remaining) {
                    qty = remaining;
                    adjustedMessage = `Only ${remaining} units of "${item.name}" left in stock. Quantity adjusted to ${remaining}.`;
                }
            }
            if (adjustedMessage) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: adjustedMessage, type: 'warning' } }));
            }
            const data = await apiFetch(window.apiRoutes.cartAdd, {
                method: 'POST',
                body: JSON.stringify({ product_id: item.id, quantity: qty }),
            });
            if (data) {
                this.syncFromResponse(data);
                window.location.href = window.checkoutUrl;
            }
        },

        async remove(productId) {
            const url = '{{ route("api.cart.remove", ":id") }}'.replace(':id', productId);
            const data = await apiFetch(url, { method: 'DELETE' });
            if (data) this.syncFromResponse(data);
        },

        async updateQuantity(productId, quantity) {
            if (quantity < 1) return;
            const item = this.items.find(i => i.id === productId);
            if (item && quantity > item.stock) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Only ${item.stock} units of "${item.name}" are available in stock.`, type: 'warning' } }));
                return;
            }
            const data = await apiFetch(window.apiRoutes.cartUpdate, {
                method: 'PUT',
                body: JSON.stringify({ product_id: productId, quantity }),
            });
            if (data) this.syncFromResponse(data);
        },

        async clear() {
            const data = await apiFetch(window.apiRoutes.cart, { method: 'DELETE' });
            if (data) this.syncFromResponse(data);
        }
    });

    Alpine.store('wishlist', {
        items: [],
        loading: false,

        async init() {
            if (!window.isLoggedIn) return;
            this.loading = true;
            const data = await apiFetch(window.apiRoutes.favorites);
            if (data) this.items = data.items;
            this.loading = false;
        },

        has(id) {
            return this.items.includes(id);
        },

        async toggle(id) {
            if (!window.isLoggedIn) {
                window.location.href = window.loginUrl + '?redirect=' + encodeURIComponent(window.location.href);
                return;
            }
            const url = '{{ route("api.favorites.toggle", ":id") }}'.replace(':id', id);
            const data = await apiFetch(url, { method: 'POST' });
            if (data) {
                if (data.favorited) {
                    if (!this.items.includes(id)) this.items.push(id);
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Added to favorites', type: 'success' } }));
                } else {
                    this.items = this.items.filter(i => i !== id);
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Removed from favorites', type: 'info' } }));
                }
                window.dispatchEvent(new CustomEvent('favorites:count', { detail: { count: data.count } }));
            }
        },

        async remove(id) {
            const url = '{{ route("api.favorites.destroy", ":id") }}'.replace(':id', id);
            const data = await apiFetch(url, { method: 'DELETE' });
            if (data) {
                this.items = this.items.filter(i => i !== id);
                window.dispatchEvent(new CustomEvent('favorites:count', { detail: { count: data.count } }));
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Removed from favorites', type: 'info' } }));
            }
        }
    });
});

// Scroll reveal — CSS-only via .animate-in class (no JS opacity hack)
function initScrollReveal() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.card-hover, .card, .category-card, .testimonial-card, .gallery-item').forEach(el => {
        if (!el.closest('header') && !el.closest('nav')) {
            observer.observe(el);
        }
    });
}

document.addEventListener('DOMContentLoaded', initScrollReveal);
document.addEventListener('livewire:navigated', () => {
    initScrollReveal();
    const i18n = Alpine.store('i18n');
    if (i18n) i18n.applyToDOM();
});

</script>
