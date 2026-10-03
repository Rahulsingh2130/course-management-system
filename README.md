# Course & Workshop Management System

A full-stack admin platform for managing online courses, live workshops, categories, and student enrollments — built to mirror real-world LMS/certification-platform requirements (course catalogs, batch scheduling, enrollment tracking, and reporting dashboards).

This project was built to demonstrate the same patterns used in production: a Livewire-driven admin panel for internal operations, a public-facing Vue.js catalog for end users, and a versioned REST API connecting the two.

## ✨ Features

**Admin Panel (Livewire)**
- Dashboard with live stats (active courses, upcoming workshops, total enrollments, monthly signups)
- Course CRUD with category assignment, active/inactive toggling, and rich validation
- Workshop scheduler — create batches with start/end date-time, seat limits, and instructor assignment
- Enrollment manager — search, filter by course/workshop, approve/cancel enrollments
- Dynamic category + course filtering so only active offerings surface to end users
- Flash messaging and inline form validation feedback on every action

**Public Catalog (Vue.js + REST API)**
- Course catalog component that consumes the REST API (`/api/courses`) with category filters and search
- Workshop calendar component showing upcoming batches with real-time seat availability
- Enrollment flow that posts to the API and reflects confirmation without a full page reload

**Backend**
- REST API (`routes/api.php`) with resource controllers for courses and workshops
- Eloquent relationships: `Category → Course → Workshop → Enrollment`
- Optimized queries (eager loading via `with()`) to avoid N+1 issues on the catalog and dashboard views
- Multi-tier auth scaffold (admin / instructor / student roles) via a `role` column + middleware gate
- Database seeders for realistic demo data (categories, courses, workshops, sample enrollments)

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 11 |
| Reactive Admin UI | Livewire 3 |
| Frontend (public) | Vue.js 3 (Composition API) |
| Styling | TailwindCSS |
| Database | MySQL + Eloquent ORM |
| API | Laravel REST Resource Controllers |

## 📁 Project Structure

```
app/
  Http/
    Controllers/Api/     → CourseApiController, WorkshopApiController (REST endpoints)
    Livewire/            → AdminDashboard, CourseManager, WorkshopScheduler, EnrollmentManager
    Requests/            → Form request validation classes
  Models/                → User, Category, Course, Workshop, Enrollment
database/
  migrations/            → Schema for categories, courses, workshops, enrollments
  seeders/                → Demo data seeder
resources/
  views/livewire/        → Blade templates for each Livewire component
  js/components/         → CourseCatalog.vue, WorkshopCalendar.vue
routes/
  web.php / api.php
```

## 🚀 Setup

Requirements: PHP 8.2+, Composer, Node.js/npm, and a running MySQL server.

```bash
git clone <your-repo-url>
cd course-management-system
composer install

# Windows PowerShell:
Copy-Item .env.example .env
# macOS/Linux:
cp .env.example .env

php artisan key:generate

# Create the database configured in .env and configure its credentials, then:
php artisan migrate --seed
npm install
npm run dev

# In a separate terminal:
php artisan serve
```

Visit `http://localhost:8000` for the public catalog. The `/admin` routes are protected, but this project does not yet include login routes.

In Windows PowerShell, use `npm.cmd install` and `npm.cmd run dev` if the `npm` command is blocked by the script execution policy.

### Run with Laragon

Set `APP_URL` in `.env` to `http://course-management-system.test`. Laragon's Apache virtual host must use `C:/laragon/www/course-management-system/public` for both `DocumentRoot` and its `<Directory>` path; serving the project root shows a directory listing instead of the Laravel app. After changing the virtual host, restart Apache from Laragon's menu, then open `http://course-management-system.test`.

Run `npm.cmd run dev` from the project folder while developing the frontend. Keep Laragon's Apache and MySQL services running; you do not need `php artisan serve` when using the `.test` address.

## 🧠 Why this project

I built this to reflect the actual problems I solve day-to-day as a Full Stack Developer at Agilemania Technologies — managing courses, workshops, and schedules on a live certification platform — but as a clean, from-scratch codebase I can openly share and iterate on.

## 📌 Roadmap / Next Steps
- [ ] Email notifications on enrollment confirmation (Laravel Notifications + queues)
- [ ] Instructor-facing dashboard (attendance, batch notes)
- [ ] Payment gateway integration for paid workshops
- [ ] Automated testing (Pest/PHPUnit for API + Livewire components)

## 📄 License
MIT
