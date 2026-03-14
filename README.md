<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Laravel and React Project

🍽️ Canteen Management System
A full-stack web application for managing school/university canteen operations, built with Laravel 12 (backend API) and React 18 (frontend SPA). The system features role-based access for Admins, Cashiers, and Customers, real-time order processing, inventory tracking, and sales reporting.

### ✨ Features

## 👤 Authentication & Role Management
- Three user roles: Admin, Cashier, Customer
- Secure login with Laravel Sanctum (Bearer tokens)
- Role-based route protection
- Session management and logout functionality

## 📋 Menu Management
- CRUD operations for menu items
- Category management (Meals, Snacks, Beverages, Desserts, Combos)
- Real-time availability toggling
- Stock tracking with low stock alerts
- Image upload support

## 🛒 Order Processing
- Point-of-Sale (POS) interface for cashiers
- Real-time order queue with status updates
- Order status flow: Pending → Preparing → Ready → Completed
- Automatic inventory deduction upon order confirmation
- Customer order history tracking

## 📦 Inventory Management
- Real-time stock level tracking
- Low-stock warnings with visual indicators
- Inventory logs showing stock changes
- Bulk restock functionality

## 📊 Sales Dashboard & Reports
- Daily, weekly, and monthly sales reports
- Best-selling items analysis
- Category-wise sales breakdown
- Interactive charts using Recharts
- Date range filtering
- Export reports to CSV


## 🛠️ Technology Stack

### Frontend (React)

| Technology | Version | Purpose |
|------------|---------|---------|
| React | 18.2.0 | UI library with functional components and hooks |
| React DOM | 18.2.0 | DOM rendering |
| Vite | 4.x | Build tool and development server |
| React Router DOM | 6.20.1 | Navigation and routing |
| Axios | 1.6.2 | HTTP client for API requests |
| Tailwind CSS | 3.3.6 | Utility-first CSS framework |
| Heroicons | 2.0.18 | SVG icon library |
| React Hot Toast | 2.4.1 | Toast notifications |
| Recharts | 2.10.3 | Data visualization charts |
| date-fns | 2.30.0 | Date formatting |

### Backend (Laravel)

| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 12.54.1 | PHP framework for RESTful API |
| Laravel Sanctum | 4.3.1 | API authentication |
| MySQL | 8.0+ | Database |
| PHP | 8.4.12 | Programming language |


## 💻 System Requirements

### Backend Requirements
| Requirement | Minimum Version |
|-------------|-----------------|
| PHP | 8.1+ |
| Composer | Latest |
| MySQL | 5.7+ |
| PostgreSQL | 10.0+ |
| Laravel | 12.x |
| Node.js | 16+ |

### Frontend Requirements
| Requirement | Minimum Version |
|-------------|-----------------|
| Node.js | 16+ |
| npm | 8+ |
| yarn | 1.22+ |
| React | 18.2.0 |
| Vite | 4.x |

### Check Command

```bash
php --version
composer --version
node --version
npm --version
mysql --version
git --version
````
## 📦Step-by-Step Installation

### 🖥️ Backend Setup (Laravel 12 API)

```bash

# Create a project directory
mkdir canteen-system
cd canteen-system

# Clone the backend repository
git clone <your-repository-url> canteen-backend
cd canteen-backend

```
## 🖥️Install PHP Dependencies
```
composer install
```
## Configure Environment
```
# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate
```

## Database Configuration

```
# Using Laragon (Windows)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=canteen_db
DB_USERNAME=root
DB_PASSWORD=

# Using XAMPP
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=canteen_db
DB_USERNAME=root
DB_PASSWORD=

# Using MAMP
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=canteen_db
DB_USERNAME=root
DB_PASSWORD=root

```
## Create Database
```
# Connect to MySQL
mysql -u root -p

# Create database (if not exists)
CREATE DATABASE canteen_db;
EXIT;
```

## Run Migrations and Seeders
```
# Run all migrations
php artisan migrate

# Run seeders to populate data
php artisan db:seed
```

## Install and Configure Sanctum
```
# Install Sanctum
composer require laravel/sanctum

# Publish Sanctum migrations
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Run Sanctum migrations
php artisan migrate
```
## Configure CORS Edit: config/cors.php

```
<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:5173',  // Vite default
        'http://localhost:5185',   // Your React app
        'http://127.0.0.1:5185',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

## Configure Middleware Edit: bootstrap/app.php
```
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```
## Test Backend Installation
```
# Start the Laravel development server
php artisan serve

# In another terminal, test the API
curl http://localhost:8000/api/test-auth
# Should return 401 Unauthorized (this is good - means API is working)
```

## 🎨 Frontend Setup (React + Vite)

## Navigate to Frontend Directory 
```
# From the canteen-system folder
cd canteen-frontend
```
## Install NPM Dependencies
```
npm install
```
## This installs all dependencies from package.json:

```
{
  "dependencies": {
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "react-router-dom": "^6.20.1",
    "axios": "^1.6.2",
    "@heroicons/react": "^2.0.18",
    "react-hot-toast": "^2.4.1",
    "date-fns": "^2.30.0",
    "recharts": "^2.10.3"
  },
  "devDependencies": {
    "@tailwindcss/forms": "^0.5.7",
    "autoprefixer": "^10.4.16",
    "postcss": "^8.4.32",
    "tailwindcss": "^3.3.6",
    "vite": "^4.5.0"
  }
}
```
## Configure Environment
```
cp .env.example .env
```
## Edit: .env
```
VITE_API_URL=http://localhost:8000/api
VITE_APP_NAME="Canteen Management System"
```

## Configure Tailwind CSS Create/update: tailwind.config.js
```
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

## Configure PostCSS Create/update: postcss.config.js
```
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
```
## Create API Service Create: src/Services/api.js
```
import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor to add token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

## 🚀 Running the Complete Application

### Terminal 1: Start Backend Server
```
cd canteen-backend
php artisan serve
```
### Terminal 2: Start Frontend Server
```
cd canteen-frontend
npm run dev
```

## Access the Application
- Frontend: http://localhost:5185
- Backend API: http://localhost:8000/api

## 🔑 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@canteen.com` | `password` |
| **Cashier 1** | `cashier1@canteen.com` | `password` |
| **Cashier 2** | `cashier2@canteen.com` | `password` |
| **Customer 1** | `customer1@canteen.com` | `password` |
| **Customer 2** | `customer2@canteen.com` | `password` |
| **Customer 3** | `customer3@canteen.com` | `password` |
| **Customer 4** | `customer4@canteen.com` | `password` |
| **Customer 5** | `customer5@canteen.com` | `password` |
## 🎉 Success!
## You should now have:

- ✅ Laravel 12 backend running on http://localhost:8000

- ✅ React + Vite frontend running on http://localhost:5185

- ✅ Database with seeded data

- ✅ All 40+ API endpoints working

- ✅ Role-based authentication

- ✅ Full POS functionality

## Next Steps:

- Login as different roles to test permissions

- Create orders as cashier

- View reports as admin

- Check order history as customer

- Customize menu items
## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
