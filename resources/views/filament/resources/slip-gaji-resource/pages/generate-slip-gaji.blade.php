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
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Karyawan</th>
                            <th>Formasi</th>
                            <th>Pokok</th>
                            <th>Lembur</th>
                            <th>Jabatan</th>
                            <th>Kehadiran</th>
                            <th>Kinerja</th>
                            <th>Lain-Lain</th>
                            <th>BPJS</th>
                            <th>Pinjaman</th>
                            <th>Absen</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $row)
                            <tr class="border-t">
                                <td class="p-2">{{ $karyawans[$index]['nama'] }}</td>
                                <td>{{ $karyawans[$index]['divisi']['name'] ?? '-' }}</td>
                                <td><input type="number" wire:model="data.{{ $index }}.gaji_pokok" class="w-20 border" /></td>
                                <td><input type="number" wire:model="data.{{ $index }}.gaji_lembur" class="w-20 border" /></td>
                                <td><input type="number" wire:model="data.{{ $index }}.tj_jabatan" class="w-20 border" /></td>
                                <td><input type="number" wire:model="data.{{ $index }}.tj_kehadiran" class="w-20 border" /></td>
                                <td><input type="number" wire:model="data.{{ $index }}.tj_kinerja" class="w-20 border" /></td>
                                <td><input type="number" wire:model="data.{{ $index }}.tj_lain" class="w-20 border" /></td>
                                <td><input type="number" wire:model="data.{{ $index }}.bpjs" class="w-20 border" /></td>
                                <td><input type="number" wire:model="data.{{ $index }}.pinjaman" class="w-20 border" /></td>
                                <td><input type="number" wire:model="data.{{ $index }}.absen" class="w-20 border" /></td>
                                <td class="px-2">Rp {{ number_format($this->hitungTotal($row),0,',','.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-filament::button type="submit" class="mt-4">Simpan Semua</x-filament::button>
        </form>
    @endif
</x-filament-panels::page>
