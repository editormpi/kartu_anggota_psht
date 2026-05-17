@php $isEdit = isset($member); @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kiri: Form --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Identitas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700 text-sm">Identitas Diri</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $member->full_name ?? '') }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 @error('full_name') border-red-400 @else border-gray-300 @enderror"
                           placeholder="Nama lengkap anggota" required>
                    @error('full_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        NIK (16 digit) @if(!$isEdit)<span class="text-red-500">*</span>@endif
                    </label>
                    <input type="text" name="nik_input" maxlength="16" pattern="\d{16}"
                           class="w-full px-3 py-2 border rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary-500 @error('nik_input') border-red-400 @else border-gray-300 @enderror"
                           placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : '1234567890123456' }}"
                           @if(!$isEdit) required @endif>
                    @error('nik_input')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    @if($isEdit)
                        <p class="text-xs text-gray-400 mt-1">NIK saat ini: {{ $member->masked_nik ?? '****************' }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected(old('jenis_kelamin', $member->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $member->jenis_kelamin ?? '') === 'P')>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $member->tempat_lahir ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                           placeholder="Kota tempat lahir">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', isset($member->tanggal_lahir) ? $member->tanggal_lahir->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Weton</label>
                    <input type="text" name="weton" value="{{ old('weton', $member->weton ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                           placeholder="Cth: Senin Pon">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Agama</label>
                    <input type="text" name="agama" value="{{ old('agama', $member->agama ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                           placeholder="Islam, Kristen, dll">
                </div>

            </div>
        </div>

        {{-- Keanggotaan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700 text-sm">Keanggotaan</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tingkat (Sabuk)</label>
                    <input type="text" name="tingkat" value="{{ old('tingkat', $member->tingkat ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                           placeholder="Cth: Putih, Kuning, dll">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status Keanggotaan <span class="text-red-500">*</span></label>
                    <select name="status_keanggotaan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white" required>
                        @foreach($statuses as $s)
                            <option value="{{ $s->value }}"
                                @selected(old('status_keanggotaan', $member->status_keanggotaan?->value ?? 'Aktif') === $s->value)>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Keanggotaan</label>
                    <input type="date" name="tanggal_keanggotaan"
                           value="{{ old('tanggal_keanggotaan', isset($member->tanggal_keanggotaan) ? $member->tanggal_keanggotaan->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Ranting</label>
                    <input type="text" name="ranting" value="{{ old('ranting', $member->ranting ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Rayon</label>
                    <input type="text" name="rayon" value="{{ old('rayon', $member->rayon ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tempat Latihan</label>
                    <input type="text" name="tempat_latihan" value="{{ old('tempat_latihan', $member->tempat_latihan ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

            </div>
        </div>

        {{-- Kontak --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700 text-sm">Kontak & Lainnya</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $member->pekerjaan ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">No. HP</label>
                    <input type="tel" name="hp" value="{{ old('hp', $member->hp ?? '') }}" maxlength="20"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                           placeholder="08xxxxxxxxxx">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                    <textarea name="alamat" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                              placeholder="Alamat lengkap">{{ old('alamat', $member->alamat ?? '') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                              placeholder="Catatan tambahan">{{ old('keterangan', $member->keterangan ?? '') }}</textarea>
                </div>

            </div>
        </div>

    </div>

    {{-- Kanan: Status akun --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700 text-sm">Status Akun</h3>
            </div>
            <div class="p-5 space-y-4">

                <label class="flex items-center justify-between cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Akun Aktif</p>
                        <p class="text-xs text-gray-400">Anggota bisa login</p>
                    </div>
                    <div x-data="{ checked: {{ old('is_active', ($member->is_active ?? false) ? 'true' : 'false') }} }"
                         class="relative">
                        <input type="hidden" name="is_active" :value="checked ? '1' : '0'">
                        <button type="button" @click="checked = !checked"
                                :class="checked ? 'bg-green-500' : 'bg-gray-200'"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1">
                            <span :class="checked ? 'translate-x-6' : 'translate-x-1'"
                                  class="inline-block h-4 w-4 transform bg-white rounded-full transition-transform shadow-sm"></span>
                        </button>
                    </div>
                </label>

                <label class="flex items-center justify-between cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Wajib Ubah Password</p>
                        <p class="text-xs text-gray-400">Paksa ganti saat login</p>
                    </div>
                    <div x-data="{ checked: {{ old('must_change_password', ($member->must_change_password ?? true) ? 'true' : 'false') }} }"
                         class="relative">
                        <input type="hidden" name="must_change_password" :value="checked ? '1' : '0'">
                        <button type="button" @click="checked = !checked"
                                :class="checked ? 'bg-amber-500' : 'bg-gray-200'"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1">
                            <span :class="checked ? 'translate-x-6' : 'translate-x-1'"
                                  class="inline-block h-4 w-4 transform bg-white rounded-full transition-transform shadow-sm"></span>
                        </button>
                    </div>
                </label>

            </div>
        </div>

        {{-- Tombol simpan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors text-sm">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Anggota' }}
            </button>
            <a href="{{ route('admin.members.index') }}"
               class="block text-center w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-colors text-sm">
                Batal
            </a>
        </div>
    </div>

</div>
