@php $isEdit = isset($certificate); @endphp

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Data Sertifikat</h3>
        </div>
        <div class="p-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Anggota <span class="text-red-500">*</span></label>
                <select name="member_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white" required>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}"
                            @selected(old('member_id', $certificate->member_id ?? '') == $m->id)>
                            {{ $m->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('member_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis / Tingkat <span class="text-red-500">*</span></label>
                <input type="text" name="jenis"
                       value="{{ old('jenis', $certificate->jenis ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                       placeholder="Putih, Kuning, dll" required>
                @error('jenis')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Sertifikat <span class="text-red-500">*</span></label>
                <input type="text" name="nomor"
                       value="{{ old('nomor', $certificate->nomor ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary-500"
                       placeholder="Nomor unik sertifikat" required>
                @error('nomor')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal"
                       value="{{ old('tanggal', isset($certificate->tanggal) ? $certificate->tanggal->format('Y-m-d') : '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 px-5 rounded-lg transition-colors text-sm">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Sertifikat' }}
                </button>
                <a href="{{ route('admin.certificates.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-5 rounded-lg transition-colors text-sm">
                    Batal
                </a>
            </div>

        </div>
    </div>
</div>
