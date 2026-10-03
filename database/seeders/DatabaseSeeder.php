<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Post;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private const CITIES = ['Bengaluru', 'Mumbai', 'Delhi', 'Hyderabad', 'Pune', 'Chennai'];

    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@coursemanagement.test'], [
            'name' => 'Rahul Kumar Singh', 'password' => Hash::make('password'), 'role' => User::ROLE_ADMIN,
        ]);
        $instructors = collect([
            ['Priya Sharma', 'instructor@coursemanagement.test'],
            ['Vikram Rao', 'vikram@coursemanagement.test'],
            ['Neha Kapoor', 'neha@coursemanagement.test'],
        ])->map(fn ($i) => User::updateOrCreate(['email' => $i[1]], [
            'name' => $i[0], 'password' => Hash::make('password'), 'role' => User::ROLE_INSTRUCTOR,
        ]));
        $student = User::updateOrCreate(['email' => 'student@coursemanagement.test'], [
            'name' => 'Amit Verma', 'password' => Hash::make('password'), 'role' => User::ROLE_STUDENT,
        ]);

        foreach ($this->catalog() as $categoryName => $courses) {
            $category = Category::updateOrCreate(['slug' => Str::slug($categoryName)], [
                'name' => $categoryName, 'is_active' => true,
            ]);

            foreach ($courses as $i => [$title, $short, $level, $days, $price]) {
                $course = Course::updateOrCreate(['slug' => Str::slug($title)], [
                    'category_id' => $category->id,
                    'title' => $title,
                    'short_description' => $short,
                    'description' => "{$short} This {$days}-day {$level} programme combines expert-led instruction, real-world case studies and hands-on exercises, so you leave with skills you can apply at work the very next day. Course fee includes study material, practice assessments and post-course support.",
                    'price' => $price,
                    'duration_days' => $days,
                    'level' => $level,
                    'rating' => [4.6, 4.7, 4.8, 4.9][$i % 4],
                    'is_featured' => $i === 0,
                    'is_active' => true,
                    'outcomes' => [
                        "Understand the core principles and terminology of {$title}",
                        'Apply proven frameworks and tools to real projects',
                        'Prepare confidently for the certification exam',
                        'Improve team delivery, communication and decision making',
                    ],
                    'syllabus' => [
                        ['Module 1: Introduction & Fundamentals', 'Key concepts, terminology, roles and the business case.'],
                        ['Module 2: Frameworks & Processes', 'Walk through the end-to-end framework with worked examples.'],
                        ['Module 3: Tools & Techniques', 'Hands-on practice with templates, tools and group exercises.'],
                        ['Module 4: Case Studies', 'Real-world scenarios and lessons learned from industry.'],
                        ['Module 5: Exam Preparation', 'Mock tests, exam strategies and Q&A with the trainer.'],
                    ],
                ]);

                if ($course->workshops()->exists()) {
                    continue;
                }

                $modes = array_keys(Workshop::MODES);
                foreach (range(0, 3) as $n) {
                    $start = now()->addDays(7 + $n * 14 + $i * 3)->setTime(9, 30);
                    $mode = $modes[$n % 4];
                    $workshop = Workshop::create([
                        'course_id' => $course->id,
                        'instructor_id' => $instructors[($i + $n) % 3]->id,
                        'batch_name' => ($n % 2 ? 'Weekend Batch - ' : 'Weekday Batch - ') . $start->format('M Y'),
                        'mode' => $mode,
                        'location' => match ($mode) {
                            'classroom' => self::CITIES[($i + $n) % 6],
                            'onsite' => 'At your premises',
                            default => 'Live online',
                        },
                        'starts_at' => $start,
                        'ends_at' => $start->copy()->addDays($days - 1)->setTime(17, 30),
                        'seat_limit' => 25,
                        'price' => $mode === 'online_self_paced' ? round($price * 0.6, -2) : null,
                        'is_active' => true,
                    ]);

                    if ($n === 0 && $i === 0 && $student->enrollments()->count() < 2) {
                        Enrollment::firstOrCreate(['user_id' => $student->id, 'workshop_id' => $workshop->id], [
                            'status' => Enrollment::STATUS_CONFIRMED, 'enrolled_at' => now()->subDays(2),
                        ]);
                    }
                }
            }
        }

        foreach ($this->posts() as $n => [$title, $topic, $excerpt]) {
            Post::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'topic' => $topic,
                'excerpt' => $excerpt,
                'body' => "<p>{$excerpt}</p><p>Whether you are just starting out or looking to level up, a structured learning path makes all the difference. Start by identifying the certification that matches your career goal, choose a delivery mode that fits your schedule, and practise with real scenarios.</p><h3>Key takeaways</h3><ul><li>Pick a certification aligned with your role and industry.</li><li>Combine instructor-led training with practice exams.</li><li>Apply what you learn on live projects as soon as possible.</li></ul><p>Have questions? Our learning advisors are available 24/7 to help you choose the right course.</p>",
                'read_minutes' => 4 + $n,
                'is_published' => true,
                'published_at' => now()->subDays($n * 6 + 1),
            ]);
        }
    }

    private function catalog(): array
    {
        return [
            'Project Management' => [
                ['PMP Certification Training', 'Prepare for the PMI Project Management Professional exam.', 'Advanced', 5, 54999],
                ['PRINCE2 Foundation & Practitioner', 'Master the PRINCE2 method for controlled project delivery.', 'Foundation', 5, 49999],
                ['Agile Project Management', 'Blend agile and traditional approaches to deliver faster.', 'Foundation', 3, 29999],
            ],
            'Agile & Scrum' => [
                ['Certified Scrum Master (CSM)', 'Learn to facilitate Scrum teams and remove impediments.', 'Foundation', 2, 24999],
                ['Professional Scrum Product Owner', 'Maximise product value and manage the backlog like a pro.', 'Intermediate', 2, 26999],
                ['Advanced Agile Coaching', 'Coach teams and organisations through agile transformation.', 'Advanced', 3, 39999],
            ],
            'IT Service Management' => [
                ['ITIL 4 Foundation', 'Understand the ITIL 4 service value system and practices.', 'Foundation', 3, 32999],
                ['ITIL 4 Managing Professional', 'Deep-dive into the four ITIL 4 MP modules.', 'Advanced', 5, 64999],
                ['DevOps Foundation', 'Bridge development and operations with DevOps culture and tools.', 'Foundation', 3, 28999],
            ],
            'Cloud & DevOps' => [
                ['AWS Cloud Practitioner', 'Get started with core AWS services, pricing and security.', 'Foundation', 2, 19999],
                ['AWS Solutions Architect Associate', 'Design resilient, cost-efficient architectures on AWS.', 'Intermediate', 4, 44999],
                ['CI/CD with GitHub Actions', 'Automate build, test and deploy pipelines end to end.', 'Intermediate', 2, 17999],
            ],
            'Data & AI' => [
                ['Machine Learning Foundations', 'Build and evaluate your first machine learning models.', 'Foundation', 4, 34999],
                ['Python for Data Science', 'Analyse and visualise data with pandas, NumPy and Matplotlib.', 'Foundation', 3, 22999],
                ['Generative AI for Professionals', 'Use LLMs responsibly to boost productivity at work.', 'Foundation', 2, 15999],
            ],
            'Quality & Lean Six Sigma' => [
                ['Lean Six Sigma Green Belt', 'Reduce defects and waste using DMAIC methodology.', 'Intermediate', 5, 37999],
                ['Lean Six Sigma Black Belt', 'Lead complex improvement projects and mentor Green Belts.', 'Advanced', 5, 59999],
                ['ISO 9001 Lead Auditor', 'Plan and conduct quality management system audits.', 'Advanced', 5, 42999],
            ],
            'Cyber Security' => [
                ['CompTIA Security+', 'Cover core security concepts, threats and mitigations.', 'Intermediate', 5, 39999],
                ['Certified Ethical Hacker (CEH)', 'Learn attacker techniques to better defend systems.', 'Advanced', 5, 59999],
                ['ISO 27001 Foundation', 'Understand information security management systems.', 'Foundation', 2, 21999],
            ],
            'Business Analysis' => [
                ['CBAP Certification Prep', 'Prepare for the IIBA Certified Business Analysis Professional exam.', 'Advanced', 4, 41999],
                ['Business Analysis Foundation', 'Learn BA techniques, requirement elicitation and modelling.', 'Foundation', 3, 24999],
                ['Data Analytics with Power BI', 'Turn raw data into interactive dashboards with Power BI.', 'Intermediate', 3, 23999],
            ],
        ];
    }

    private function posts(): array
    {
        return [
            ['How to Choose the Right Project Management Certification', 'Project Management', 'PMP, PRINCE2 or Agile? A simple guide to picking the credential that fits your career stage.'],
            ['Scrum vs Kanban: Which Agile Framework Suits Your Team?', 'Agile & Scrum', 'Compare roles, cadences and metrics to decide between Scrum and Kanban.'],
            ['10 Tips to Pass the ITIL 4 Foundation Exam on Your First Try', 'IT Service Management', 'Study plans, mock tests and exam-day strategies that actually work.'],
            ['Getting Started with AWS: A Beginner Roadmap', 'Cloud & DevOps', 'From Cloud Practitioner to Solutions Architect: your learning path explained.'],
            ['Why Upskilling Your Team Pays for Itself', 'Corporate Training', 'The measurable ROI of structured corporate training programmes.'],
            ['Generative AI at Work: Practical Use Cases for Every Role', 'Data & AI', 'Concrete ways professionals are using AI assistants to save hours each week.'],
        ];
    }
}
