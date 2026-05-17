<?php

declare(strict_types=1);

use App\Models\Certificate;

it('rejects download of other member certificate', function (): void {
    $alice = makeActiveMember(nik: '3509291199500001', overrides: ['full_name' => 'Alice']);
    $bob = makeActiveMember(nik: '3509291199500002', overrides: ['full_name' => 'Bob']);

    $bobCert = Certificate::query()->create([
        'member_id' => $bob->id,
        'jenis' => 'Sabuk Hijau',
        'nomor' => 'CERT/BOB/2025',
        'tanggal' => '2025-08-17',
    ]);

    $this->actingAs($alice, 'member');
    $response = $this->get("/certificates/{$bobCert->id}/download");

    $response->assertForbidden();
});

it('lists certificates for the authenticated member', function (): void {
    $member = makeActiveMember();

    Certificate::query()->create([
        'member_id' => $member->id,
        'jenis' => 'Sabuk Polos',
        'nomor' => 'CERT/SELF/2025',
        'tanggal' => '2025-08-17',
    ]);

    $this->actingAs($member, 'member');
    $response = $this->get('/certificates');

    $response->assertOk();
    $response->assertSee('CERT/SELF/2025');
});
