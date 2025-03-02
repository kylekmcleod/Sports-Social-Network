# COSC 360 Group Project

## Project Overview
Our web application will be a sports orientated personal blogging platform. It will provide a space for fans to share their opinions on teams and players, and allow general discussions regarding sports.

## Table of Contents
1. [Team Members](#team-members)
2. [Project Overview](#project-overview)
3. [Folder Structure](#folder-structure)
      
## Team Members
- Kyle McLeod
- Harper Kerstens
- Matin Raoufi

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
│   ├── homepage.html            # Homepage
│   ├── register.html            # Register page
│   ├── login.html               # Login page
│   ├── post.html                # Post page
│   ├── settings.html            # Settings page
│   ├── profile.html             # Profile page
│   ├── 404.html                 # Error 404 page
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
│   └── config.php               # Database config
│ 
└── README.md                    # Project readme file
```
