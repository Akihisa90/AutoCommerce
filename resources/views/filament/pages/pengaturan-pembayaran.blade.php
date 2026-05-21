<x-filament-panels::page>
    <form wire:submit="save">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- QRIS Section -->
            <x-filament::card>
                <h2 class="text-lg font-bold mb-4">
                    <x-filament::icon icon="heroicon-o-qr-code" class="w-5 h-5 inline mr-1" />
                    QRIS
                </h2>

                <div class="space-y-4">
                    <x-filament-forms::field-wrapper label="Upload Gambar QR Code">
                        <x-filament::input.wrapper>
                            <input type="file" wire:model="qris_qr_image_file" accept="image/*"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-primary-50 file:text-primary-700
                                    hover:file:bg-primary-100" />
                        </x-filament::input.wrapper>
                        @if(!empty($data['qris_qr_image']))
                        <div class="mt-3">
                            <p class="text-sm text-gray-500 mb-2">QR Code saat ini:</p>
                            <img src="{{ Storage::disk('public')->url($data['qris_qr_image']) }}" alt="QRIS QR"
                                class="max-w-xs rounded-lg border shadow-sm" />
                            <button type="button" wire:click="$set('data.qris_qr_image', '')"
                                class="mt-2 text-sm text-danger-600 hover:text-danger-700">
                                <x-filament::icon icon="heroicon-m-trash" class="w-4 h-4 inline mr-1" />
                                Hapus QR Code
                            </button>
                        </div>
                        @endif
                    </x-filament-forms::field-wrapper>

                    <x-filament-forms::field-wrapper label="Deskripsi">
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="data.qris_description" />
                        </x-filament::input.wrapper>
                    </x-filament-forms::field-wrapper>
                </div>
            </x-filament::card>

            <!-- DANA Section -->
            <x-filament::card>
                <h2 class="text-lg font-bold mb-4">
                    <x-filament::icon icon="heroicon-o-device-phone-mobile" class="w-5 h-5 inline mr-1" />
                    DANA
                </h2>

                <div class="space-y-4">
                    <x-filament-forms::field-wrapper label="Nomor Telepon DANA">
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="data.dana_phone" placeholder="08xxxxxxxxxx" />
                        </x-filament::input.wrapper>
                    </x-filament-forms::field-wrapper>

                    <x-filament-forms::field-wrapper label="Nama Akun DANA">
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="data.dana_name" />
                        </x-filament::input.wrapper>
                    </x-filament-forms::field-wrapper>

                    <x-filament-forms::field-wrapper label="Upload Gambar QR Code DANA (opsional)">
                        <x-filament::input.wrapper>
                            <input type="file" wire:model="dana_qr_image_file" accept="image/*"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-primary-50 file:text-primary-700
                                    hover:file:bg-primary-100" />
                        </x-filament::input.wrapper>
                        @if(!empty($data['dana_qr_image']))
                        <div class="mt-3">
                            <p class="text-sm text-gray-500 mb-2">QR Code DANA saat ini:</p>
                            <img src="{{ Storage::disk('public')->url($data['dana_qr_image']) }}" alt="DANA QR"
                                class="max-w-xs rounded-lg border shadow-sm" />
                            <button type="button" wire:click="$set('data.dana_qr_image', '')"
                                class="mt-2 text-sm text-danger-600 hover:text-danger-700">
                                <x-filament::icon icon="heroicon-m-trash" class="w-4 h-4 inline mr-1" />
                                Hapus QR Code
                            </button>
                        </div>
                        @endif
                    </x-filament-forms::field-wrapper>
                </div>
            </x-filament::card>
        </div>

        <!-- Bank Accounts Section -->
        <x-filament::card class="mt-6">
            <h2 class="text-lg font-bold mb-4">
                <x-filament::icon icon="heroicon-o-banknotes" class="w-5 h-5 inline mr-1" />
                Rekening Bank
            </h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <h3 class="text-md font-semibold mb-3">Bank 1</h3>
                    <div class="space-y-3">
                        <x-filament-forms::field-wrapper label="Nama Bank">
                            <x-filament::input.wrapper>
                                <x-filament::input type="text" wire:model="data.bank_1_name" placeholder="BCA, BNI, dll" />
                            </x-filament::input.wrapper>
                        </x-filament-forms::field-wrapper>
                        <x-filament-forms::field-wrapper label="Nomor Rekening">
                            <x-filament::input.wrapper>
                                <x-filament::input type="text" wire:model="data.bank_1_number" />
                            </x-filament::input.wrapper>
                        </x-filament-forms::field-wrapper>
                        <x-filament-forms::field-wrapper label="Nama Pemilik Rekening">
                            <x-filament::input.wrapper>
                                <x-filament::input type="text" wire:model="data.bank_1_holder" />
                            </x-filament::input.wrapper>
                        </x-filament-forms::field-wrapper>
                    </div>
                </div>
                <div>
                    <h3 class="text-md font-semibold mb-3">Bank 2</h3>
                    <div class="space-y-3">
                        <x-filament-forms::field-wrapper label="Nama Bank">
                            <x-filament::input.wrapper>
                                <x-filament::input type="text" wire:model="data.bank_2_name" placeholder="BCA, BNI, dll" />
                            </x-filament::input.wrapper>
                        </x-filament-forms::field-wrapper>
                        <x-filament-forms::field-wrapper label="Nomor Rekening">
                            <x-filament::input.wrapper>
                                <x-filament::input type="text" wire:model="data.bank_2_number" />
                            </x-filament::input.wrapper>
                        </x-filament-forms::field-wrapper>
                        <x-filament-forms::field-wrapper label="Nama Pemilik Rekening">
                            <x-filament::input.wrapper>
                                <x-filament::input type="text" wire:model="data.bank_2_holder" />
                            </x-filament::input.wrapper>
                        </x-filament-forms::field-wrapper>
                    </div>
                </div>
            </div>
        </x-filament::card>

        <div class="flex justify-end mt-6">
            <x-filament::button type="submit" color="primary">
                Simpan Pengaturan
            </x-filament::button>
        </div>
    </form>

    @push('scripts')
    <script>
        // Handle QRIS image upload
        document.addEventListener('livewire:init', () => {
            const qrisInput = document.querySelector('input[wire\\:model="qris_qr_image_file"]');
            if (qrisInput) {
                qrisInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (ev) {
                            Livewire.dispatch('upload-qris-qr-image', { data: ev.target.result });
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            const danaInput = document.querySelector('input[wire\\:model="dana_qr_image_file"]');
            if (danaInput) {
                danaInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (ev) {
                            Livewire.dispatch('upload-dana-qr-image', { data: ev.target.result });
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
    @endpush
</x-filament-panels::page>
