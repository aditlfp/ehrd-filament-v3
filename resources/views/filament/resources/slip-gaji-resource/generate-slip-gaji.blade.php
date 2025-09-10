<x-filament::page>
    <div class="space-y-6">
        <div class="flex items-center gap-6">
            <div>
                <h2 class="text-lg font-medium">Tambah Slip Gaji</h2>
                <p class="text-sm">Mitra: {{ optional(\App\Models\Client::find($client_id))->nama ?? '-' }} — Bulan: {{ $bulan }}</p>
            </div>
        </div>

        <form wire:submit.prevent="submit">
            {{ $this->form }}

            <div class="mt-4">
                <x-filament::button type="submit" color="primary">Submit</x-filament::button>
            </div>
        </form>
    </div>
</x-filament::page>
