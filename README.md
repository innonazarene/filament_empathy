# Clinic Health Monitoring System

A comprehensive web-based application designed to manage and monitor clinic operations efficiently. This system provides role-based access for different users, including Super Admins, Disbursement Officers, Budget Monitoring Officers, and Auditors.

## Features

- **Role-Based Access Control (RBAC):** Secure login and personalized dashboards based on user roles.
- **Student & Personnel Management:** Detailed profiles, including automatic BMI calculation and allergy tracking.
- **Medicine Logbook:** Track medicine inventory, dispensing, and reorder levels.
- **Treatment Records:** Maintain detailed logs of common illnesses and treatments provided.
- **Department Organization:** Manage records categorized by specific departments.
- **Offline Capabilities:** Designed to sync data and queue forms even when an internet connection is unavailable.
- **Two-Factor Authentication (2FA):** Enhanced security via email OTP during login.

## Tech Stack

- **Backend:** [Laravel](https://laravel.com/) (PHP Framework)
- **Frontend:** [Vue.js](https://vuejs.org/) with [Inertia.js](https://inertiajs.com/)
- **Database:** MySQL
- **Styling:** CSS
- **Local Environment:** Laragon

## Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   ```

2. **Navigate to the project directory:**
   ```bash
   cd filament_empathy
   ```

3. **Install PHP dependencies:**
   ```bash
   composer install
   ```

4. **Install Node.js dependencies:**
   ```bash
   npm install
   ```

5. **Set up your environment variables:**
   Copy the `.env.example` file to `.env` and configure your database and email SMTP settings.
   ```bash
   cp .env.example .env
   ```

6. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

7. **Run database migrations and seeders:**
   ```bash
   php artisan migrate --seed
   ```

8. **Build frontend assets:**
   ```bash
   npm run build
   ```

9. **Serve the application:**
   If using **Laragon**, ensure Laragon is running and visit `http://filament_empathy.test` in your browser.

## Themes
The system UI incorporates the official organization colors: **Navy Blue** and **Mustard Yellow**.
