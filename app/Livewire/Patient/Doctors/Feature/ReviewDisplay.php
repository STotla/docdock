<?php

namespace App\Livewire\Patient\Doctors\Feature;

use App\Models\Doctor;
use Livewire\Component;



class ReviewDisplay extends Component
{
    public Doctor $doctor;
    public $sort = 'latest'; // Default sorting
    public $ratingFilter = null;
    public $perPage = 2;

    public function setSort($value)
    {
        $this->sort = $value;
    }
public function setFilter($rating) {
    $this->ratingFilter = $this->ratingFilter === $rating ? null : $rating;
    $this->perPage = 5; 
}

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor->load('reviews');
    }
    public function filterByRating($rating)
    {
        $this->ratingFilter = $rating;
        $this->perPage = 5;
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function getStatsProperty()
    {
        $total = $this->doctor->reviews->count();
        $stats = [];

        for ($i = 5; $i >= 1; $i--) {
            $count = $this->doctor->reviews->where('rating', $i)->count();
            $stats[$i] = [
                'count' => $count,
                'percent' => $total > 0 ? ($count / $total) * 100 : 0
            ];
        }

        return $stats;
    }

    public function render() {
    $query = $this->doctor->reviews()->with('user');
    if ($this->ratingFilter) {
        $query->where('rating', $this->ratingFilter);
    }
    if ($this->sort === 'latest') {
        $query->latest();
    } else {
        $query->oldest();
    }

    return view('livewire.patient.doctors.feature.review-display', [
        'stats' => $this->stats,
        'reviews' => $query->paginate($this->perPage)
    ]);
    }
}
