<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user for authentication
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Login as admin
        $this->actingAs($this->admin);
    }

    /**
     * Create a regular user for testing user-related operations
     */
    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'user',
        ], $attributes));
    }

    /**
     * Assert that the response redirects to login (unauthenticated)
     */
    protected function assertGuestRedirect(): void
    {
        $this->assertGuest();
    }
}
