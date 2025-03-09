# COSC 360 Group Project

## Project Overview
Our web application will be a sports orientated personal blogging platform. It will provide a space for fans to share their opinions on teams and players, and allow general discussions regarding sports.

## Table of Contents
1. [Team Members](#team-members)
2. [Installation](#installation)
3. [Folder Structure](#folder-structure)
   
## Team Members
- Kyle McLeod
- Harper Kerstens
- Matin Raoufi

## Installation (Windows)
### 1. Prerequisites
- Install [XAMPP](https://www.apachefriends.org/) (Apache, MySQL, PHP).

### 2. Clone the project
Clone the repository into your preferred location.

### 3. Use a Symbolic Link
A symbolic link (symlink) allows you to keep your project files in another location (e.g., Documents/your_project) while still making them accessible to XAMPP inside htdocs.

- Locate the cloned folder location (e.g., C:\Users\YourName\Documents\your_project).
- Locate the htdocs folder in XAMPP (usually C:\xampp\htdocs)
- Open Command Prompt as Administrator:
- Press Win + S, type cmd, right-click Command Prompt, and select Run as administrator.
- Run the following command to create the symbolic link:
- ```mklink /D C:\xampp\htdocs\COSC360 C:\Users\YourName\Documents\your_project```
  
4. Run the project
- Open XAMPP Control Panel and start Apache and MySQL.
- Project should be running. For example, try ```http://localhost/COSC360/public/homepage.php```

### 4. Import the Database
1. Open phpMyAdmin by going to `http://localhost/phpmyadmin/` in your web browser.
2. Create a new database:
   - In phpMyAdmin, click on the **Databases** tab.
   - Enter `sports_db` as the name of the new database and click **Create**.
3. Import the SQL file:
   - After creating the database, select `sports_db` from the left panel.
   - Click on the **Import** tab on the top bar.
   - Click the **Choose File** button and select the `sports_db.sql` file from this repository (in configs folder).
   - Click **Import** to import the database schema.


## Folder Structure:
```
myapp/
│
├── assets/                      # For static assets like images, CSS, and JavaScript
│   ├── css/                     # Custom CSS files (new CSS file for each component)
│   ├── js/                      # Custom JavaScript files (can include AJAX calls here)
│   └── images/                  # Images (logos, icons, etc.)
│
├── public/                      # Public folder for all publicly accessible files
│   ├── homepage.php             # Homepage
│   ├── register.php             # Register page
│   ├── login.php                # Login page
│   ├── post.php                 # Post page
│   ├── settings.php             # Settings page
│   ├── profile.php              # Profile page
│   └── 404.php                  # Error 404 page
│
├── src/                         # Back-end logic
│   ├── controllers/             # PHP files for handling requests and logic
│   │   └── AuthController.php   # Handles user authentication logic (login, register, etc.)
│   │
│   ├── models/                  # Files for interacting with the database
│   │   └── User.php             # User model
│   │
│   └── utils/                   # Utility classes (helpers, functions)
│       └── Database.php         # Database connection
│
├── views/                       # View templates for rendering HTML (can be PHP or HTML)
│
├── .gitignore                   # Git ignore file
├── composer.json                # Composer file (for PHP dependencies management)
├── config/                      # Config files
│   ├── sports_db.sql            # Database SQL file
│   └── config.php               # Database config
│ 
└── README.md                    # Project readme file
```
