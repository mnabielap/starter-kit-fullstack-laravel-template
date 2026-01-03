# 🚀 Starter Kit Fullstack Laravel Template

![Laravel](https://img.shields.io/badge/Laravel-10%2F11-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-%3E%3D8.2-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-00758F?style=flat-square&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat-square&logo=docker&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-563D7C?style=flat-square&logo=bootstrap&logoColor=white)
![Swagger](https://img.shields.io/badge/Swagger-OpenAPI-85EA2D?style=flat-square&logo=swagger&logoColor=black)

A powerful, production-ready **Fullstack Starter Kit** built with **Laravel**. This project demonstrates a robust "Hybrid" architecture: it serves both a **RESTful API** (JSON) and a **Classic Web Interface** (Blade Templates) using a single codebase.

It is designed for developers who want a solid foundation with **Best Practices** right out of the box, including **Sanctum Authentication**, **Swagger Documentation**, and **Automated API Testing**.

---

## ✨ Features

*   **🏗 Hybrid Architecture**:
    *   **API**: RESTful endpoints returning JSON (located in `routes/api.php`).
    *   **Web**: Server-side rendered Blade views (located in `routes/web.php`).
*   **🔐 Secure Authentication**:
    *   Powered by **Laravel Sanctum**.
    *   Supports both Token-based auth (for API clients) and Cookie-based auth (for Web UI).
    *   Dual-token system implemented (Access Token & Refresh Token).
*   **📄 API Documentation**: Auto-generated Swagger/OpenAPI documentation via `l5-swagger`.
*   **🎨 Frontend**: Lightweight setup using **Bootstrap 5** via CDN (No `npm`, `node_modules`, or build steps required).
*   **🔌 Database Agnostic**: Seamlessly switch between **MySQL** and **SQLite**.
*   **🐳 Docker Ready**: Custom `Dockerfile` and manual container orchestration for full control.
*   **🧪 Automated Testing**: Dedicated Python scripts to test API endpoints externally.

---

## 📂 Project Structure

```text
├── api_tests/            # Python scripts for external API Testing
├── app/
│   ├── Enums/            # PHP Enums (e.g., Role)
│   ├── Http/
│   │   ├── Controllers/  # Api & Web Controllers separated
│   │   ├── Middleware/   # Custom Middleware (e.g., ForceJsonResponse)
│   │   ├── Requests/     # FormRequests with Swagger Annotations
│   │   └── Resources/    # API Resources (JSON Transformers)
│   ├── Models/           # Eloquent Models
│   ├── Policies/         # Authorization Policies
│   └── Services/         # Business Logic Layer
├── config/               # App configuration (Sanctum, Swagger, DB)
├── database/             # Migrations & Seeders
├── public/               # Web Entry Point & Assets
│   ├── assets/           # Custom CSS & JS
│   └── storage/          # Symbolic link to storage/app/public
├── resources/
│   └── views/            # Blade Templates (Layouts, Pages, Partials)
├── routes/               # api.php & web.php
├── storage/              # Logs & File Uploads
├── .env.example          # Environment variables template
├── composer.json         # PHP Dependencies
├── Dockerfile            # Docker Configuration
└── README.md             # Documentation
```

---

## 🛠️ Getting Started (Local Development)

**Recommended:** We suggest running the project locally first to understand the structure before containerizing it.

### Prerequisites
*   PHP >= 8.2
*   Composer
*   MySQL (or use the built-in SQLite)

### 1. Installation

Clone the repository and install PHP dependencies:

```bash
git clone https://github.com/mnabielap/starter-kit-fullstack-laravel-template.git
cd starter-kit-fullstack-laravel-template
composer install
```

### 2. Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

Open `.env` and configure your database:
*   **For SQLite:** Set `DB_CONNECTION=sqlite` (and remove DB_HOST/PORT/USERNAME/PASSWORD).
*   **For MySQL:** Set `DB_CONNECTION=mysql` and fill in your credentials.

### 3. Database & Setup

Run the following commands to set up the key, database, and storage:

```bash
# Generate App Key
php artisan key:generate

# Run Migrations
php artisan migrate

# Create Admin User (Optional / via Tinker)
php artisan tinker
# Inside tinker run:
# \App\Models\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('Password123'),'role'=>'admin']);

# Link Storage
php artisan storage:link
```

### 4. Generate API Docs

Generate the Swagger documentation JSON:

```bash
php artisan l5-swagger:generate
```

### 5. Run the Application

Start the local development server:

```bash
php artisan serve
```

*   **Web Dashboard:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
*   **API Documentation:** [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)

---

## 🐳 Getting Started (Docker)

If you prefer using Docker, follow these steps to set up a persistent environment with a separate MySQL container manually.

### 1. Create Network
Create a shared network for the application and database to communicate.

```bash
docker network create fullstack_laravel_network
```

### 2. Create Volumes
Create volumes to ensure your Database data and Uploaded files persist even if containers are deleted.

```bash
docker volume create fullstack_laravel_db_volume
docker volume create fullstack_laravel_media_volume
```

### 3. Setup Environment for Docker
Create a specific `.env` file for Docker:

```bash
cp .env.example .env.docker
```

**Important:** Inside `.env.docker`, configure the database connection to point to the container name we will create (`fullstack-laravel-mysql`):

```ini
DB_CONNECTION=mysql
DB_HOST=fullstack-laravel-mysql
DB_PORT=3306
DB_DATABASE=starter_kit_laravel
DB_USERNAME=root
DB_PASSWORD=rootpassword
APP_URL=http://localhost:5005
```

### 4. Run MySQL Container
Start the MySQL database container attached to the network and volume.

```bash
docker run -d \
  --name fullstack-laravel-mysql \
  --network fullstack_laravel_network \
  -v fullstack_laravel_db_volume:/var/lib/mysql \
  -e MYSQL_ROOT_PASSWORD=rootpassword \
  -e MYSQL_DATABASE=starter_kit_laravel \
  mysql:8.0
```

### 5. Build App Image
Build the Docker image for the Laravel application.

```bash
docker build -t fullstack-laravel-app .
```

### 6. Run Application Container
Run the application on port **5005**. This command mounts the media volume, loads the env file, and connects to the network.

```bash
docker run -d -p 5005:5005 \
  --env-file .env.docker \
  --network fullstack_laravel_network \
  -v fullstack_laravel_media_volume:/var/www/html/storage/app/public \
  --name fullstack-laravel-container \
  fullstack-laravel-app
```

🚀 **Done!** Access your app at: **`http://localhost:5005`**

---

## 📦 Docker Management Cheat Sheet

Here are the essential commands to manage your containers manually.

#### 📜 View Logs
See what's happening inside your application (Access logs, Errors, etc.).
```bash
docker logs -f fullstack-laravel-container
```

#### 🛑 Stop Container
Safely stop the running application.
```bash
docker stop fullstack-laravel-container
```

#### ▶️ Start Container
Resume a stopped container.
```bash
docker start fullstack-laravel-container
```

#### 🗑 Remove Container
Remove the container (your data stays safe in the volumes).
```bash
docker stop fullstack-laravel-container
docker rm fullstack-laravel-container
```

#### 📂 View Volumes
List all persistent storage volumes.
```bash
docker volume ls
```

#### ⚠️ Remove Volume
**WARNING:** This deletes your database and uploaded files **permanently**.
```bash
docker volume rm fullstack_laravel_db_volume
docker volume rm fullstack_laravel_media_volume
```

---

## 🧪 API Testing (Python)

This project comes with a suite of **Python scripts** to test the API endpoints automatically. This serves as a lightweight replacement for Postman.

### Setup
1.  Navigate to the `api_tests` folder.
2.  Ensure you have Python 3 installed.
3.  The scripts use `utils.py` to manage configuration and tokens automatically (saved in `secrets.json`).

### Running Tests
Run the scripts simply by executing the file. **No arguments needed.**

**1. Authentication Flow (Group A):**
```bash
# Register a new random user
python A1.auth_register.py

# Login (Saves access & refresh tokens to secrets.json)
python A2.auth_login.py

# Refresh Token
python A3.auth_refresh.py

# Logout
python A6.auth_logout.py
```

**2. User Management (Group B):**
*Note: You must log in as an Admin (using A2) first.*

```bash
# Create a User
python B1.user_create.py

# Get All Users (with pagination)
python B2.user_get_all.py

# Get Specific User
python B3.user_get_one.py

# Update User
python B4.user_update.py

# Delete User
python B5.user_delete.py
```

---

## 📝 License

This project is open-source and available under the **MIT License**.