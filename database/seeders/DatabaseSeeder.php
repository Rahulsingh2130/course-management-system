<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Rahul Kumar Singh',
            'email' => 'admin@coursemanagement.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        $instructor = User::create([
            'name' => 'Priya Sharma',
            'email' => 'instructor@coursemanagement.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_INSTRUCTOR,
        ]);

        $student = User::create([
            'name' => 'Amit Verma',
            'email' => 'student@coursemanagement.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STUDENT,
        ]);

        $categories = collect(['Agile & Scrum', 'Project Management', 'Cloud & DevOps', 'Data & AI'])
            ->map(fn ($name) => Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'is_active' => true,
            ]));

        $courseTitles = [
            'Agile & Scrum' => ['Certified Scrum Master', 'Advanced Agile Coaching'],
            'Project Management' => ['PMP Certification Prep', 'Agile Project Management'],
            'Cloud & DevOps' => ['AWS Fundamentals', 'CI/CD with GitHub Actions'],
            'Data & AI' => ['Machine Learning Foundations', 'Python for AI Engineers'],
        ];

        foreach ($categories as $category) {
            foreach ($courseTitles[$category->name] as $title) {
                $course = Course::create([
                    'category_id' => $category->id,
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . Str::random(4),
                    'description' => "A hands-on {$title} program designed for working professionals.",
                    'price' => fake()->randomElement([4999, 9999, 14999, 19999]),
                    'is_active' => true,
                ]);

                $workshop = Workshop::create([
                    'course_id' => $course->id,
                    'instructor_id' => $instructor->id,
                    'batch_name' => 'Weekend Batch - ' . now()->addDays(rand(5, 30))->format('M Y'),
                    'starts_at' => now()->addDays(rand(5, 30)),
                    'ends_at' => now()->addDays(rand(31, 45)),
                    'seat_limit' => 25,
                    'is_active' => true,
                ]);

                Enrollment::create([
                    'user_id' => $student->id,
                    'workshop_id' => $workshop->id,
                    'status' => Enrollment::STATUS_CONFIRMED,
                    'enrolled_at' => now()->subDays(rand(1, 10)),
                ]);
            }
        }
    }
}
