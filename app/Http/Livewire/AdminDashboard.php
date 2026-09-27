<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Workshop;
use Livewire\Component;

class AdminDashboard extends Component
{
    public int $activeCourses = 0;
    public int $upcomingWorkshops = 0;
    public int $totalEnrollments = 0;
    public int $enrollmentsThisMonth = 0;

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->activeCourses = Course::where('is_active', true)->count();
        $this->upcomingWorkshops = Workshop::upcoming()->count();
        $this->totalEnrollments = Enrollment::where('status', '!=', 'cancelled')->count();
        $this->enrollmentsThisMonth = Enrollment::whereMonth('enrolled_at', now()->month)
            ->whereYear('enrolled_at', now()->year)
            ->count();
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
