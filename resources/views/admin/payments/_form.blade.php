@php $isEdit = isset($payment); @endphp

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Data Pembayaran</h3>
        </div>
        <div class="p-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Anggota <span class="text-red-500">*</span></label>
                <select name="member_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white" required>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}"
                            @selected(old('member_id', $payment->member_id ?? '') == $m->id)>
                            {{ $m->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('member_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tagihan (Opsional)</label>
                <select name="bill_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                    <option value="">-- Tidak ada tagihan spesifik --</option>
                    @foreach($bills as $b)
                        <option value="{{ $b->id }}"
                            @selected(old('bill_id', $payment->bill_id ?? '') == $b->id)>
                            {{ $b->member?->full_name }} — {{ $b->uraian }} ({{ $b->tahun }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal"
                           value="{{ old('tanggal', isset($payment->tanggal) ? $payment->tanggal->format('Y-m-d') : now()->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                    @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal"
                           value="{{ old('nominal', $payment->nominal ?? 0) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                    @error('nominal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Uraian <span class="text-red-500">*</span></label>
                <input type="text" name="uraian"
                       value="{{ old('uraian', $payment->uraian ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                       placeholder="Keterangan pembayaran" required>
                @error('uraian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan Tambahan</label>
                <input type="text" name="keterangan"
                       value="{{ old('keterangan', $payment->keterangan ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                       placeholder="Opsional">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 px-5 rounded-lg transition-colors text-sm">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Catat Pembayaran' }}
                </button>
                <a href="{{ route('admin.payments.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-5 rounded-lg transition-colors text-sm">
                    Batal
                </a>
            </div>

        </div>
    </div>
</div>
