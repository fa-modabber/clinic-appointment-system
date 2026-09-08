- Project overview
- Features
- Tech stack
- Architecture
- Database structure
- Authentication
- API documentation
- Installation
- Environment setup
- Screenshots
- Testing


# Clinic Appointment System

A Laravel-based backend API for managing doctor appointments in a clinic.

## 📸 Screenshots

### Home

![Home](screenshots/home.png)

### Doctors

![Doctors](screenshots/doctors.png)

### Doctor Profile

![Doctor Profile](screenshots/doctor-profile.png)

### Appointments

![Appointments](screenshots/appointments.png)

> Screenshots will be added here.

---

## 📌 About The Project

**Clinic Appointment System** is a backend API for a clinic appointment management system built with Laravel.

The system allows patients to browse doctors and specialties, view doctor information and available appointment slots, and book and manage their appointments.

The project also provides role-based access control for different types of users, including patients, doctors, secretaries, clinic managers, and administrators.

---

## ✨ Features

### Authentication

- Patient authentication using OTP
- Staff authentication using mobile number and password
- OTP verification
- Password reset using OTP
- Token-based authentication with Laravel Sanctum
- Logout

### Patients

- Browse medical specialties
- Browse doctors
- View doctor profiles
- View available appointment slots
- Book appointments
- View personal appointments
- Cancel appointments

### Doctors

- Manage doctor profile
- Manage specialties
- Manage working schedules
- View appointments

### Clinic Management

- Manage doctors
- Manage specialties
- Manage schedules
- Manage appointments

### Authorization

- Role-based access control
- Permission-based authorization
- Different roles for:
  - Patient
  - Doctor
  - Secretary
  - Clinic Manager
  - Super Admin

---

## 🛠 Tech Stack

- **PHP**
- **Laravel**
- **MySQL**
- **Laravel Sanctum**
- **Spatie Laravel Permission**
- **RESTful API**
- **Composer**
- **Git**
- **Docker & Docker Compose**

---

## 📋 Requirements

Make sure the following are installed on your system:

- PHP >= 8.x
- Composer
- MySQL
- Git
- Docker & Docker Compose *(optional)*

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/clinic-appointment-system.git
```

### 2. Navigate to the project directory

```bash
cd clinic-appointment-system
```

### 3. Install PHP dependencies

```bash
composer install
```

### 4. Create the environment file

```bash
cp .env.example .env
```

### 5. Generate the application key

```bash
php artisan key:generate
```

---

## ⚙️ Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Then configure the required environment variables in `.env`.

Example:

```env
APP_NAME="Clinic Appointment System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic
DB_USERNAME=root
DB_PASSWORD=
```

> Make sure the database credentials match your local MySQL configuration.

---

## 🗄️ Database Setup

Create a MySQL database:

```sql
CREATE DATABASE clinic;
```

Then update the database configuration in your `.env` file.

Run the database migrations:

```bash
php artisan migrate
```

---

## 🌱 Database Seeding

To run the database seeders:

```bash
php artisan db:seed
```

Or migrate and seed the database in one command:

```bash
php artisan migrate --seed
```

To completely reset the database and run all migrations and seeders again:

```bash
php artisan migrate:fresh --seed
```

---

## 🔐 Authentication

The API uses **Laravel Sanctum** for token-based authentication.

### Patient Authentication

Patients authenticate using OTP:

```text
Mobile Number
      ↓
Request OTP
      ↓
Verify OTP
      ↓
Authentication Token
      ↓
Authenticated Patient
```

### Staff Authentication

Staff members authenticate using their mobile number and password:

```text
Mobile Number + Password
          ↓
      Authentication
          ↓
   Authentication Token
```

---

## 📡 API Documentation

The project provides a RESTful API.

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/request-otp` | Request OTP |
| POST | `/api/auth/verify-otp` | Verify OTP |
| POST | `/api/auth/login` | Login |
| POST | `/api/auth/logout` | Logout |

### Specialties

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/specialties` | Get all specialties |
| GET | `/api/specialties/{id}` | Get specialty details |

### Doctors

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/doctors` | Get all doctors |
| GET | `/api/doctors/{id}` | Get doctor details |

### Appointments

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/appointments` | Get user appointments |
| POST | `/api/appointments` | Book an appointment |
| GET | `/api/appointments/{id}` | Get appointment details |
| DELETE | `/api/appointments/{id}` | Cancel an appointment |

> API endpoints may change as the project evolves.

### Postman Collection

The Postman collection is available in:

```text
/docs/postman/clinic-appointment-system.json
```

---

## 📁 Project Structure

The project follows Laravel's standard structure with business logic separated into dedicated layers.

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
│
├── Models/
├── Services/
├── Repositories/
├── Policies/
└── ...

database/
├── factories/
├── migrations/
└── seeders/

routes/
├── api.php
└── web.php

tests/
├── Feature/
└── Unit/
```

### Main Layers

```text
Controller
    ↓
Service
    ↓
Repository
    ↓
Eloquent / Database
```

- **Controllers** — Handle HTTP requests and responses.
- **Requests** — Handle request validation.
- **Services** — Contain business logic.
- **Repositories** — Handle data access logic.
- **Models** — Represent database entities.
- **Resources** — Transform models into API responses.
- **Policies** — Handle authorization rules.

---

## 🧪 Testing

Run the test suite using:

```bash
php artisan test
```

Or:

```bash
vendor/bin/phpunit
```

---

## 🐳 Docker

The project can also be run using Docker.

Build and start the containers:

```bash
docker compose up -d --build
```

Check running containers:

```bash
docker compose ps
```

Stop the containers:

```bash
docker compose down
```

---

## ▶️ Running the Application

For local development without Docker:

```bash
php artisan serve
```

The application will be available at:

```text
http://localhost:8000
```

---

## 🔒 Security

- Laravel Sanctum for API authentication
- Role-based access control
- Permission-based authorization
- Request validation
- Password hashing
- Protected API routes
- Authorization policies

---

## 🗺️ Future Improvements

- Online payment integration
- SMS provider integration
- Email notifications
- Redis for caching and queues
- Appointment reminders
- Improved doctor availability management
- Swagger / OpenAPI documentation
- CI/CD pipeline
- Production-ready Docker configuration

---

## 👩‍💻 Author

- GitHub: https://github.com/fa-modabber
- LinkedIn: https://linkedin.com/in/your-profile

---

## 📄 License

This project is licensed under the MIT License.
