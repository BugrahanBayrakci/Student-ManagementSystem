# Student-Management-System
PHP  MSSQL (SQL Server)  Basic CSS  XAMPP (for Apache + PHP)  SSMS (SQL Server Management Studio)



Student Information System – Setup Instructions 
1. Install XAMPP

Download XAMPP from the following address:
https://www.apachefriends.org/tr/index.html

2. Add Your Project Files

Place all your PHP files and other necessary files into:
C:\xampp\htdocs

3. Start Apache

Open XAMPP and click Start on the Apache module.

4. Run a PHP File in the Browser

Open your browser and go to:
http://localhost/connection.php

5. Important – SQL Server Login

You must use SQL Server Authentication to log in to SQL Server.

This video explains how to enable and use SQL Server Authentication:
https://www.youtube.com/watch?v=-UY0fHckkGc

Database Login Credentials

User ID: sa

Password: bjk232rt

6. Server Name Adjustment

In your PHP connection file:

$serverName = "DESKTOP-HLBI80J\SQLEXPRESS";


This part must be replaced with your own computer’s name.

Login Accounts for the Student Information System
Student Login

Username: ogrenci1

Password: sifre123

Instructor Login

Username: ogretim1

Password: sifre789

Administrator Login

Username: yonetici1

Password: admin123
