@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Testimonials', 'url' => null],
    ];
@endphp

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Testimonials</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Customer reviews shown on the homepage. Drag to reorder, toggle visibility, edit each review.</p>
        </div>
        <a href="#create" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Testimonial
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-300">{{ session('success') }}</div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400 w-16">Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Review</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Rating</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Visible</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800" id="testimonial-sortable">
                    @forelse ($items as $index => $t)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] cursor-move sortable-testimonial" data-id="{{ $t['id'] }}">
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="drag-handle cursor-grab text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 inline-block align-middle">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                </span>
                                <span class="ml-1 text-xs text-gray-400 dark:text-gray-500">{{ $loop->index }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if (!empty($t['avatar']))
                                        <img src="{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="h-10 w-10 flex-shrink-0 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 flex-shrink-0 rounded-full bg-primary-50 dark:bg-primary-950/30 flex items-center justify-center text-sm font-semibold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($t['name'], 0, 1)) }}</div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $t['name'] }}</p>
                                        @if (!empty($t['role']))
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $t['role'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">{{ $t['review'] }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-amber-500 text-sm">★ {{ $t['rating'] }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <button onclick="toggleTestimonial('{{ $t['id'] }}', this)" class="relative inline-flex h-6 w-11 shrink-0 items-center overflow-hidden rounded-full transition-colors {{ $t['active'] ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $t['active'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    <button onclick="openTestimonialModal('{{ $t['id'] }}')"
                                        class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-500 shadow-sm transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-gray-200">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                    <form action="{{ route('admin.testimonials.destroy', $t['id']) }}" method="POST" onsubmit="return confirm('Delete this testimonial?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-red-500 shadow-sm transition hover:bg-red-50 hover:text-red-700 dark:border-gray-700 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-500/10">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">No testimonials yet. Add your first review below.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create form --}}
    <div id="create" class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Add New Testimonial</h3>
        <form action="{{ route('admin.testimonials.store') }}" method="POST" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name *</label>
                <input type="text" name="name" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Role / Subtitle</label>
                <input type="text" name="role" placeholder="e.g. Verified Buyer" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Review *</label>
                <textarea name="review" required rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Rating (1-5)</label>
                <select name="rating" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Avatar (optional)</label>
                <input type="url" name="avatar" placeholder="Paste image URL from Media Library" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="md:col-span-2">
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="active" value="1" checked class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                    Visible on homepage
                </label>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add Testimonial</button>
            </div>
        </form>
    </div>

    {{-- Edit modal --}}
    <div id="testimonialEditModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 p-4" onclick="if(event.target === this) closeTestimonialModal();">
        <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Edit Testimonial</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeTestimonialModal()">&times;</button>
            </div>
            <form id="testimonialEditForm" method="POST" class="space-y-4">
                <input type="hidden" name="_method" value="PUT">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name *</label>
                        <input id="tm_name" type="text" name="name" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                        <input id="tm_role" type="text" name="role" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Review *</label>
                    <textarea id="tm_review" name="review" required rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"></textarea>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Rating</label>
                        <select id="tm_rating" name="rating" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Avatar URL</label>
                        <input id="tm_avatar" type="url" name="avatar" placeholder="https://..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input id="tm_active" type="checkbox" name="active" value="1" class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                    Visible on homepage
                </label>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeTestimonialModal()" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Cancel</button>
                    <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/turbo-script">
    window.__testimonials = @json($items);

    function showToast(message, type) {
        var toast = document.createElement('div');
        toast.className = 'fixed top-5 right-5 z-[100001] rounded-lg bg-gray-900 px-4 py-2 text-sm text-white shadow-lg';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(function () { toast.remove(); }, 2500);
    }

    function openTestimonialModal(id) {
        var t = window.__testimonials.find(function (x) { return x.id === id; });
        if (!t) return;
        document.getElementById('testimonialEditForm').action = '/admin/testimonials/' + id;
        document.getElementById('tm_name').value = t.name || '';
        document.getElementById('tm_role').value = t.role || '';
        document.getElementById('tm_review').value = t.review || '';
        document.getElementById('tm_rating').value = t.rating || 5;
        document.getElementById('tm_avatar').value = t.avatar || '';
        document.getElementById('tm_active').checked = t.active !== false;
        var modal = document.getElementById('testimonialEditModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTestimonialModal() {
        var modal = document.getElementById('testimonialEditModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function toggleTestimonial(id, btn) {
        fetch('/admin/testimonials/' + id + '/toggle', {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        }).then(function (r) { return r.json(); }).then(function (d) {
            if (d.active) {
                btn.classList.remove('bg-gray-300', 'dark:bg-gray-600');
                btn.classList.add('bg-green-500');
                btn.firstElementChild.classList.add('translate-x-6');
                btn.firstElementChild.classList.remove('translate-x-1');
            } else {
                btn.classList.remove('bg-green-500');
                btn.classList.add('bg-gray-300', 'dark:bg-gray-600');
                btn.firstElementChild.classList.remove('translate-x-6');
                btn.firstElementChild.classList.add('translate-x-1');
            }
            if (d.message) showToast(d.message, 'success');
        });
    }

    function initTestimonialSort() {
        var container = document.getElementById('testimonial-sortable');
        if (!container || container.children.length < 2 || container.__sortable) return;
        container.__sortable = new Sortable(container, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function () {
                var rows = container.querySelectorAll('.sortable-testimonial');
                var order = Array.from(rows).map(function (r) { return r.dataset.id; });
                fetch('/admin/testimonials/order', {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ order: order })
                }).then(function (r) { return r.json(); }).then(function (d) {
                    if (d.message) showToast(d.message, 'success');
                });
            }
        });
    }

    initTestimonialSort();
</script>
@endpush
