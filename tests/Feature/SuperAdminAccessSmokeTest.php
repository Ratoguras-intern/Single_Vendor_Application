<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperAdminAccessSmokeTest extends TestCase
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

    protected function tearDown(): void
    {
        User::where('email', 'like', 'smoke-%@example.com')->delete();
        parent::tearDown();
    }

    private function user(string $role): User
    {
        return User::updateOrCreate(
            ['email' => "smoke-$role@example.com"],
            [
                'name' => ucfirst($role),
                'password' => Hash::make('password'),
                'role' => $role,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }

    public function test_super_admin_reaches_superadmin_pages(): void
    {
        $this->actingAs($this->user('super_admin'))
            ->get(route('superadmin.dashboard'))
            ->assertOk();
        $this->actingAs($this->user('super_admin'))
            ->get(route('superadmin.admins.index'))
            ->assertOk();
        $this->actingAs($this->user('super_admin'))
            ->get(route('superadmin.users.index'))
            ->assertOk();
    }

    public function test_admin_and_customer_are_forbidden_from_superadmin_pages(): void
    {
        foreach (['admin', 'customer'] as $role) {
            $this->actingAs($this->user($role))
                ->get(route('superadmin.dashboard'))
                ->assertForbidden();
        }
    }

    public function test_super_admin_reaches_admin_pages(): void
    {
        $this->actingAs($this->user('super_admin'))
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_customer_is_forbidden_from_admin_pages(): void
    {
        $this->actingAs($this->user('customer'))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_super_admin_is_forbidden_from_customer_orders(): void
    {
        $this->actingAs($this->user('super_admin'))
            ->get(route('customer.orders.index'))
            ->assertForbidden();
    }
}
