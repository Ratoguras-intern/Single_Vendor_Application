<?php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Homepage Sections', 'url' => route('admin.homepage-sections.index')],
        ['label' => ucwords(str_replace('-', ' ', $section->slug)), 'url' => null],
    ];
?>

<?php $__env->startSection('content'); ?>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white"><?php echo e(ucwords(str_replace('-', ' ', $section->slug))); ?></h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                <?php if($section->subtitle): ?>
                    <?php echo e($section->subtitle); ?>

                <?php else: ?>
                    Edit section content and configuration
                <?php endif; ?>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <form method="POST" action="<?php echo e(route('admin.homepage-sections.toggle', $section)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors <?php echo e($section->is_enabled ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'); ?>">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform <?php echo e($section->is_enabled ? 'translate-x-6' : 'translate-x-1'); ?>"></span>
                </button>
            </form>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('admin.homepage-sections.update', $section)); ?>" x-data="sectionForm()">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Basic Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" name="title" value="<?php echo e(old('title', $section->title)); ?>"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Subtitle</label>
                    <input type="text" name="subtitle" value="<?php echo e(old('subtitle', $section->subtitle)); ?>"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
            </div>
            <?php if(in_array($section->slug, ['featured-products','new-arrivals','trending-products','flash-sale','best-sellers','recommended-products','popular-products'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Max Products</label>
                        <input type="number" name="max_products" value="<?php echo e(old('max_products', $section->max_products)); ?>" min="1" max="50"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Layout</label>
                        <select name="layout" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="grid" <?php echo e($section->layout === 'grid' ? 'selected' : ''); ?>>Grid</option>
                            <option value="carousel" <?php echo e($section->layout === 'carousel' ? 'selected' : ''); ?>>Carousel</option>
                            <option value="list" <?php echo e($section->layout === 'list' ? 'selected' : ''); ?>>List</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        
        <?php if($section->slug === 'hero-carousel'): ?>
            <?php echo $__env->make('admin.homepage-sections._hero-carousel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif($section->slug === 'trust-bar'): ?>
            <?php echo $__env->make('admin.homepage-sections._trust-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif($section->slug === 'why-choose-us'): ?>
            <?php echo $__env->make('admin.homepage-sections._why-choose-us', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif($section->slug === 'testimonials'): ?>
            <?php echo $__env->make('admin.homepage-sections._testimonials', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif($section->slug === 'newsletter-cta'): ?>
            <?php echo $__env->make('admin.homepage-sections._newsletter-cta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif($section->slug === 'instagram-gallery'): ?>
            <?php echo $__env->make('admin.homepage-sections._instagram-gallery', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif($section->slug === 'flash-sale'): ?>
            <?php echo $__env->make('admin.homepage-sections._flash-sale', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif($section->slug === 'premium-footer'): ?>
            <?php echo $__env->make('admin.homepage-sections._premium-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif(in_array($section->slug, ['featured-products','new-arrivals','trending-products','best-sellers','recommended-products','popular-products'])): ?>
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage products for this section via
                    <a href="<?php echo e(route('admin.product-sections.index', $section->slug)); ?>" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 font-medium">Product Sections</a>.
                </p>
            </div>
        <?php endif; ?>

        
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Save Changes
            </button>
            <a href="<?php echo e(route('admin.homepage-sections.index')); ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                Back to Sections
            </a>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function sectionForm() {
    return {
        slides: <?php echo json_encode($section->config['slides'] ?? [], 15, 512) ?>,
        trustItems: <?php echo json_encode($section->config['items'] ?? [], 15, 512) ?>,
        features: <?php echo json_encode($section->config['features'] ?? [], 15, 512) ?>,
        testimonials: <?php echo json_encode($section->config['testimonials'] ?? [], 15, 512) ?>,
        galleryImages: <?php echo json_encode($section->config['images'] ?? [], 15, 512) ?>,
        footerColumns: <?php echo json_encode($section->config['footer_columns'] ?? [], 15, 512) ?>,
        socialLinks: <?php echo json_encode($section->config['social_links'] ?? [], 15, 512) ?>,

        addSlide() {
            this.slides.push({ badge: 'NEW', badge_color: 'bg-green-500', heading: '', description: '', image: '', cta_primary: 'Shop Now', cta_secondary: 'Learn More', link_primary: '/shop', link_secondary: '/about' });
        },
        removeSlide(i) { this.slides.splice(i, 1); },

        addTrustItem() {
            this.trustItems.push({ icon: 'truck', title: '', description: '' });
        },
        removeTrustItem(i) { this.trustItems.splice(i, 1); },

        addFeature() {
            this.features.push({ icon: 'quality', title: '', description: '' });
        },
        removeFeature(i) { this.features.splice(i, 1); },

        addTestimonial() {
            this.testimonials.push({ name: '', avatar: '', rating: 5, review: '', role: '' });
        },
        removeTestimonial(i) { this.testimonials.splice(i, 1); },

        addGalleryImage() {
            this.galleryImages.push({ url: '', span: 'col-span-1 row-span-1', alt: '' });
        },
        removeGalleryImage(i) { this.galleryImages.splice(i, 1); },

        addFooterColumn() {
            this.footerColumns.push({ heading: '', links: [] });
        },
        removeFooterColumn(i) { this.footerColumns.splice(i, 1); },
        addFooterLink(colIndex) {
            this.footerColumns[colIndex].links.push('');
        },
        removeFooterLink(colIndex, linkIndex) {
            this.footerColumns[colIndex].links.splice(linkIndex, 1);
        },

        addSocialLink() {
            this.socialLinks.push({ platform: '', url: '#' });
        },
        removeSocialLink(i) { this.socialLinks.splice(i, 1); },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Co_laravel\Single Vendor Ecomm\single-vendor-ecommerce\resources\views\admin\homepage-sections\show.blade.php ENDPATH**/ ?>