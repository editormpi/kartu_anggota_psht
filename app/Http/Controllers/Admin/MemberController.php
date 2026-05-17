<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\MemberStatus;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Support\NikEncryptor;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = Member::query();

        if ($search = $request->get('search')) {
            $query->where('full_name', 'like', "%{$search}%");
        }

        if ($status = $request->get('status')) {
            $query->where('status_keanggotaan', $status);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $members = $query->orderBy('full_name')->paginate(20)->withQueryString();

        return view('admin.members.index', [
            'members'  => $members,
            'statuses' => MemberStatus::cases(),
        ]);
    }

    public function create(): View
    {
        return view('admin.members.create', [
            'statuses' => MemberStatus::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name'            => 'required|string|max:255',
            'nik_input'            => 'required|regex:/^\d{16}$/|unique:members,nik_hash',
            'jenis_kelamin'        => 'nullable|in:L,P',
            'tempat_lahir'         => 'nullable|string|max:100',
            'tanggal_lahir'        => 'nullable|date',
            'weton'                => 'nullable|string|max:50',
            'agama'                => 'nullable|string|max:50',
            'tingkat'              => 'nullable|string|max:100',
            'status_keanggotaan'   => 'required|in:Aktif,Tidak Aktif,Alumni,Berhenti',
            'tanggal_keanggotaan'  => 'nullable|date',
            'ranting'              => 'nullable|string|max:100',
            'rayon'                => 'nullable|string|max:100',
            'tempat_latihan'       => 'nullable|string|max:100',
            'is_active'            => 'boolean',
            'must_change_password' => 'boolean',
            'pekerjaan'            => 'nullable|string|max:100',
            'hp'                   => 'nullable|string|max:20',
            'alamat'               => 'nullable|string',
            'keterangan'           => 'nullable|string',
        ]);

        $encryptor = app(NikEncryptor::class);
        $nik = $data['nik_input'];
        unset($data['nik_input']);

        $data['nik_hash']      = $encryptor->hash($nik);
        $data['nik_encrypted'] = $encryptor->encrypt($nik);

        $defaultPassword = ! empty($data['tanggal_lahir'])
            ? CarbonImmutable::parse($data['tanggal_lahir'])->format('dmY')
            : substr($nik, 0, 8);

        $data['password']             = Hash::make($defaultPassword);
        $data['must_change_password'] = true;
        $data['is_active']            = $request->boolean('is_active');

        Member::create($data);

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Member $member): RedirectResponse
    {
        return redirect()->route('admin.members.edit', $member);
    }

    public function edit(Member $member): View
    {
        return view('admin.members.edit', [
            'member'   => $member,
            'statuses' => MemberStatus::cases(),
        ]);
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $data = $request->validate([
            'full_name'            => 'required|string|max:255',
            'nik_input'            => 'nullable|regex:/^\d{16}$/',
            'jenis_kelamin'        => 'nullable|in:L,P',
            'tempat_lahir'         => 'nullable|string|max:100',
            'tanggal_lahir'        => 'nullable|date',
            'weton'                => 'nullable|string|max:50',
            'agama'                => 'nullable|string|max:50',
            'tingkat'              => 'nullable|string|max:100',
            'status_keanggotaan'   => 'required|in:Aktif,Tidak Aktif,Alumni,Berhenti',
            'tanggal_keanggotaan'  => 'nullable|date',
            'ranting'              => 'nullable|string|max:100',
            'rayon'                => 'nullable|string|max:100',
            'tempat_latihan'       => 'nullable|string|max:100',
            'is_active'            => 'boolean',
            'must_change_password' => 'boolean',
            'pekerjaan'            => 'nullable|string|max:100',
            'hp'                   => 'nullable|string|max:20',
            'alamat'               => 'nullable|string',
            'keterangan'           => 'nullable|string',
        ]);

        if (! empty($data['nik_input'])) {
            $encryptor             = app(NikEncryptor::class);
            $nik                   = $data['nik_input'];
            $data['nik_hash']      = $encryptor->hash($nik);
            $data['nik_encrypted'] = $encryptor->encrypt($nik);
        }
        unset($data['nik_input']);

        $data['is_active']            = $request->boolean('is_active');
        $data['must_change_password'] = $request->boolean('must_change_password');

        $member->update($data);

        return redirect()->route('admin.members.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    public function activate(Member $member): RedirectResponse
    {
        $member->forceFill(['is_active' => true])->save();

        return back()->with('success', "Akun {$member->full_name} berhasil diaktifkan.");
    }

    public function deactivate(Member $member): RedirectResponse
    {
        $member->forceFill(['is_active' => false])->save();

        return back()->with('success', "Akun {$member->full_name} berhasil dinonaktifkan.");
    }

    public function resetPassword(Member $member): RedirectResponse
    {
        if ($member->tanggal_lahir === null) {
            return back()->with('error', 'Tanggal lahir anggota belum diisi.');
        }

        $member->forceFill([
            'password'             => Hash::make($member->tanggal_lahir->format('dmY')),
            'must_change_password' => true,
        ])->save();

        return back()->with('success', "Password {$member->full_name} berhasil direset ke tanggal lahir.");
    }
}
