# Code Challenge - Kacper Ogorkiewicz

## Project Overview
Welcome to the **Code Challenge**! This is a PHP-based application designed for managing event bookings. It features user authentication and a sleek admin dashboard where you can manage bookings, users, and more. As an admin, you can easily upload event bookings via JSON files, delete unwanted bookings, and dive into detailed event summaries.

## Requirements
Before you dive in, make sure you have the following set up:
- **XAMPP** (or any local server that supports PHP and MySQL)
- **PHP 7.0 or higher**
- **MySQL**

## Getting Started

### Step 1: Move the Project to XAMPP's `htdocs` Folder
1. **Download or clone** this repository to your local machine.
2. **Copy** the entire project folder named `code_challenge_kacper_ogorkiewicz` into your XAMPP's `htdocs` directory.

   You can usually find the `htdocs` folder here:
   - **Windows**: `C:/xampp/htdocs/`
   - **Mac/Linux**: `/Applications/XAMPP/htdocs/` or `/opt/lampp/htdocs/`

### Step 2: Set Up the Database
1. **Start** your XAMPP (or local server) services for **Apache** and **MySQL**.
2. Open your web browser and navigate to:
http://localhost:8080/code_challenge_kacper_ogorkiewicz/database/setup_database.php
This script will create the necessary database and tables needed for the project.

### Step 3: Access the Application
Once the database is ready, head over to:
http://localhost:8080/code_challenge_kacper_ogorkiewicz/index.php
This is where the magic happens—your home page for the application!

### Step 4: Test the Application
To test the login functionality:
1. Navigate to:
http://localhost:8080/code_challenge_kacper_ogorkiewicz/auth/login.php
2. Log in using the credentials set during the database setup.

## Admin Panel

### Accessing the Admin Dashboard
Only users with the role of **admin** can access the admin dashboard. Admins have the power to manage bookings, upload JSON files for batch uploads, delete bookings, and check out event statistics.

To access the admin dashboard, go to:
http://localhost:8080/code_challenge_kacper_ogorkiewicz/views/admin


### What Can Admins Do?
- **Manage Bookings**: Delete event bookings and view all booking details.
- **Upload Bookings (JSON)**: Easily upload JSON files containing multiple event bookings.
- **View Event Statistics**: Get a summary of all events, including total participation and fees collected.

## Troubleshooting
- Make sure your XAMPP server is running on port **8080**. If it’s running on a different port, adjust the URLs accordingly.
- Check that **mod_rewrite** is enabled in your Apache configuration to allow for URL routing.
- If you hit any snags, take a peek at your Apache and PHP logs for detailed error messages.

## License
This project is for educational purposes, and you’re free to modify or distribute it under the **MIT License**.

Happy coding!

