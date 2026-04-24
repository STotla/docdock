<?php 
$disabled = in_array($doctor->profile_status, ['approved', 'under review']);
?>
<x-filament-panels::page>
   <div class="space-y-6">
    {{-- DRAFT STATUS --}}
    @if ($doctor->profile_status === 'draft')
        <x-filament::section
            icon="heroicon-m-pencil-square"
            icon-color="info"
            heading="Profile in Draft"
        >
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Your profile is currently in the <strong>draft</strong> stage. Please complete all required fields and submit it for review to activate your account.
            </div>
        </x-filament::section>
    @endif

    {{-- REJECTED STATUS --}}
    @if ($doctor->profile_status === 'rejected')
        <x-filament::section
            icon="heroicon-m-x-circle"
            icon-color="danger"
            heading="Profile Rejected"
        >
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-bold text-danger-600">Reason:</span> 
                {{ $doctor->admin_note ?? 'Please review your information and try again.' }}
            </div>
        </x-filament::section>
    @endif

    {{-- UNDER REVIEW STATUS --}}
    @if ($doctor->profile_status === 'under review')
        <x-filament::section
            icon="heroicon-m-clock"
            icon-color="warning"
            heading="Application Submitted"
        >
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Your profile is currently <strong>under review</strong>. Our team will verify your details shortly.
            </div>
        </x-filament::section>
    @endif
</div>


    {{ $this->form }}

    <div class="mt-6 flex flex-wrap gap-3">
        <x-filament::button wire:click="saveDraft" wire:loading.attr="disabled" :disabled="$disabled">
            Save Draft
        </x-filament::button>
        <x-filament::button wire:click="submitForReview" wire:loading.attr="disabled" :disabled="$disabled">
            Submit for Review
        </x-filament::button>

    </div>
</x-filament-panels::page>