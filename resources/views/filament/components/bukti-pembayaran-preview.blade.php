@php
    $record = $this->getRecord();
@endphp

@if($record && $record->bukti_pembayaran)
    <div class="text-center">
        <img src="{{ Storage::disk('public')->url($record->bukti_pembayaran) }}"
             alt="Bukti Pembayaran"
             class="img-fluid rounded shadow-sm"
             style="max-width: 300px;">
    </div>
@else
    <p class="text-muted small">Tidak ada bukti pembayaran.</p>
@endif
