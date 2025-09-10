<x-filament-panels::page>
    {{-- Step 1: Filter Form --}}
    <form wire:submit.prevent="generate">
        {{ $this->form }}
        <x-filament::button type="submit" class="mt-4">Submit</x-filament::button>
    </form>

    {{-- Step 2: Tabel Mass Create --}}
    @if($karyawans)
        <form wire:submit.prevent="save">
            <div class="overflow-x-auto mt-6">
                <table class="w-full border text-sm">
                   <thead class="bg-orange-600 dark:bg-orange-500 text-gray-800 dark:text-gray-100 text-xs uppercase">
                        <tr>
                            <th colspan="3" class="border border-orange-300 dark:border-orange-400 p-2">Data Karyawan</th>
                            <th colspan="2" class="border border-orange-300 dark:border-orange-400 p-2">Gaji</th>
                            <th colspan="4" class="border border-orange-300 dark:border-orange-400 p-2">Tunjangan</th>
                            <th colspan="4" class="border border-orange-300 dark:border-orange-400 p-2">Potongan</th>
                            <th rowspan="2" class="border border-orange-300 dark:border-orange-400 p-2">Total</th>
                        </tr>
                        <tr>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Karyawan</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Formasi</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">MK</th>

                            <th class="border border-orange-300 dark:border-orange-400 p-2">Pokok</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Lembur</th>

                            <th class="border border-orange-300 dark:border-orange-400 p-2">Jabatan</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Kehadiran</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Kinerja</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Lain Lain</th>

                            <th class="border border-orange-300 dark:border-orange-400 p-2">BPJS</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Pinjaman</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Absen</th>
                            <th class="border border-orange-300 dark:border-orange-400 p-2">Lain Lain</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data as $index => $row)
                            @if ($karyawans[$index]['divisi']['name'] != 'MITRA')
                                {{-- @dd($data[0]['absen']) --}}
                           <tr class="border-t border-gray-300 dark:border-gray-600">
                                <td class="p-2 text-gray-800 dark:text-gray-100"><span class="w-auto">{{ $karyawans[$index]['nama_lengkap'] }}</span></td>
                                <td class="p-2 text-gray-800 dark:text-gray-100"><span class="w-auto">{{ $karyawans[$index]['divisi']['name'] ?? '-' }}</span></td>

                                <td><input type="number" wire:model.lazy="data.{{ $index }}.mk" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.gaji_pokok" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.gaji_lembur" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.tj_jabatan" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.tj_kehadiran" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.tj_kinerja" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.tj_lain" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.bpjs" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.pinjaman" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.absen" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td><input type="number" wire:model.lazy="data.{{ $index }}.lain_lain" class="w-auto border-none text-gray-800 dark:text-gray-100" /></td>
                                <td class="px-auto w-auto text-gray-800 dark:text-gray-100">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                            </tr>

                            @endif

                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-filament::button type="submit" class="mt-4">Simpan Semua</x-filament::button>
        </form>
    @endif
</x-filament-panels::page>
