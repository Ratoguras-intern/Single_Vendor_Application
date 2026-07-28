<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#e89b2d">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'NBK Vertex')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preload" href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" as="style">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/frontend.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>

    <script>
        window.i18nTranslations = {
            'en': <?php echo file_get_contents(lang_path('en.json')); ?>,
            'ja': <?php echo file_get_contents(lang_path('ja.json')); ?>,
            'ne': <?php echo file_get_contents(lang_path('ne.json')); ?>

        };
        window.defaultLocale = '<?php echo e(config("app.locale", "en")); ?>';
    </script>

    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script>
        function bannerCountdown(endDate, autoHide) {
            autoHide = autoHide !== undefined ? autoHide : true;
            return {
                show: !autoHide, days: 0, hours: 0, minutes: 0, seconds: 0, timer: null,
                init() {
                    if (!endDate || !autoHide) return;
                    this.tick();
                    this.timer = setInterval(() => this.tick(), 1000);
                },
                tick() {
                    if (!autoHide || !endDate) return;
                    const diff = new Date(endDate) - new Date();
                    if (diff <= 0) { this.show = false; clearInterval(this.timer); return; }
                    this.days = Math.floor(diff / 86400000);
                    this.hours = Math.floor((diff % 86400000) / 3600000);
                    this.minutes = Math.floor((diff % 3600000) / 60000);
                    this.seconds = Math.floor((diff % 60000) / 1000);
                }
            };
        }
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const saved = localStorage.getItem('theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    this.current = saved || (prefersDark ? 'dark' : 'light');
                    this.apply();
                },
                current: 'light',
                toggle() {
                    this.current = this.current === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.current);
                    this.apply();
                },
                apply() {
                    document.documentElement.classList.toggle('dark', this.current === 'dark');
                }
            });
        });
    </script>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen ">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[9999] focus:bg-primary-500 focus:text-white focus:px-4 focus:py-2 focus:rounded-lg focus:font-semibold">Skip to content</a>
    <?php echo $__env->make('frontend.partials.announcement-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main id="main-content" class="flex-grow">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <div x-data="toastManager()" x-on:toast.window="add($event.detail)" role="status" aria-live="polite" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
        <template x-for="t in items" :key="t.id">
            <div x-show="t.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-8" class="pointer-events-auto max-w-sm w-full rounded-card border shadow-dropdown px-4 py-3 flex items-center gap-3"
                :class="t.type === 'error' ? 'border-red-300 dark:border-red-800 bg-red-50 dark:bg-red-950/50 text-red-800 dark:text-red-300' : t.type === 'warning' ? 'border-yellow-300 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-950/50 text-yellow-800 dark:text-yellow-300' : t.type === 'info' ? 'border-blue-300 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/50 text-blue-800 dark:text-blue-300' : 'border-green-300 dark:border-green-800 bg-green-50 dark:bg-green-950/50 text-green-800 dark:text-green-300'">
                <template x-if="t.type === 'success'">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </template>
                <template x-if="t.type === 'error'">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </template>
                <template x-if="t.type === 'warning'">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                </template>
                <template x-if="t.type === 'info'">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                </template>
                <span x-text="t.message" class="text-sm font-medium flex-1"></span>
                <button x-on:click="dismiss(t.id)" class="shrink-0 opacity-60 hover:opacity-100">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    <?php if (! empty(trim($__env->yieldContent('footer')))): ?>
        <?php echo $__env->yieldContent('footer'); ?>
    <?php else: ?>
        <?php echo $__env->make('frontend.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->make('frontend.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\layouts\frontend.blade.php ENDPATH**/ ?>