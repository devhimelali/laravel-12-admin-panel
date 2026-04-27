<?php

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login history details returns expected json', function () {
    $user = User::factory()->create();

    $history = LoginHistory::create([
        'user_id' => $user->id,
        'ip' => '127.0.0.1',
        'date' => now()->toDateString(),
        'details' => [
            'city' => 'Localhost',
            'regionName' => 'Development Environment',
            'country' => 'Local Development',
            'browser_name' => 'Chrome',
            'os_name' => 'Windows',
            'device_type' => 'desktop',
            'timezone' => 'UTC',
            'isp' => 'Local ISP',
            'org' => 'Development Organization',
            'referrer_host' => 'taskly.test',
            'referrer_path' => '/login',
        ],
        'type' => 'superadmin',
        'created_by' => $user->id,
    ]);

    $response = $this->getJson(route('login-history.details', $history));

    $response->assertOk();
    $response->assertJsonPath('data.user_name', $user->name);
    $response->assertJsonPath('data.email', $user->email);
    $response->assertJsonPath('data.role', 'superadmin');
    $response->assertJsonPath('data.ip', '127.0.0.1');
    $response->assertJsonPath('data.city', 'Localhost');
    $response->assertJsonPath('data.referrer_path', '/login');
});
