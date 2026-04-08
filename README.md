# PCRS - Polytechnic Course Registration System
### Sistem Pendaftaran Kursus Politeknik

A web-based course registration system built with PHP, JavaScript, AJAX, MySQL, and Bootstrap 5. This system allows students to register and drop courses, while admins can manage the course list with a live AJAX search feature.

---

## Features

**Student**
- Register a new account and log in securely
- View and register available courses
- Drop (withdraw from) registered courses
- View personal course slip

**Admin**
- Secure admin dashboard
- Add and delete courses
- Live AJAX search — filter courses by code or name without page reload

---

## Getting Started

### Prerequisites
- XAMPP
- PHP 8.x

### Installation

**1. Clone or extract the project**
```
Place the MiniProject2_Group/ folder inside your web root:
- XAMPP  → C:\xampp\htdocs\
```

**2. Import the database**
- Open phpMyAdmin at `http://localhost/phpmyadmin`
- Create a new database named `pcrs_db`
- Click **Import** → select `pcrs_db.sql` → click **Go**

**3. Configure the database connection**

Open `config/db.php` and update the settings to match your environment:

```php
$host     = 'localhost';
$dbname   = 'pcrs_db';
$username = 'root';
$password = '';
```

**4. Run the project**

Open your browser and go to:
```
http://localhost/MiniProject2_Group/auth/login.php
```

---

## Group Members

| No. | Name | Student Matrix |
|---|---|---|
| 1 | MUHAMMAD RASYDAN BIN RIZALMAN | 17DIT24F1006 |
| 2 | MUQRISH NABIL BIN MASRULNIZAM | 17DIT24F1083 |
| 3 | MUHAMMAD AFIF BIN AZHAN | 17DIT24F1062 |

---

## License

This project was developed as a Mini Project assignment. For academic use only.
