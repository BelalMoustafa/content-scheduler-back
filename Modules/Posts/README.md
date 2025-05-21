# Posts Module

A Laravel module for managing social media posts, scheduling, and publishing across multiple platforms.

## Overview

The Posts module provides a comprehensive solution for creating, scheduling, and publishing social media posts. It includes features for post management, platform-specific validation, and publishing status tracking.

## Module Structure

```
Posts/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── PublishScheduledPostsCommand.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── PostController.php
│   │   └── Requests/
│   │       ├── CreatePostRequest.php
│   │       └── UpdatePostRequest.php
│   ├── Jobs/
│   │   └── PublishScheduledPosts.php
│   ├── Models/
│   │   ├── Post.php
│   │   └── PostPlatform.php
│   ├── Providers/
│   │   └── PostsServiceProvider.php
│   ├── Repositories/
│   │   ├── PostRepository.php
│   │   └── PostRepositoryInterface.php
│   ├── Rules/
│   │   ├── DailyPostLimit.php
│   │   └── PlatformCharacterLimit.php
│   └── Services/
│       └── PostService.php
├── database/
│   ├── migrations/
│   │   ├── create_posts_table.php
│   │   └── create_post_platform_table.php
│   └── seeders/
├── routes/
│   └── api.php
└── tests/
```

## Features

- Post creation and management
- Multi-platform publishing
- Post scheduling
- Platform-specific character limits
- Daily post limits
- Post status tracking
- Media URL support
- Automated post publishing system

## Automated Publishing System

The module includes an automated system for publishing scheduled posts:

### Components

1. **PublishScheduledPosts Job**
   - Runs every minute via Laravel scheduler
   - Processes all due posts
   - Handles platform-specific publishing
   - Includes error handling and logging

2. **PublishScheduledPostsCommand**
   - Console command for manual execution
   - Command: `php artisan posts:publish`
   - Useful for testing and manual intervention

### Publishing Process

1. **Post Selection**
   - Finds posts where:
     - status = 'scheduled'
     - scheduled_time <= now()
     - Not fully published

2. **Platform Processing**
   - Processes each platform independently
   - Validates platform-specific requirements
   - Simulates publishing with random failures
   - Updates platform status (published/failed)

3. **Status Updates**
   - Updates post status based on platform results:
     - All successful → 'published'
     - Partial success → remains 'scheduled'
   - Records detailed status per platform

### Monitoring

- Detailed logging of all publishing attempts
- Platform-specific success/failure tracking
- Error messages for failed attempts
- Post status history

## API Endpoints

### List Posts
```http
GET /api/posts
Authorization: Bearer {token}
```

### Get Post
```http
GET /api/posts/{id}
Authorization: Bearer {token}
```

### Create Post
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

### Update Post
```http
PUT /api/posts/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "content": "Updated post content",
    "platforms": [1, 2, 3],
    "scheduled_time": "2024-03-21 15:00:00"
}
```

### Delete Post
```http
DELETE /api/posts/{id}
Authorization: Bearer {token}
```

### Publish Post
```http
POST /api/posts/{id}/publish
Authorization: Bearer {token}
```

## Validation Rules

### Platform Character Limits
- Twitter: 280 characters
- LinkedIn: 1,300 characters
- Instagram: Unlimited
- Facebook: 63,206 characters

### Daily Post Limits
- Maximum 10 scheduled posts per day per user

## Models

### Post
- `title`: string
- `content`: text
- `image_url`: string (nullable)
- `scheduled_time`: datetime (nullable)
- `status`: enum ('draft', 'scheduled', 'published')
- `user_id`: foreignId

### PostPlatform (Pivot)
- `post_id`: foreignId
- `platform_id`: foreignId
- `platform_status`: enum ('pending', 'published', 'failed')
- `error_message`: text (nullable)

## Development

### Running Tests
```bash
php artisan test --filter=Posts
```

### Manual Publishing
```bash
php artisan posts:publish
```

### Adding New Features
1. Create necessary migrations
2. Update models and relationships
3. Add repository methods
4. Implement service layer logic
5. Create/update controllers
6. Add validation rules
7. Write tests
``` 
