<?php

declare(strict_types=1);

use App\Models\Bill;

it('shows only own bills', function (): void {
    $alice = makeActiveMember(nik: '3509291199500001', overrides: ['full_name' => 'Alice']);
    $bob = makeActiveMember(nik: '3509291199500002', overrides: ['full_name' => 'Bob']);

    Bill::query()->create([
        'member_id' => $alice->id,
        'tahun' => 2026,
        'uraian' => 'Iuran Alice',
        'nominal' => 100000,
        'dibayar' => 0,
        'status' => 'Belum Lunas',
    ]);

    Bill::query()->create([
        'member_id' => $bob->id,
        'tahun' => 2026,
        'uraian' => 'Iuran Bob',
        'nominal' => 200000,
        'dibayar' => 0,
        'status' => 'Belum Lunas',
    ]);

    $this->actingAs($alice, 'member');
    $response = $this->get('/bills');

    $response->assertOk();
    $response->assertSee('Iuran Alice');
    $response->assertDontSee('Iuran Bob');
});

it('computes sisa correctly via generated column', function (): void {
    $member = makeActiveMember();

    $bill = Bill::query()->create([
        'member_id' => $member->id,
        'tahun' => 2026,
        'uraian' => 'Iuran',
        'nominal' => 240000,
        'dibayar' => 60000,
        'status' => 'Sebagian',
    ]);

    $bill->refresh();

    expect($bill->sisa)->toBe(180000);
});
