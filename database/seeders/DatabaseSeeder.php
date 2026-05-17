<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Certificate;
use App\Models\Member;
use App\Models\PaymentHistory;
use App\Models\User;
use App\Support\NikEncryptor;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedMembers();
    }

    private function seedAdmin(): void
    {
        User::query()->updateOrCreate(
            ['email' => (string) env('ADMIN_SEED_EMAIL', 'admin@psht-jember.local')],
            [
                'name' => 'Administrator',
                'password' => Hash::make((string) env('ADMIN_SEED_PASSWORD', 'ChangeMe!Now2026')),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }

    private function seedMembers(): void
    {
        $encryptor = app(NikEncryptor::class);

        $samples = [
            [
                'nik' => '3509291199500001',
                'full_name' => 'Andi Wijaya',
                'tingkat' => 'Sabuk Polos',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jember',
                'tanggal_lahir' => '1995-11-29',
                'ranting' => 'Sukowono',
                'rayon' => 'Sukosari',
            ],
            [
                'nik' => '3509151088700002',
                'full_name' => 'Budi Santoso',
                'tingkat' => 'Sabuk Hijau',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jember',
                'tanggal_lahir' => '1987-10-15',
                'ranting' => 'Patrang',
                'rayon' => 'Slawu',
            ],
            [
                'nik' => '3509050595500003',
                'full_name' => 'Citra Dewi',
                'tingkat' => 'Sabuk Putih',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Jember',
                'tanggal_lahir' => '1995-05-05',
                'ranting' => 'Kaliwates',
                'rayon' => 'Mangli',
            ],
        ];

        foreach ($samples as $sample) {
            $nik = $sample['nik'];
            $defaultPassword = CarbonImmutable::parse($sample['tanggal_lahir'])->format('dmY');

            $member = Member::query()->updateOrCreate(
                ['nik_hash' => $encryptor->hash($nik)],
                [
                    'nik_encrypted' => $encryptor->encrypt($nik),
                    'password' => Hash::make($defaultPassword),
                    'must_change_password' => true,
                    'is_active' => true,
                    'full_name' => $sample['full_name'],
                    'tingkat' => $sample['tingkat'],
                    'status_keanggotaan' => 'Aktif',
                    'tanggal_keanggotaan' => '2020-01-15',
                    'jenis_kelamin' => $sample['jenis_kelamin'],
                    'tempat_lahir' => $sample['tempat_lahir'],
                    'tanggal_lahir' => $sample['tanggal_lahir'],
                    'agama' => 'Islam',
                    'pekerjaan' => 'Mahasiswa',
                    'alamat' => 'Jl. Contoh No. 1, Jember',
                    'ranting' => $sample['ranting'],
                    'rayon' => $sample['rayon'],
                    'tempat_latihan' => 'GOR Kaliwates',
                    'hp' => '081234567890',
                ]
            );

            $this->seedBillsFor($member);
            $this->seedPaymentsFor($member);
            $this->seedCertificateFor($member, $sample['tingkat']);
        }
    }

    private function seedBillsFor(Member $member): void
    {
        Bill::query()->updateOrCreate(
            ['member_id' => $member->id, 'tahun' => 2025, 'uraian' => 'Iuran Tahunan 2025'],
            ['nominal' => 240000, 'dibayar' => 240000, 'status' => 'Lunas']
        );

        Bill::query()->updateOrCreate(
            ['member_id' => $member->id, 'tahun' => 2026, 'uraian' => 'Iuran Tahunan 2026'],
            ['nominal' => 240000, 'dibayar' => 60000, 'status' => 'Sebagian']
        );
    }

    private function seedPaymentsFor(Member $member): void
    {
        $payments = [
            ['tanggal' => '2025-03-12', 'uraian' => 'Iuran Tahunan 2025 (cicilan 1)', 'nominal' => 120000],
            ['tanggal' => '2025-09-01', 'uraian' => 'Iuran Tahunan 2025 (pelunasan)', 'nominal' => 120000],
            ['tanggal' => '2026-02-10', 'uraian' => 'Iuran Tahunan 2026 (cicilan 1)', 'nominal' => 60000],
        ];

        foreach ($payments as $p) {
            PaymentHistory::query()->updateOrCreate(
                ['member_id' => $member->id, 'tanggal' => $p['tanggal'], 'uraian' => $p['uraian']],
                ['nominal' => $p['nominal'], 'keterangan' => 'Operator: Sekretariat']
            );
        }
    }

    private function seedCertificateFor(Member $member, string $tingkat): void
    {
        Certificate::query()->updateOrCreate(
            ['nomor' => sprintf('CERT/%d/%s', $member->id, date('Y'))],
            [
                'member_id' => $member->id,
                'jenis' => $tingkat,
                'tanggal' => '2023-08-17',
            ]
        );
    }
}
