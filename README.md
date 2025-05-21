# Social Media Post Scheduler Backend

A modular Laravel-based backend system for scheduling and publishing social media posts across multiple platforms.

## Project Overview

This backend system provides a robust API for managing social media posts, user authentication, and platform integrations. Built with Laravel's modular architecture, it offers a scalable and maintainable solution for social media management.

## System Requirements

- PHP 8.1 or higher
- Laravel 10.x
- MySQL 8.0 or higher
- Composer
- Node.js & NPM

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd backend
```

2. Install PHP dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations and seeders:
```bash
php artisan migrate
php artisan db:seed --class=Modules\\Auth\\Database\\Seeders\\RoleSeeder
php artisan db:seed --class=Modules\\Platforms\\Database\\Seeders\\PlatformSeeder
```

7. Start the development server:
```bash
php artisan serve
```

8. Start the scheduler (required for automated post publishing):
```bash
php artisan schedule:work
```

## Project Structure

```
backend/
├── app/
│   └── Http/
│       └── Controllers/
├── Modules/
│   ├── Auth/
│   │   ├── app/
│   │   │   ├── Http/
│   │   │   ├── Models/
│   │   │   ├── Repositories/
│   │   │   └── Services/
│   │   ├── database/
│   │   └── routes/
│   ├── Platforms/
│   │   ├── app/
│   │   │   ├── Http/
│   │   │   ├── Models/
│   │   │   ├── Repositories/
│   │   │   └── Services/
│   │   ├── database/
│   │   └── routes/
│   └── Posts/
│       ├── app/
│       │   ├── Console/
│       │   │   └── Commands/
│       │   ├── Http/
│       │   ├── Jobs/
│       │   ├── Models/
│       │   ├── Providers/
│       │   ├── Repositories/
│       │   └── Services/
│       ├── database/
│       └── routes/
├── config/
├── database/
├── routes/
└── tests/
```

## Modules

### Auth Module
- User authentication and authorization
- Role and permission management
- User profile management

### Platforms Module
- Platform management (Twitter, LinkedIn, Instagram, Facebook)
- Platform-specific configurations
- User-platform associations

### Posts Module
- Post creation and management
- Post scheduling
- Platform publishing
- Post status tracking
- Automated post publishing system

## Automated Post Publishing

The system includes an automated publishing system that runs every minute to process scheduled posts:

### Components

1. **Scheduled Job**
   - Runs every minute via Laravel scheduler
   - Processes all due posts
   - Handles platform-specific publishing
   - Includes error handling and logging

2. **Console Command**
   - Manual execution: `php artisan posts:publish`
   - Useful for testing and manual intervention

### Features

- Automatic processing of scheduled posts
- Platform-specific validation
- Detailed logging and monitoring
- Error handling and retry mechanisms
- Status tracking per platform

### Setup

1. Ensure the Laravel scheduler is running:
```bash
php artisan schedule:work
```

2. For production, add to crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## API Documentation

### Authentication

#### Register
```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password"
}
```

### Platforms

#### List Platforms
```http
GET /api/platforms
Authorization: Bearer {token}
```

#### Toggle Platform
```http
POST /api/platforms/toggle
Authorization: Bearer {token}
Content-Type: application/json

{
    "platform_id": 1,
    "is_active": true
}
```

### Posts

#### Create Post
```http
POST /api/posts
Authorization: Bearer {token}
Content-Type: application/json

{
    "content": "Your post content here",
    "platforms": [1, 2],
    "scheduled_time": "2024-03-20 15:00:00",
    "media_urls": [
        "https://example.com/image1.jpg"
    ]
}
```

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
composer run lint
composer run format
```

### Adding New Modules
1. Create module structure:
```bash
php artisan module:make ModuleName
```

2. Register module in `config/modules.php`
3. Implement module features
4. Add module routes
5. Write tests

## Support

For support, email belalmoustafa65@gmail.com or create an issue in the repository.
