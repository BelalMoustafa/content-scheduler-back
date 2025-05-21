# Auth Module

A secure and modular authentication system built with Laravel Sanctum, following service/repository architecture, SOLID principles, and PSR-12 standards.

## Features

- 🔐 Secure token-based authentication using Laravel Sanctum
- 📦 Modular architecture with clear separation of concerns
- 🛡️ Input validation using Form Requests
- 🚦 Rate limiting on login attempts
- 📝 Standardized API responses
- 🔄 Repository pattern for data access
- 🎯 Service layer for business logic
- 👥 Role-based access control (RBAC)
- 🔑 Permission management
- 📊 Role assignment and management

## API Endpoints

### Public Routes

#### Register
```http
POST /api/auth/register
```
**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login
```http
POST /api/auth/login
```
**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```
*Note: Rate limited to 5 attempts per minute*

### Protected Routes (Requires Authentication)

#### Logout
```http
POST /api/auth/logout
```
*Requires Bearer Token*

#### Get Profile
```http
GET /api/auth/me
```
*Requires Bearer Token*

#### Update Profile
```http
PUT /api/auth/profile
```
**Request Body:**
```json
{
    "name": "John Updated",
    "email": "john.updated@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```
*Requires Bearer Token*

### Role Management Routes (Admin Only)

#### List All Roles
```http
GET /api/auth/roles
```
*Requires Bearer Token and admin role*

#### Assign Roles
```http
POST /api/auth/roles/assign
```
**Request Body:**
```json
{
    "user_id": 1,
    "roles": ["admin", "editor"]
}
```
*Requires Bearer Token and admin role*

#### Remove Role
```http
DELETE /api/auth/roles/remove/{user}/{role}
```
*Requires Bearer Token and admin role*

#### List All Permissions
```http
GET /api/auth/roles/permissions
```
*Requires Bearer Token and admin role*

## Response Format

All API responses follow a standardized format:

```json
{
    "success": true,
    "message": "Operation successful message",
    "data": {
        // Response data
    },
    "errors": null
}
```

## Installation

1. Install the required package:
```bash
composer require spatie/laravel-permission
```

2. Publish the configuration:
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

3. Run migrations:
```bash
php artisan migrate
```

4. Seed the roles and permissions:
```bash
php artisan db:seed --class=Modules\\Auth\\Database\\Seeders\\RolesAndPermissionsSeeder
```

5. Ensure the module is registered in your Laravel application's `config/modules.php`:

```php
'modules' => [
    'Auth' => [
        'enabled' => true,
        'providers' => [
            Modules\Auth\Providers\RouteServiceProvider::class,
        ],
    ],
]
```

## Default Roles and Permissions

### Roles
- **admin**: Full system access
- **user**: Basic user access

### Permissions
- Post Management:
  - create-post
  - edit-post
  - delete-post
  - view-post
- User Management:
  - view-users
  - create-users
  - edit-users
  - delete-users
- Role Management:
  - view-roles
  - create-roles
  - edit-roles
  - delete-roles
- Platform Management:
  - manage-platforms
  - view-analytics

## Architecture

The module follows a clean architecture pattern:

### Directory Structure
```
Auth/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       └── RoleController.php
│   │   └── Requests/
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       ├── UpdateProfileRequest.php
│   │       └── AssignRoleRequest.php
│   ├── Models/
│   │   └── User.php
│   ├── Repositories/
│   │   ├── AuthRepository.php
│   │   └── AuthRepositoryInterface.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   └── PermissionService.php
│   └── Providers/
│       └── RouteServiceProvider.php
├── database/
│   └── seeders/
│       └── RolesAndPermissionsSeeder.php
├── routes/
│   └── api.php
└── README.md
```

### Components

- **Controllers**: Handle HTTP requests and responses
- **Services**: Contain business logic
- **Repositories**: Handle data access
- **Form Requests**: Validate input data
- **Models**: Define data structure and relationships

## Security Features

- Password hashing using Laravel's Hash facade
- Rate limiting on login attempts
- Sanctum token-based authentication
- Input validation and sanitization
- Protected routes with middleware
- Role-based access control
- Permission-based authorization
- Audit logging for role/permission changes

## Usage Example

```php
// Login and get token
$response = $http->post('/api/auth/login', [
    'email' => 'user@example.com',
    'password' => 'password123'
]);

$token = $response->json()['data']['token'];

// Use token for authenticated requests
$response = $http->withToken($token)
    ->get('/api/auth/me');

// Admin: Assign roles to user
$response = $http->withToken($adminToken)
    ->post('/api/auth/roles/assign', [
        'user_id' => 1,
        'roles' => ['editor']
    ]);
```

## Testing

Run the module's tests:
```bash
php artisan test --filter=Auth
```

## Contributing

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Update documentation as needed
4. Submit pull requests with clear descriptions

## License

This module is part of your Laravel application and follows its license terms. 
