<?php

use App\Models\User;

it('renders the elections index for the election commission', function (): void {
    $this->actingAs(adminUser())
        ->get(route('admin-elections-index'))
        ->assertOk();
});

it('renders the elections form', function (): void {
    $this->actingAs(adminUser())
        ->get(route('admin-elections-add'))
        ->assertOk();
});

it('denies admin pages to users without a group', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('admin-elections-index'))
        ->assertForbidden();
});
