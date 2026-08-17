<img width="1901" height="913" alt="Screenshot 2026-08-17 073741" src="https://github.com/user-attachments/assets/189048f3-6233-4fb3-81df-23711fe0aba8" />
# JobVerse — Job Listing Platform

> A full-featured job board web application built with PHP, MySQL, and Bootstrap. Browse live job listings, apply in seconds, and manage everything through a dedicated admin panel.

---

## ✨ Features

### For Job Seekers
- 🔍 **Live Job Search** — Real-time search by title or company from the navbar
- 📋 **Job Listings** — Browse active jobs in a responsive card grid with location, type, and salary
- 📄 **Job Details** — View full description, required skills, experience level, and company info
- ⚡ **Quick Apply** — Apply directly with name, email, and WhatsApp number
- 👤 **User Accounts** — Register, log in, and manage your profile
- 🔄 **Auto-Refresh** — Refresh the job feed without a full page reload

### For Admins
- 🔐 **Secure Admin Panel** — Separate login for admin users
- 📝 **Job Management** — Create, edit, activate/deactivate, and delete job listings
- 📬 **Application Viewer** — View all applicants per job with contact details
- 📊 **Dashboard** — Overview of total jobs and applications

---

## 🛠️ Tech Stack

| Layer      | Technology                                   |
|------------|----------------------------------------------|
| Backend    | PHP 8+ (procedural + OOP with MySQLi)        |
| Database   | MySQL 5.7+ / MariaDB                         |
| Frontend   | HTML5, Vanilla CSS, JavaScript (ES6+)        |
| UI Library | MDB Bootstrap 5 (v7.3.2)                    |
| Icons      | Google Material Icons                        |
| Fonts      | Google Fonts — Inter                         |
| Server     | Apache via XAMPP                             |

---

## 📁 Project Structure

```
jobwebsite/
├── admin/
│   ├── index.php               # Admin login page
│   ├── dashboard.php           # Admin dashboard
│   ├── logout.php              # Admin logout
│   ├── includes/               # Admin-specific partials
│   ├── jobs/
│   │   ├── job-list.php        # Manage all jobs
│   │   ├── add-job.php         # Create a new job
│   │   ├── edit-job.php        # Edit existing job
│   │   └── delete-job.php      # Delete a job
│   └── applications/
│       └── applications.php    # View all applications
│
├── api/
│   ├── fetch-jobs.php          # JSON endpoint — returns active jobs
│   └── apply.php               # JSON endpoint — submit an application
│
├── assets/
│   ├── css/
│   │   └── style.css           # Global custom stylesheet
│   ├── js/                     # Custom JavaScript files
│   └── images/
│       └── logo.png            # Site logo
│
├── config/
│   └── database.php            # DB connection + auto-setup logic
│
├── includes/
│   ├── header.php              # Global HTML head, navbar, flash messages
│   ├── footer.php              # Global footer + scripts
│   ├── functions.php           # Helper functions (auth, flash, time-ago, etc.)
│   └── auth-check.php          # Session-based auth guard
│
├── index.php                   # Homepage — job listing grid
├── job-details.php             # Individual job detail page
├── login.php                   # User login
├── signup.php                  # User registration
├── logout.php                  # User logout
├── profile.php                 # User profile view
├── update-profile.php          # Handle profile update POST
├── setup.sql                   # Full DB schema + seed data
└── README.md
```

---

## ⚙️ Installation & Setup

### Prerequisites
- **XAMPP** (or any Apache + PHP 8+ + MySQL stack)
- A modern web browser

### Steps

1. **Clone / Copy the project**
   ```bash
   # Place the project inside your XAMPP htdocs folder
   C:\xampp\htdocs\jobwebsite\
   ```

2. **Start XAMPP services**
   - Open the XAMPP Control Panel
   - Start **Apache** and **MySQL**

3. **Open the site in your browser**
   ```
   http://localhost/jobwebsite/
   ```

   > 🚀 The database (`job_platform`) is **created automatically** on first visit — no manual SQL import needed. The config at `config/database.php` handles database creation, table setup, and seeding if the `jobs` table doesn't exist.

4. *(Optional)* **Manual database setup**  
   If you prefer to set up the database yourself, import `setup.sql` via phpMyAdmin:
   ```
   http://localhost/phpmyadmin
   ```
   Create a database named `job_platform` and import the SQL file.

---

## 🔑 Default Credentials

> ⚠️ Change these immediately in a production environment.

### Admin Panel
| Field    | Value          |
|----------|----------------|
| URL      | `http://localhost/jobwebsite/admin/` |
| Username | `YouBTech`     |
| Password | `admin123`     |

### Sample User Accounts
| Name       | Email                | Password      |
|------------|----------------------|---------------|
| Ahmed Khan | ahmed@example.com    | password123   |
| Sara Ali   | sara@example.com     | password123   |

---

## 🗄️ Database Schema

The `job_platform` database contains four tables:

| Table          | Description                                      |
|----------------|--------------------------------------------------|
| `users`        | Registered job seeker accounts                   |
| `admin_users`  | Admin panel credentials                          |
| `jobs`         | Job listings with type, salary, skills, etc.     |
| `applications` | Applications submitted by users or guests        |

### Job Types Supported
`Full-time` · `Part-time` · `Remote` · `Contract` · `Internship`

---

## 🔌 API Endpoints

| Method | Endpoint              | Description                          |
|--------|-----------------------|--------------------------------------|
| `GET`  | `/api/fetch-jobs.php` | Returns all active jobs as JSON      |
| `POST` | `/api/apply.php`      | Submits a job application            |

---

## 🔒 Security Notes

- All user input is sanitised with `htmlspecialchars()` before output
- Passwords are hashed using PHP's `password_hash()` (bcrypt)
- Database queries use **MySQLi prepared statements** to prevent SQL injection
- Session-based authentication guards all user and admin pages
- Admin and user sessions are kept separate

---

## 🚀 Deployment (Production)

When deploying to a live server:

1. Update database credentials in `config/database.php`:
   ```php
   define('DB_HOST', 'your_host');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_secure_password');
   define('DB_NAME', 'job_platform');
   ```
2. Change the default admin password from the admin panel or directly in the database.
3. Ensure PHP sessions are properly secured (`session.cookie_secure`, `session.cookie_httponly`).
4. Remove or restrict access to `setup.sql`.

---

## 📸 Pages Overview

| Page                | URL                          | Access        |
|---------------------|------------------------------|---------------|
| Home / Job Listings | `/jobwebsite/`               | Public        |
| Job Details         | `/jobwebsite/job-details.php?id=X` | Public  |
| Sign Up             | `/jobwebsite/signup.php`     | Public        |
| Login               | `/jobwebsite/login.php`      | Public        |
| User Profile        | `/jobwebsite/profile.php`    | Logged-in     |
| Admin Dashboard     | `/jobwebsite/admin/dashboard.php` | Admin    |
| Manage Jobs         | `/jobwebsite/admin/jobs/job-list.php` | Admin |
| Applications        | `/jobwebsite/admin/applications/applications.php` | Admin |

---

## 📄 License

This project is open-source and available for personal and educational use.

---

*Built with ❤️ using PHP, MySQL & Bootstrap*
