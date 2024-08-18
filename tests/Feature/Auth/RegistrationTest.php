<?php

namespace Tests\Feature\Auth;

use Livewire\Volt\Volt;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.register');
});

test('new users can register', function () {
    $component = Volt::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@moh.gov.my') // Use a valid email based on the regex
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    // Call the register method
    $component->call('register');

    // Assert the component redirects to the dashboard route
    $component->assertRedirect(route('dashboard'));

    // Assert the user is authenticated
    $this->assertAuthenticated();

    // Optionally, check the database to ensure the user was created
    $this->assertDatabaseHas('users', [
        'email' => 'test@moh.gov.my'
    ]);
});
