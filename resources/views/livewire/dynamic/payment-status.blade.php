<div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full  font-bold border capitalize tracking-tight {{ $classes }} 
    {{ match($status) {
        'paid' => 'bg-emerald-500 text-white border-emerald-500',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse',
        'failed' => 'bg-rose-50 text-rose-700 border-rose-200',
        'marked_as_issue' => 'bg-purple-50 text-purple-700 border-purple-200',
        default => 'bg-slate-50 text-slate-700 border-slate-200'
    } }}">
    
    <!-- Status Icon -->
    <svg xmlns="http://w3.org" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-90">
        @if($status == 'paid')
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
        @elseif($status == 'pending')
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
        @elseif($status == 'failed')
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
        @else
            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
        @endif
    </svg>

    <!-- Clean Status Name -->
    <span>{{ str_replace('_', ' ', $status) }}</span>
</div>
