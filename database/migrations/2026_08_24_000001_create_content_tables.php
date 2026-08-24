<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (! Schema::hasColumn('pages', 'subtitle')) {
                $table->string('subtitle')->nullable()->after('title');
            }
            if (! Schema::hasColumn('pages', 'template')) {
                $table->string('template', 50)->nullable()->after('slug');
            }
            if (! Schema::hasColumn('pages', 'canonical_url')) {
                $table->string('canonical_url')->nullable()->after('seo_description');
            }
            if (! Schema::hasColumn('pages', 'og_title')) {
                $table->string('og_title')->nullable()->after('canonical_url');
            }
            if (! Schema::hasColumn('pages', 'og_description')) {
                $table->text('og_description')->nullable()->after('og_title');
            }
            if (! Schema::hasColumn('pages', 'og_image')) {
                $table->string('og_image')->nullable()->after('og_description');
            }
        });

        if (Schema::hasTable('contact_messages') && ! Schema::hasColumn('contact_messages', 'phone')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('email');
            });
        }

        if (! Schema::hasTable('faq_categories')) {
            Schema::create('faq_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('faq_category_id')->nullable()->constrained('faq_categories')->nullOnDelete();
                $table->string('question');
                $table->text('answer');
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('job_openings')) {
            Schema::create('job_openings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('department')->nullable();
                $table->string('location')->nullable();
                $table->string('employment_type', 50)->nullable();
                $table->string('experience_level', 100)->nullable();
                $table->longText('description')->nullable();
                $table->text('responsibilities')->nullable();
                $table->text('requirements')->nullable();
                $table->text('benefits')->nullable();
                $table->text('application_instructions')->nullable();
                $table->string('application_email')->nullable();
                $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('post_categories')) {
            Schema::create('post_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_category_id')->nullable()->constrained('post_categories')->nullOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content')->nullable();
                $table->string('featured_image')->nullable();
                $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('author_name')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->enum('status', ['draft', 'published'])->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->string('seo_title')->nullable();
                $table->text('seo_description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('press_releases')) {
            Schema::create('press_releases', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('summary')->nullable();
                $table->longText('content')->nullable();
                $table->string('featured_image')->nullable();
                $table->date('released_at')->nullable();
                $table->enum('status', ['draft', 'published'])->default('draft');
                $table->string('seo_title')->nullable();
                $table->text('seo_description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('shipping_methods')) {
            Schema::create('shipping_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->string('delivery_estimate')->nullable();
                $table->string('availability')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('press_releases');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('job_openings');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');

        if (Schema::hasTable('contact_messages') && Schema::hasColumn('contact_messages', 'phone')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }

        if (Schema::hasColumns('pages', ['subtitle', 'template', 'canonical_url', 'og_title', 'og_description', 'og_image'])) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn(['subtitle', 'template', 'canonical_url', 'og_title', 'og_description', 'og_image']);
            });
        }
    }
};
