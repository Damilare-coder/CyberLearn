# CyberLearn - Cybersecurity Basics Learning Platform

A simple PHP web application to teach cybersecurity fundamentals with lessons, quizzes, user registration/login, profile image upload, and progress tracking.

## Technologies Used
- PHP 8+
- MariaDB/MySQL (via XAMPP)
- Bootstrap 5.3 
- PDO for secure database access

## How to Run the Project (for grading)

### Prerequisites
- XAMPP (with Apache + MySQL running)
- PHP 8+ (included in XAMPP)

### Setup Steps

1. Clone the repository**
      ```bash
      git clone https://github.com/yourusername/CyberLearn.git

2. Copy project to XAMPP htdocs
      Move or copy the CyberLearn folder to:
      Windows: C:\xampp\htdocs\CyberLearn
      macOS/Linux: /Applications/XAMPP/htdocs/CyberLearn or /opt/lampp/htdocs/CyberLearn

3. Start XAMPP
      Open XAMPP Control Panel
      Start Apache and MySQL

4. Create the database
      Open http://localhost/phpmyadmin
      Click New on the left
      Database name: cyber_db
      Collation: utf8mb4_general_ci
      Click Create

5. Import the database
      In phpMyAdmin, select cyber_db on the left
      Go to Import tab
      Click Choose File → select cyber_db.sql from the project folder
      Click Go / Import at the bottom

6. Access the app
      Open browser: http://localhost/CyberLearn/
      You should see the login page
      Default test user (if included in SQL dump):
      Username: testuser
      Password: password123



Username: root
Password: ' '

Folder Structure
textCyberLearn/
├── db_connect.php
├── login.php
├── register.php
├── dashboard.php
├── profile.php
├── lesson.php
├── quiz.php
├── results.php
├── logout.php
├── uploads/               ← profile images go here
├── cyber_db.sql           ← database dump (structure + sample data)
