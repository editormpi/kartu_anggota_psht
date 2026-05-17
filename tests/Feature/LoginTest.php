<?php

declare(strict_types=1);

use App\Models\LoginAttempt;

it('allows login with valid nik and password', function (): void {
    $member = makeActiveMember(nik: '3509291199500001');

    $response = $this->post('/login', [
        'nik' => '3509291199500001',
        'password' => 'SecretPass1',
    ]);

    $response->assertRedirect(route('portal.dashboard'));
    $this->assertAuthenticatedAs($member, 'member');
});

it('rejects invalid nik with generic message', function (): void {
    makeActiveMember(nik: '3509291199500001');

    $response = $this->from('/login')->post('/login', [
        'nik' => '9999999999999999',
        'password' => 'SecretPass1',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors(['nik' => 'NIK atau password salah']);
    $this->assertGuest('member');
});

it('rejects wrong password with generic message', function (): void {
    makeActiveMember(nik: '3509291199500001');

    $response = $this->from('/login')->post('/login', [
        'nik' => '3509291199500001',
        'password' => 'WrongPass!',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors(['nik' => 'NIK atau password salah']);
});

it('blocks inactive accounts with specific message', function (): void {
    makeActiveMember(nik: '3509291199500001', overrides: ['is_active' => false]);

    $response = $this->from('/login')->post('/login', [
        'nik' => '3509291199500001',
        'password' => 'SecretPass1',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors(['nik' => 'Akun belum aktif, hubungi admin']);
});

it('throttles after 5 failed attempts from same ip', function (): void {
    makeActiveMember(nik: '3509291199500001');

    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', [
            'nik' => '3509291199500001',
            'password' => 'WrongPass!',
        ]);
    }

    $response = $this->from('/login')->post('/login', [
        'nik' => '3509291199500001',
        'password' => 'SecretPass1',
    ]);

    $response->assertSessionHasErrors('nik');
    $this->assertGuest('member');
    expect(LoginAttempt::query()->where('successful', false)->count())->toBeGreaterThanOrEqual(5);
});

it('requires password change on first login', function (): void {
    makeActiveMember(nik: '3509291199500001', overrides: ['must_change_password' => true]);

    $response = $this->post('/login', [
        'nik' => '3509291199500001',
        'password' => 'SecretPass1',
    ]);

    $response->assertRedirect(route('password.change'));
});
