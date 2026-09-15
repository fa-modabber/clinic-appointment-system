# Clinic Appointment System


This is a Laravel-based backend API for a clinic appointment management system, primarily designed to support clinic staff in managing doctors’ schedules and patient appointments.

The system provides role-specific access for staff, doctors, and patients, allowing each user type to access the information and functionality relevant to their role such as their profiles and related appointments.

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

## ✨ Features

### Authentication

- Patient authentication using OTP
- Staff and doctor authentication using mobile number and password
- OTP verification
- Password reset using OTP
- Token-based authentication with Laravel Sanctum
- Logout

### Authorization

- Role-based access control
- Permission-based authorization
- Different roles for:
  - Patient
  - Doctor
  - Clinic Staff
 
### Clinic Staff

- Manage clinic, doctors, patients, specialties, schedules, schedule exceptions and appointments

### Patients

- View their profile, appointments and appointment details including doctor information

### Doctors

- View their profile, working schedule, appointments and patients’ records

---

## 📋 Requirements

Make sure the following are installed on your system:

- Docker
- Docker Compose

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/fa-modabber/clinic-appointment-system.git
```

### 2. Navigate to the project directory

```bash
cd clinic-appointment-system
```

### 3. Create the environment file

```bash
cp .env.example .env
```
Update some configuration in `.env`:

```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=clinic
DB_USERNAME=clinic
DB_PASSWORD=clinic
```

### 4. Build and start the containers

```bash
docker compose up -d --build
```

### 5. Generate the application key
```bash
docker compose exec app php artisan key:generate
```

### 5. Run database migrations and seeders

```bash
docker compose exec app php artisan migrate --seed
```

### 5. Access the application

The following diagram provides a visual overview of the application services and their corresponding access URLs.
```text
Browser
   │
   ├── :8000 → Laravel Container
   │
   └── :8080 → phpMyAdmin Container
                    │
                    ▼
               MySQL Container
```

The API will be available at:

```text
http://localhost:8000
```

phpMyAdmin will be available at:

```text
http://localhost:8080
```




### 6. Stop the containers

```bash
docker compose down
```

To remove the database volume as well:

```bash
docker compose down -v
```

## 📦 Postman Collection

The Postman collection is available in:

```text
/docs/postman/clinic-appointment-system.json
```
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

### Clinic Staff and Doctor Authentication

Staff members and doctors authenticate using their mobile number and password:

```text
Mobile Number + Password
          ↓
      Authentication
          ↓
   Authentication Token
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

- SMS provider integration
- Redis for caching and queues
- Appointment reminders
- Swagger / OpenAPI documentation
- CI/CD pipeline
- Production-ready Docker configuration
