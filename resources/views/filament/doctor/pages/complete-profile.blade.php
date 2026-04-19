<?php 
$disabled = in_array($doctor->profile_status,['approved','under review']);
?>
<x-filament-panels::page>
    @if ($doctor->profile_status === 'rejected' && $doctor->admin_note)
        <div class="rounded-lg border border-danger-300 bg-danger-50 p-4 mb-4">
            <div class="font-semibold text-danger-700">Rejected</div>
            <div class="text-sm text-danger-700 mt-1">{{ $doctor->admin_note }}</div>
        </div>
    @endif

    @if ($doctor->profile_status === 'under review')
        <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 p-4">
    <div class="font-semibold text-yellow-800">Submitted</div>
    <div class="mt-1 text-sm text-yellow-800">Your profile is under review.</div>
</div>
    @endif

    {{ $this->form }}

    <div class="mt-6 flex flex-wrap gap-3">
        <x-filament::button wire:click="saveDraft" wire:loading.attr="disabled"  
         :disabled="$disabled"
        >
            Save Draft
        </x-filament::button>
        <x-filament::button wire:click="submitForReview" wire:loading.attr="disabled" 
        :disabled="$disabled"
        >
            Submit for Review
        </x-filament::button>

    </div>
</x-filament-panels::page>