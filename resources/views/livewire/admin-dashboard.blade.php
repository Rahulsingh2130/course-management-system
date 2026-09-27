<div>
    <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-5 border border-slate-100">
            <p class="text-sm text-slate-500">Active Courses</p>
            <p class="text-3xl font-bold mt-1">{{ $activeCourses }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-5 border border-slate-100">
            <p class="text-sm text-slate-500">Upcoming Workshops</p>
            <p class="text-3xl font-bold mt-1">{{ $upcomingWorkshops }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-5 border border-slate-100">
            <p class="text-sm text-slate-500">Total Enrollments</p>
            <p class="text-3xl font-bold mt-1">{{ $totalEnrollments }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-5 border border-slate-100">
            <p class="text-sm text-slate-500">Enrollments This Month</p>
            <p class="text-3xl font-bold mt-1">{{ $enrollmentsThisMonth }}</p>
        </div>
    </div>
</div>
