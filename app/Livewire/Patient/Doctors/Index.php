<?php

namespace App\Livewire\Patient\Doctors;

use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Doctors')]
#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'sort')]
    public $sort_by = 'recommended';

    #[Url(as: 'specialty')]
    public $specializationId;

    #[Url(as: 'st')]
    public $stateCode;

    #[Url(as: 'c')]
    public $city;

    #[Url(as: 'q')]
    public $searchName;

    #[Url(as: 'r')]
    public $radius = 50; // Default 50km

    // Location properties (not in URL to keep it clean, set via JS)
    public $userLat;
    public $userLng;
    public $geoLocationLoaded = false;

    public $specialization;

    public function resetFilters()
    {
        $this->reset(['searchName', 'city', 'stateCode', 'radius', 'sort_by']);
        $this->resetPage(pageName: 'doctors-page');
    }

    public function updateRadius($value)
    {
        $this->radius = $value;
        $this->resetPage(pageName: 'doctors-page');
        $this->dispatch('doctor-filter-changed');
    }

    public function updateSearchName($value)
    {
        $this->searchName = $value;
        $this->resetPage(pageName: 'doctors-page');
    }

    public function updateSortBy($value)
    {
        $this->sort_by = $value;
        $this->resetPage(pageName: 'doctors-page');
    }

    public function updated($property)
    {
        if (in_array($property, ['searchName', 'city', 'stateCode', 'radius', 'sort_by'])) {
            $this->resetPage(pageName: 'doctors-page');
        }
    }

    public function render()
    {
        $this->specialization = $this->specializationId ? Specialization::find($this->specializationId) : null;

        $query = Doctor::query()
            ->active()
            ->with(['user', 'specialization']);
            FacadesLog::info("Render called with radius: {$this->radius}, userLat: {$this->userLat}, userLng: {$this->userLng}");
        // Distance calculation and filtering
        if ($this->userLat && $this->userLng) {
            // Ensure doctors have latitude/longitude data
            $query->whereNotNull('latitude')->whereNotNull('longitude');

            // Use custom haversine_distance function
            $query->selectRaw("doctors.*, haversine_distance(?, ?, latitude, longitude) AS distance", [
                $this->userLat,
                $this->userLng
            ]);

            // Apply distance filter only if radius is less than max
            if ((int)$this->radius < 200) {
                FacadesLog::info("Applying distance filter with radius: {$this->radius}");
                 
                $query->whereRaw('distance < ?', [(int)$this->radius]);
            }
            

            $query->orderBy('distance', 'asc');
            }
            
            // Search and filtering
            $query->when($this->searchName, function ($q) {
            $q->whereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $this->searchName . '%'));
        });

        $query->when($this->specializationId, fn($q) => $q->where('specialization_id', $this->specializationId))
            ->when($this->stateCode, fn($q) => $q->where('state_code', $this->stateCode))
            ->when($this->city, fn($q) => $q->where('city', 'like', '%' . $this->city . '%'));

            // Sorting
            if ($this->sort_by === 'rating_high') {
                $query->orderByDesc('average_rating');
                } elseif ($this->sort_by === 'exp_high') {
                    $query->orderByDesc('experience');
                    } elseif ($this->sort_by === 'fee_low') {
                        $query->orderBy('consultation_fee');
                        } elseif ($this->sort_by === 'fee_high') {
                            $query->orderByDesc('consultation_fee');
                            }
                            
                            return view('livewire.patient.doctors.index', [
                                'doctors' => $query->get(),
        ]);
    }
}
