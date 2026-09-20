# Dorm Access & Student Database System

A database-first web project for a university dormitory access system.

## Main modules
- Dashboard
- Students
- Access Logs
- Student History
- Security Reports
- Admins

## Technology
- PHP 8+
- MySQL 8+ / MariaDB
- Bootstrap 5.3
- HTML / CSS / JavaScript
- PDO with prepared statements

## Database
The SQL schema is in `sql/database.sql`.

## Local setup
1. Install XAMPP (or another PHP + MySQL environment).
2. Copy this project into `htdocs`.
3. Create a database named `dorm_access`.
4. Import `sql/database.sql` in phpMyAdmin.
5. Open:
   `http://localhost/dorm-access-system/public/`

Default demo admin:
- Username: `admin_main`
- Password: `admin123`

> Change the demo password before real deployment.

## Important
GitHub Pages can host the source/static files, but it cannot execute PHP or connect directly to MySQL. To run the complete database-connected system, use XAMPP, a PHP hosting server, or another server that supports PHP + MySQL.
