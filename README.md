# KTORZE M (14M) - Event Producer Website & Backoffice

This project is a modern web application built with **Laravel 12** and **Vue.js 3** (via Vite). It serves as both the public-facing website and the internal backoffice/CRM for the KTORZE M event production company.

## Prerequisites

To run this project, make sure you have the following installed on your system:

- [Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/) (Recommended approach)
- Alternately, for local development without Docker:
  - [PHP 8.2+](https://www.php.net/)
  - [Composer](https://getcomposer.org/)
  - [Node.js & npm](https://nodejs.org/)
  - MySQL or SQLite (Default is SQLite)

---

## 🐳 Running with Docker (Recommended)

The project includes a `docker-compose.yml` file to quickly spin up the environment (PHP application, MySQL, and Nginx servers).

1. **Start the Docker containers:**
   ```bash
   docker compose up -d
   ```
   *(This spins up three containers: `backend` for the PHP app, `mysql` for the database, and `nginx` for the web server).*

2. **Access the PHP application container terminal:**
   ```bash
   docker exec -it php bash
   ```
   *(Run all following sub-steps inside this container terminal)*

3. **Install dependencies and setup the project:**
   Within the container, use the predefined Composer script to automatically copy `.env`, generate your app key, run the database migrations, and build your frontend assets:
   ```bash
   composer setup
   ```

4. **Access the application:**
   - The application is mapped to port `8000` (from PHP container) and port `80` (from Nginx container).
   - Open your browser and navigate to: [http://localhost](http://localhost).

---

## 🛠️ Typical Development Commands

- **Run frontend assets in watch mode:** `npm run dev`
- **Build frontend assets for production:** `npm run build`
- **Run Artisan server only:** `php artisan serve`
- **Run DB migrations and seeders:** `php artisan migrate --seed`
- **Run DB seeders only:** `php artisan db:seed`
- **Clear Application Cache:** `php artisan optimize:clear`
- **Run Tests:** `composer test` or `php artisan test`

---

## 📂 Project Structure Overview

- **`app/`**: Contains the Laravel backend code (Controllers, Models, Mailables, etc.).
- **`resources/js/`**: Contains the Vue.js frontend code (Inertia Pages, Components, Views).
- **`routes/`**: Contains the API and web routing configurations.
- **`public/`**: Contains the compiled assets and the main `index.php` entry point.
- **`database/`**: Contains database migrations, factories, and seeders.
