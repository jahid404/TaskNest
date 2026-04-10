# TaskNest

TaskNest is a modern, high-performance task management application built with a focus on simplicity, speed, and clean aesthetics. It provides a robust dashboard for tracking tasks, managing priorities, and organizing workflows.

## 🚀 Technologies Used

- **Backend**: [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Frontend**: [Tailwind CSS 4](https://tailwindcss.com/) & [Alpine.js](https://alpinejs.dev/)
- **Build Tool**: [Vite](https://vitejs.dev/)
- **Testing**: [Pest PHP](https://pestphp.com/)
- **Others**: Axios, NProgress, [TailAdmin](https://tailadmin.com/) (UI Template), standard Laravel Blade templating.

## ✨ Key Features

- **Dashboard Overview**: At-a-glance view of task statistics and recent activity.
- **Task Management**: Full CRUD operations for tasks including status tracking (Pending, In Progress, Completed) and priority levels (Low, Medium, High).
- **Advanced Filtering**: Efficient task filtering by status, priority, and real-time search.
- **Profile Management**: Secure user settings to manage profile information and account passwords.
- **Modern UI**: A premium, responsive design implemented with Tailwind CSS 4.

## 🛠️ Setup Instructions

Follow these steps to get TaskNest running locally:

### 1. Prerequisites

Ensure you have the following installed:

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- A local database (MySQL/PostgreSQL/SQLite)

### 2. Installation

Clone the repository and install dependencies:

```bash
git clone https://github.com/jahid404/TaskNest.git
cd TaskNest
composer install
npm install
```

### 3. Configuration

Copy the environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

_Note: Update the `DB_\*`variables in your`.env` file with your local database credentials.

#### Quick Login (Optional)

For faster development and testing, you can enable "Quick Login" which adds shortcut buttons to the sign-in page from `.env` file:

```bash
QUICK_LOGIN=true
```

### 4. Database & Seeding

Run migrations and seed the database with initial data:

```bash
php artisan migrate --seed
```

This will create a default administrator account and sample tasks.

**Default Login:**

- **Email**: `admin@tasknest.com`
- **Password**: `123456789`

### 5. Start the Application

Run the development server and Vite:

```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite Assets
npm run dev
```

## 🧠 Design Decisions & Assumptions

- **Admin to Dashboard Pivot**: The application was refactored from a generic "Admin" template to a specific "Dashboard" centric UI. This involved renaming routes, controllers, and views to ensure a task-focused user experience rather than a broad administrative one.
- **Lean Frontend**: The interface relies on modern CSS and Alpine.js for interactive elements, resulting in significantly faster page loads.
- **Tailwind CSS 4**: I utilize the latest Tailwind CSS 4 engine via the Vite plugin for a more streamlined development workflow and better CSS performance.
- **Route Organization**: Business logic is separated into standard `web.php` and specialized `dashboard.php` route files for better maintainability as the project grows.

## 🧪 Testing Approach

TaskNest uses **Pest PHP** for its testing suite, focusing on expressive and readable implementation tests.

- **Feature Tests**: I prioritize testing core business logic, such as task creation, status updates, and filtering accuracy. This ensures that features like "completed_at" timestamps and "priority" levels are correctly persisted.
- **Data Integrity**: Tests include checks for "or" clause leaks in filtering logic to ensure users only see relevant records.

**Run tests using:**

```bash
php artisan test
# OR
./vendor/bin/pest
```
