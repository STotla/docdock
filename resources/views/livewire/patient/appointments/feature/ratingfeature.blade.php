<div>
     @if($appointment->review)
        <!-- SHOW THE EXISTING REVIEW -->
        <div class="">
            <div class="flex justify-between items-start mb-3">
                <div>

                    <h2 class="text font-semibold text-white">Your Review</h2>
                    <div class="flex gap-0.5 mt-1">
                        @foreach(range(1, 5) as $i)
                            <svg class="w-4 h-4 {{ $appointment->review->rating >= $i ? 'text-yellow-400' : 'text-slate-700' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endforeach
                    </div>
                </div>
                <span class="text-[10px] uppercase tracking-wider text-slate-500 font-medium">
                    {{ $appointment->review->created_at->format('M d, Y') }}
                </span>
            </div>
            
            <p class="text-sm text-slate-300 italic leading-relaxed">
                "{{ $appointment->review->comment ?? $appointment->review->review }}"
            </p>
        </div>
    @endif

    @if($appointment->canBeReviewed())
        <div class="mb-4 p-4 rounded-xl bg-primary-500/5 border border-primary-500/10">
            <div class="flex flex-col gap-1 mb-3">
                <p class="text-sm font-medium text-slate-100">
                    Your appointment is complete and your notes are ready!
                </p>
                <p class="text-xs text-slate-400">
                    How was your experience with {{ $appointment->doctor->user->name }}? Your feedback helps others find
                    great care.
                </p>
            </div>

            <!-- Trigger for Modal -->
            <button x-on:click="$wire.showModal = true"
                class="text-xs font-semibold bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all shadow-sm shadow-primary-500/20">
                Share Your Experience
            </button>
        </div>
    @endif


    <!-- Simple Modal Example (Tailwind + Alpine.js or Vanilla JS) -->
    <div wire:show="showModal" x-transition.duration.500ms
        class="  fixed inset-0 z-50  items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-white/[0.08] m-auto mt-4 w-full max-w-md p-6 rounded-2xl shadow-2xl">
            <h3 class="text-lg font-semibold text-white mb-1">Submit your review</h3>
            <p class="text-sm text-slate-400 mb-6">Rate your visit with {{ $appointment->doctor->user->name }}</p>

            <form>
                @csrf
                <!-- Star Rating (Simple Radio Group) -->
                <div class="flex gap-2 mb-6 justify-center">
                    @foreach(range(1, 5) as $i)
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="rating" name="rating" value="{{ $i }}" class="hidden"
                                required>

                            <!-- Use a conditional class based on the $rating value -->
                            <svg class="w-8 h-8 transition-colors {{ $this->rating >= $i ? 'text-yellow-400' : 'text-slate-600' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </label>
                    @endforeach
                     @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                </div>

                <textarea name="review" wire:model.live="review" rows="4"
                    placeholder="Tell us what you liked or how we can improve..."
                    class="w-full bg-slate-800 border border-white/[0.08] rounded-xl px-4 py-3 text-sm text-white focus:ring-2 focus:ring-primary-500 focus:outline-none mb-6"></textarea>
                @error('review') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <div class="flex gap-3">
                    <button type="button" x-on:click="$wire.showModal = false"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-white/[0.05]">Cancel</button>
                    <button type="button" wire:click="saveReview"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold bg-primary-500 text-white shadow-lg shadow-primary-500/20">Submit
                        Review</button>
                </div>
            </form>
        </div>
    </div>


</div>