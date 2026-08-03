<?php

namespace Tests\Feature;

use App\Livewire\ProductListing;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ProductListingSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.database' => 'laravel']);
        try {
            DB::connection('mysql')->getPdo();
        } catch (\Throwable $e) {
            $this->markTestSkipped('MySQL dev database not available.');
        }
    }

    public function test_shop_listing_renders_and_survives_updates(): void
    {
        Livewire::test(ProductListing::class)
            ->assertOk()
            ->set('sort', 'price_desc')
            ->assertOk()
            ->call('setSort', 'price_asc')
            ->assertOk()
            ->set('brand', ['onitsuka'])
            ->assertOk()
            ->set('minPrice', 20)
            ->assertOk()
            ->set('inStock', true)
            ->assertOk()
            ->call('resetFilters')
            ->assertOk();
    }

    public function test_category_listing_renders_and_paginates(): void
    {
        Livewire::test(ProductListing::class, ['categorySlug' => 'fashion'])
            ->assertOk()
            ->set('sort', 'newest')
            ->assertOk()
            ->set('onSale', true)
            ->assertOk()
            ->call('setPage', 1)
            ->assertOk();
    }

    public function test_category_without_products_renders_empty_state(): void
    {
        Livewire::test(ProductListing::class, ['categorySlug' => 'watches'])
            ->assertOk()
            ->call('resetFilters')
            ->assertOk();
    }
}
