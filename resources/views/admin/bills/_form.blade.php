@php $isEdit = isset($bill); @endphp

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Data Tagihan</h3>
        </div>
        <div class="p-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Anggota <span class="text-red-500">*</span></label>
                <select name="member_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white" required>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}"
                            @selected(old('member_id', $bill->member_id ?? '') == $m->id)>
                            {{ $m->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('member_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tahun <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun"
                           value="{{ old('tahun', $bill->tahun ?? now()->year) }}"
                           min="2000" max="2100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                    @error('tahun')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white" required>
                        @foreach($statuses as $s)
                            <option value="{{ $s->value }}"
                                @selected(old('status', $bill->status?->value ?? 'Belum Lunas') === $s->value)>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Uraian <span class="text-red-500">*</span></label>
                <input type="text" name="uraian"
                       value="{{ old('uraian', $bill->uraian ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                       placeholder="Iuran tahunan, dll" required>
                @error('uraian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal"
                           value="{{ old('nominal', $bill->nominal ?? 0) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                    @error('nominal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Dibayar (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="dibayar"
                           value="{{ old('dibayar', $bill->dibayar ?? 0) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                    @error('dibayar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 px-5 rounded-lg transition-colors text-sm">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Tagihan' }}
                </button>
                <a href="{{ route('admin.bills.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-5 rounded-lg transition-colors text-sm">
                    Batal
                </a>
            </div>

        </div>
    </div>
</div>
