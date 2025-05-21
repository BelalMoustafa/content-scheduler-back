# Platforms Module

A Laravel module for managing social media platforms and user-platform associations.

## Features

- Platform management (CRUD operations)
- User-platform associations
- Platform status toggling
- Platform analytics
- Role-based access control
- API endpoints for both users and admins

## Installation

1. Register the module in `config/modules.php`:
```php
'Platforms' => [
    'enabled' => true,
    'providers' => [
        Modules\Platforms\Providers\PlatformsServiceProvider::class,
    ],
],
```

2. Run migrations:
```bash
php artisan migrate
```

3. Seed the platforms:
```bash
php artisan db:seed --class=Modules\\Platforms\\Database\\Seeders\\PlatformSeeder
```

## Database Structure

### Platforms Table
- `id`: Auto-incrementing primary key
- `name`: Platform name (e.g., "Twitter", "Instagram")
- `type`: Unique platform type identifier
- `created_at`: Timestamp
- `updated_at`: Timestamp

### User Platform Table (Pivot)
- `id`: Auto-incrementing primary key
- `user_id`: Foreign key to users table
- `platform_id`: Foreign key to platforms table
- `is_active`: Boolean indicating if platform is active for user
- `created_at`: Timestamp
- `updated_at`: Timestamp

## API Endpoints

### User Endpoints
All user endpoints require authentication (`auth:sanctum`).

```http
GET /api/platforms
```
Get all platforms with their status for the authenticated user.

```http
POST /api/platforms/toggle
```
Toggle platform status for the authenticated user.
```json
{
    "platform_id": 1,
    "is_active": true
}
```

### Admin Endpoints
All admin endpoints require authentication and admin role (`auth:sanctum` and `role:admin`).

```http
GET /api/admin/platforms
```
Get all platforms with their users.

```http
GET /api/admin/platforms/{id}
```
Get detailed platform information.

```http
POST /api/admin/platforms
```
Create a new platform.
```json
{
    "name": "New Platform",
    "type": "new_platform"
}
```

```http
PUT /api/admin/platforms/{id}
```
Update platform details.
```json
{
    "name": "Updated Platform",
    "type": "updated_platform"
}
```

```http
DELETE /api/admin/platforms/{id}
```
Delete a platform.

```http
POST /api/admin/platforms/toggle-user
```
Toggle platform status for a specific user.
```json
{
    "user_id": 1,
    "platform_id": 1,
    "is_active": true
}
```

```http
GET /api/admin/platforms/analytics
```
Get platform usage analytics.

## Usage Examples

### Service Layer
```php
// Get all platforms
$platforms = $platformService->getAllPlatforms();

// Get platform with users
$platform = $platformService->getPlatformWithUsers(1);

// Create platform
$platform = $platformService->createPlatform('Twitter', 'twitter');

// Update platform
$platform = $platformService->updatePlatform(1, 'Twitter', 'twitter');

// Delete platform
$platformService->deletePlatform(1);

// Toggle platform status
$platform = $platformService->togglePlatform($user, 1, true);

// Get analytics
$analytics = $platformService->getPlatformAnalytics();
```

### Repository Layer
```php
// Get all platforms
$platforms = $platformRepository->all();

// Find platform
$platform = $platformRepository->find(1);
$platform = $platformRepository->findByType('twitter');

// Create platform
$platform = $platformRepository->create([
    'name' => 'Twitter',
    'type' => 'twitter'
]);

// Update platform
$platform = $platformRepository->update(1, [
    'name' => 'Twitter',
    'type' => 'twitter'
]);

// Delete platform
$platformRepository->delete(1);
```

## Error Handling

The module includes comprehensive error handling:
- Platform not found exceptions
- Validation errors
- Authorization errors
- Database operation errors

All errors are logged and returned with appropriate HTTP status codes.

## Logging

The module logs important operations:
- Platform creation
- Platform updates
- Platform deletion
- Platform status changes
- Error events

## Security

- All endpoints are protected by authentication
- Admin endpoints require admin role
- Input validation for all requests
- CSRF protection
- Rate limiting

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This module is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). 
