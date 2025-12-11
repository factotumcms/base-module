# Factotum Base Module - Documentation

## Table of Contents

1. [Introduction](#introduction)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [Main Features](#main-features)
6. [Technical Architecture](#technical-architecture)
7. [API Reference](#api-reference)
8. [Models and Database](#models-and-database)
9. [Authorization and Security](#authorization-and-security)
10. [Extension and Customization](#extension-and-customization)

---

## Introduction

**Factotum Base Module** is a Laravel starter kit package that provides essential features to quickly start a complete web application. The module includes a robust authentication system, role and permission management, media management, notification system, and much more.

### What It Offers

- **Authentication System**: Login, registration, and password management with history
- **User Management**: Complete CRUD with soft delete and advanced query builder
- **Roles and Permissions**: Complete system based on Spatie Laravel Permission
- **Media Management**: Upload, storage, and automatic image conversions
- **Settings**: Global and per-user configuration system
- **Notifications**: Integrated notification management
- **Multi-language**: Translation support via Spatie Translation Loader
- **RESTful API**: Ready-to-use endpoints with Sanctum authentication

### Key Features

- Modular and easily extensible architecture
- DTO (Data Transfer Objects) pattern for validation and data transfer
- Custom Query Builders with filters and sorting
- Policies for granular authorization
- Service Layer for centralized business logic
- API Resources for consistent serialization

---

## Requirements

### Minimum Requirements

- **PHP**: ^8.4
- **Laravel**: ^12.0
- **Database**: MySQL 5.7+ / PostgreSQL 10+ / SQLite 3.8.8+

### Main Dependencies

The package automatically includes the following libraries:

- `laravel/sanctum`: ^4.2 - API authentication
- `spatie/laravel-permission`: ^6.21 - Role and permission system
- `spatie/laravel-data`: ^4.17 - Data Transfer Objects
- `spatie/laravel-query-builder`: ^6.3 - Advanced query builder
- `spatie/laravel-translation-loader`: ^2.8 - Translation management
- `spatie/image`: ^3.8 - Image manipulation
- `spatie/image-optimizer`: ^1.8 - Image optimization

---

## Installation

### 1. Laravel Installation

If you don't already have a Laravel project, create a new one:

```bash
composer create-project laravel/laravel project-name
cd project-name
```

### 2. Database Configuration

Configure the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Package Installation

Require the package via Composer:

```bash
composer require wave8/factotum-base
```

### 4. Configuration Publishing

Publish the configuration files:

```bash
# Publish configuration files
php artisan vendor:publish --tag=factotum-base-config

# Publish migrations (optional, if you want to customize them)
php artisan vendor:publish --tag=factotum-base-migrations
```

### 5. User Model Modification

In the `app/Models/User.php` model, extend the package's User class instead of Laravel's:

```php
<?php

namespace App\Models;

// Remove: use Illuminate\Foundation\Auth\User as Authenticatable;
// Add:
use Wave8\Factotum\Base\Models\User as FactotumUser;

class User extends FactotumUser
{
    // Your custom code here
}
```

### 6. Installation Command

Run the automatic installation command that:
- Publishes migrations
- Runs migrations
- Runs initial seeders
- Copies necessary files

```bash
php artisan factotum-base:install --migrate --seed
```

**Available Options:**
- `--migrate`: Runs migrations (fresh)
- `--seed`: Populates database with initial data
- `--force`: Overwrites existing files

### 7. Installation Verification

After installation, verify that everything is configured correctly:

```bash
php artisan route:list
```

You should see the package routes listed.

---

## Configuration

### Main Configuration File

The `config/factotum_base.php` file contains all package configurations:

#### Default Admin

```php
'admin_default' => [
    'email' => env('ADMIN_DEFAULT_EMAIL', 'admin@gmail.com'),
    'password' => env('ADMIN_DEFAULT_PASSWORD', 'password'),
    'username' => env('ADMIN_DEFAULT_USERNAME', 'admin'),
    'first_name' => env('ADMIN_DEFAULT_FIRST_NAME', 'Admin'),
    'last_name' => env('ADMIN_DEFAULT_LAST_NAME', 'Default'),
],
```

You can customize these values in the `.env` file:

```env
ADMIN_DEFAULT_EMAIL=admin@yourdomain.com
ADMIN_DEFAULT_PASSWORD=secure_password
ADMIN_DEFAULT_USERNAME=superadmin
```

#### Authentication Configuration

```php
'auth' => [
    'type' => 'basic', // Authentication type
    'basic_identifier' => 'email', // Login field: 'email' or 'username'
    'password_expiration_days' => env('PASSWORD_EXPIRATION_DAYS', 30),
    'password_prune_keep' => env('PASSWORD_PRUNE_KEEP', 5),
    'password_validate_latest' => env('PASSWORD_VALIDATE_LATEST', 5),
],
```

- **basic_identifier**: Defines whether login occurs via email or username
- **password_expiration_days**: Days before password expiration
- **password_prune_keep**: Number of historical passwords to keep
- **password_validate_latest**: Number of previous passwords to verify (prevents reuse)

#### Locale Configuration

```php
'locale' => [
    'default' => env('APP_LOCALE', 'en'),
],
```

#### Media Configuration

```php
'media' => [
    'disk' => env('FILESYSTEM_DISK', 'public'), // Storage disk
    'base_path' => env('MEDIA_BASE_PATH', 'media'), // Base path for media
    'conversions_path' => 'conversions', // Subfolder for conversions
    
    'presets' => [
        // User avatar preset
        'user-avatar' => [
            'suffix' => '_avatar',
            'actions' => [
                'fit' => [
                    'method' => 'crop',
                    'width' => 300,
                    'height' => 300,
                ],
                'crop' => [
                    'width' => 300,
                    'height' => 300,
                    'position' => 'center',
                ],
                'optimize' => [],
            ],
        ],
    ],
],
```

Presets define automatic transformations for images. You can add custom presets.

### Data Transfer File

The `config/data_transfer.php` file defines bindings for DTOs and Resources:

```php
return [
    // Dto Bindings
    LoginUserDto::class => LoginUserDto::class,
    RegisterUserDto::class => RegisterUserDto::class,
    CreateUserDto::class => CreateUserDto::class,
    // ...
    
    // Resource Bindings
    UserResource::class => UserResource::class,
    RoleResource::class => RoleResource::class,
    // ...
];
```

This allows extending/overriding DTOs and Resources in the main project.

---

## Main Features

### 1. Authentication System

#### Login

**Endpoint**: `POST /api/public/login`

**Request Body**:
```json
{
  "email": "admin@gmail.com",
  "password": "password"
}
```

**Response**:
```json
{
  "data": {
    "id": 1,
    "first_name": "Admin",
    "last_name": "Default",
    "email": "admin@gmail.com",
    "username": "admin",
    "is_active": true,
    "token": "1|abcdef123456..."
  }
}
```

The returned token must be included in authenticated requests as a Bearer token.

#### Registration

**Endpoint**: `POST /api/public/register`

**Request Body**:
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "username": "jdoe",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Password Management

The system includes:
- **Password History**: Tracks the last N passwords to prevent reuse
- **Password Expiration**: Notifies password expiration after N days
- **Change Password**: Endpoint for password change

**Endpoint**: `PATCH /api/protected/users/{id}/change-password`

### 2. User Management

#### User List with Filters

**Endpoint**: `GET /api/protected/users`

**Query Parameters**:
```
?filter[first_name]=John
&filter[email]=example.com
&filter[is_active]=1
&sort=-created_at
&page=1
```

The system supports dynamic filters on all fields configured in the QueryBuilder.

#### Create User

**Endpoint**: `POST /api/protected/users`

**Request Body**:
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "username": "jdoe",
  "password": "password123",
  "is_active": true,
  "roles": [1, 2]
}
```

#### Update User

**Endpoint**: `PUT /api/protected/users/{id}`

#### Delete User

**Endpoint**: `DELETE /api/protected/users/{id}`

Uses soft delete, so the user remains in the database but marked as deleted.

### 3. Roles and Permissions

The system is based on **Spatie Laravel Permission** and provides complete RBAC (Role-Based Access Control) management.

#### Role List

**Endpoint**: `GET /api/protected/roles`

#### Create Role

**Endpoint**: `POST /api/protected/roles`

**Request Body**:
```json
{
  "name": "editor",
  "permissions": [1, 2, 3, 5, 8]
}
```

#### Assign Roles to User

Roles can be assigned during user creation/modification via the `roles` array.

#### Permission List

**Endpoint**: `GET /api/protected/permissions`

Returns all permissions available in the system.

### 4. Media Management

#### File Upload

**Endpoint**: `POST /api/protected/media`

**Request**: Multipart form-data

```javascript
const formData = new FormData();
formData.append('file', fileInput.files[0]);
formData.append('presets', JSON.stringify(['user-avatar']));
formData.append('custom_properties', JSON.stringify({
  alt: 'Profile image',
  description: 'User avatar'
}));
```

**Response**:
```json
{
  "data": {
    "id": 1,
    "uuid": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
    "name": "profile-pic.jpg",
    "file_name": "a1b2c3d4e5f67890.jpg",
    "mime_type": "image/jpeg",
    "media_type": "image",
    "size": 245678,
    "disk": "public",
    "path": "media",
    "url": "http://localhost/storage/media/a1b2c3d4e5f67890.jpg",
    "conversions": {
      "user-avatar": {
        "url": "http://localhost/storage/media/conversions/a1b2c3d4e5f67890_avatar.jpg",
        "size": 45678
      }
    }
  }
}
```

#### Automatic Conversions

Images are automatically processed according to configured presets. The system:
- Resizes images
- Applies crop
- Optimizes for web
- Generates thumbnails

#### Media List

**Endpoint**: `GET /api/protected/media`

With support for filters and sorting.

### 5. Settings

The settings system allows managing global and per-user configurations.

#### Setting Structure

```php
[
  'key' => 'site_name',
  'value' => 'My Site',
  'group' => 'general',
  'data_type' => 'string', // string, integer, boolean, json
  'is_editable' => true,
  'description' => 'Website name'
]
```

#### Settings List

**Endpoint**: `GET /api/protected/settings`

#### Update Setting

**Endpoint**: `PUT /api/protected/settings/{id}`

#### User Settings

Users can have personalized settings.

**Endpoint**: `GET /api/protected/users/{id}/settings`
**Endpoint**: `PUT /api/protected/users/{id}/settings/{setting_id}`

### 6. Notifications

Integrated system for managing application notifications.

#### User Notifications List

**Endpoint**: `GET /api/protected/notifications`

#### Read Notification

**Endpoint**: `PATCH /api/protected/notifications/{id}/read`

#### Delete Notification

**Endpoint**: `DELETE /api/protected/notifications/{id}`

### 7. Multi-language

Complete support for dynamic translations via database.

**Endpoint**: `GET /api/public/language/{locale}`

Returns all translations for the specified language.

---

## Technical Architecture

### Architectural Patterns

The package follows **Clean Architecture** and **SOLID** principles:

#### 1. Service Layer

All business logic is centralized in Services:

```php
namespace Wave8\Factotum\Base\Services\Api;

class UserService implements UserServiceInterface
{
    public function create(CreateUserDto $data): User
    {
        // Creation logic
    }
    
    public function update(User $user, UpdateUserDto $data): User
    {
        // Update logic
    }
}
```

**Advantages**:
- Reusable logic
- Easy testing
- Separation of responsibilities

#### 2. Data Transfer Objects (DTO)

DTOs validate and transfer data between layers:

```php
namespace Wave8\Factotum\Base\Dtos\Api\User;

use Spatie\LaravelData\Data;

class CreateUserDto extends Data
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public ?string $username,
        public string $password,
        public bool $is_active = true,
        public ?array $roles = null,
    ) {}
}
```

**Advantages**:
- Type-safe validation
- Auto-casting
- Implicit documentation

#### 3. Repository Pattern (Builders)

Custom Query Builders encapsulate complex queries:

```php
class UserQueryBuilder extends Builder
{
    public $filterable = [
        'first_name' => FilterType::LIKE,
        'last_name' => FilterType::LIKE,
        'email' => FilterType::LIKE,
        'is_active' => FilterType::EXACT,
    ];

    public $sortable = ['id', 'first_name', 'email'];

    public function filterByRequest()
    {
        // Builds query with dynamic filters
    }
}
```

#### 4. Resource Pattern

API Resources serialize models:

```php
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
        ];
    }
}
```

### Folder Structure

```
src/
├── Builders/           # Custom query builders
├── Console/            # Artisan commands
│   └── Commands/
├── Contracts/          # Interfaces
├── Dtos/              # Data Transfer Objects
├── Enums/             # Enumerations
├── Exceptions/        # Custom exceptions
├── Http/
│   ├── Controllers/   # API Controllers
│   └── Requests/      # Form Requests (deprecated, use DTO)
├── Jobs/              # Queue Jobs
├── Models/            # Eloquent Models
├── Policies/          # Authorization Policies
├── Providers/         # Service Providers
├── Resources/         # API Resources
├── Rules/             # Validation Rules
├── Services/          # Business Logic
└── Traits/            # Reusable traits
```

### Dependency Injection

All services are registered in the Service Provider and can be injected:

```php
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;

class MyController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {}
    
    public function index()
    {
        return $this->userService->getAll();
    }
}
```

### Event-Driven Architecture

The package uses events and observers for automatic reactions:

- **Job**: `GenerateImageConversions` - Generates image conversions
- **Command**: `PrunePasswordHistories` - Cleans old password histories

---

## API Reference

### Response Structure

All API responses follow this format:

**Success Response**:
```json
{
  "data": {
    // Requested data
  },
  "meta": {
    "current_page": 1,
    "total": 100,
    "per_page": 15
  }
}
```

**Error Response**:
```json
{
  "message": "Error message",
  "errors": {
    "field_name": ["Validation error"]
  }
}
```

### Authentication

All protected routes require Sanctum authentication:

```http
Authorization: Bearer {token}
```

### Public Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/public/login` | User login |
| POST | `/api/public/register` | User registration |
| GET | `/api/public/language/{locale}` | Locale translations |

### Protected Routes - Users

| Method | Endpoint | Permission | Description |
|--------|----------|----------|-------------|
| GET | `/api/protected/users` | `filter users` | User list with filters |
| GET | `/api/protected/users/{id}` | `read user` | User details |
| POST | `/api/protected/users` | `create users` | Create user |
| PUT | `/api/protected/users/{id}` | `update user` | Update user |
| DELETE | `/api/protected/users/{id}` | `delete user` | Delete user |
| PATCH | `/api/protected/users/{id}/change-password` | `changePassword user` | Change password |
| GET | `/api/protected/users/{id}/settings` | `read settings` | User settings |
| PUT | `/api/protected/users/{id}/settings/{setting_id}` | `updateUserSetting setting` | Update user setting |

### Protected Routes - Roles

| Method | Endpoint | Permission | Description |
|--------|----------|----------|-------------|
| GET | `/api/protected/roles` | `read roles` | Role list |
| GET | `/api/protected/roles/{id}` | `read role` | Role details |
| POST | `/api/protected/roles` | `create roles` | Create role |
| PUT | `/api/protected/roles/{id}` | `update role` | Update role |
| DELETE | `/api/protected/roles/{id}` | `delete role` | Delete role |

### Protected Routes - Permissions

| Method | Endpoint | Permission | Description |
|--------|----------|----------|-------------|
| GET | `/api/protected/permissions` | `read permissions` | Permission list |

### Protected Routes - Media

| Method | Endpoint | Permission | Description |
|--------|----------|----------|-------------|
| GET | `/api/protected/media` | `read media` | Media list |
| GET | `/api/protected/media/{id}` | `read media` | Media details |
| POST | `/api/protected/media` | `upload media` | File upload |

### Protected Routes - Settings

| Method | Endpoint | Permission | Description |
|--------|----------|----------|-------------|
| GET | `/api/protected/settings` | `read settings` | Settings list |
| GET | `/api/protected/settings/{id}` | `read setting` | Setting details |
| PUT | `/api/protected/settings/{id}` | `update setting` | Update setting |

### Protected Routes - Notifications

| Method | Endpoint | Permission | Description |
|--------|----------|----------|-------------|
| GET | `/api/protected/notifications` | - | User notifications list |
| PATCH | `/api/protected/notifications/{id}/read` | - | Mark as read |
| DELETE | `/api/protected/notifications/{id}` | - | Delete notification |

### Query Filters

Lists support dynamic filters:

```
GET /api/protected/users?filter[email]=@gmail.com&filter[is_active]=1
```

**Filter Types**:
- **EXACT**: Exact match (`filter[is_active]=1`)
- **LIKE**: Partial match (`filter[first_name]=John`)
- **DYNAMIC**: Advanced operators (`filter[created_at][gte]=2024-01-01`)

### Sorting

```
GET /api/protected/users?sort=first_name          # ASC
GET /api/protected/users?sort=-created_at         # DESC
GET /api/protected/users?sort=first_name,-id      # Multiple
```

### Pagination

```
GET /api/protected/users?page=1&per_page=20
```

Default: 15 items per page.

---

## Models and Database

### User Model

**Table**: `users`

**Fields**:
- `id`: Primary key
- `first_name`: First name
- `last_name`: Last name
- `username`: Username (nullable, unique)
- `email`: Email (unique)
- `email_verified_at`: Email verification date
- `password`: Password hash
- `is_active`: Active/inactive flag
- `last_login_at`: Last login
- `avatar_id`: FK to media
- `remember_token`: Remember token
- `deleted_at`: Soft delete timestamp

**Relations**:
- `avatar()`: BelongsTo Media
- `roles()`: BelongsToMany Role
- `permissions()`: BelongsToMany Permission
- `settings()`: BelongsToMany Setting
- `passwordHistories()`: HasMany PasswordHistory

**Traits**:
- `HasApiTokens`: Sanctum tokens
- `HasRoles`: Spatie roles
- `HasPermissions`: Spatie permissions
- `Notifiable`: Laravel notifications
- `SoftDeletes`: Soft delete

### Role Model

**Table**: `roles`

Extends `Spatie\Permission\Models\Role`.

**Fields**:
- `id`: Primary key
- `name`: Role name (unique)
- `guard_name`: Guard (default: 'web')

**Relations**:
- `permissions()`: BelongsToMany Permission
- `users()`: BelongsToMany User

### Permission Model

**Table**: `permissions`

Extends `Spatie\Permission\Models\Permission`.

**Fields**:
- `id`: Primary key
- `name`: Permission name (unique)
- `guard_name`: Guard

### Setting Model

**Table**: `settings`

**Fields**:
- `id`: Primary key
- `key`: Setting key (unique in group)
- `value`: Value (text)
- `group`: Group belonging
- `data_type`: Data type (enum)
- `is_editable`: If editable
- `description`: Description

**Enums**:
- `SettingDataType`: STRING, INTEGER, BOOLEAN, JSON

**Relations**:
- `users()`: BelongsToMany User (for personalized settings)

### Media Model

**Table**: `media`

**Fields**:
- `id`: Primary key
- `uuid`: Unique UUID
- `name`: Original file name
- `file_name`: Saved file name
- `mime_type`: MIME type
- `media_type`: Media type (image, video, document)
- `presets`: Applied presets array
- `disk`: Storage disk
- `path`: Relative path
- `size`: Size in bytes
- `custom_properties`: Custom properties JSON
- `conversions`: Generated conversions JSON
- `deleted_at`: Soft delete

**Useful Methods**:
```php
$media->fullMediaPath(); // Complete absolute path
```

### PasswordHistory Model

**Table**: `password_histories`

Tracks password history to prevent reuse.

**Fields**:
- `id`: Primary key
- `user_id`: FK to users
- `password`: Password hash
- `created_at`: Creation date

### Notification Model

**Table**: `notifications`

Uses the standard Laravel Notifications system with additional fields.

---

## Authorization and Security

### Permission System

The package implements a complete RBAC system based on **Spatie Laravel Permission**.

#### Permission Structure

Permissions follow the convention: `{action} {resource}`

**Examples**:
- `read users`
- `create users`
- `update users`
- `delete users`
- `filter users`

#### Role Management

A role can have multiple permissions:

```php
$role = Role::create(['name' => 'editor']);
$role->givePermissionTo([
    'read users',
    'update users',
    'read media',
    'upload media'
]);
```

#### Assign Roles to Users

```php
$user = User::find(1);
$user->assignRole('editor');

// Check
$user->hasRole('editor'); // true
$user->hasPermissionTo('update users'); // true
```

#### Direct Permissions

Users can have direct permissions (in addition to role permissions):

```php
$user->givePermissionTo('delete users');
```

### Policies

Each model has a dedicated Policy that implements authorization rules:

```php
class UserPolicy
{
    public function read(User $authUser, User $user): bool
    {
        return $authUser->hasPermissionTo('read users') 
            || $authUser->id === $user->id;
    }
    
    public function update(User $authUser, User $user): bool
    {
        return $authUser->hasPermissionTo('update users')
            || $authUser->id === $user->id;
    }
}
```

### Route Protection

Routes are protected via gates and policies:

```php
Route::get('users/{user}', 'show')->can('read', 'user');
Route::put('users/{user}', 'update')->can('update', 'user');
Route::delete('users/{user}', 'destroy')->can('delete', 'user');
```

### Middleware

**Sanctum Authentication**:
```php
Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});
```

### Input Validation

All inputs are validated via DTO with strict rules:

```php
class CreateUserDto extends Data
{
    public function __construct(
        #[Required, Max(255)]
        public string $first_name,
        
        #[Required, Email, Unique('users', 'email')]
        public string $email,
        
        #[Required, Min(8)]
        public string $password,
    ) {}
}
```

### Password Protection

- **Hashing**: Automatic Bcrypt
- **History**: Prevents reuse of last N passwords
- **Expiration**: Expiration notification
- **Validation**: Custom policy for complexity

---

## Extension and Customization

### Override DTO

To customize validation, create a DTO in your project:

```php
namespace App\Dtos;

use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto as BaseCreateUserDto;

class CreateUserDto extends BaseCreateUserDto
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $username,
        public string $password,
        public bool $is_active = true,
        public ?array $roles = null,
        
        // Custom fields
        public ?string $phone_number = null,
        public ?string $company = null,
    ) {
        parent::__construct(...func_get_args());
    }
}
```

Then register in config:

```php
// config/data_transfer.php
return [
    \Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto::class 
        => \App\Dtos\CreateUserDto::class,
];
```

### Override Resources

Customize API serialization:

```php
namespace App\Resources;

use Wave8\Factotum\Base\Resources\Api\UserResource as BaseUserResource;

class UserResource extends BaseUserResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'phone_number' => $this->phone_number,
            'company' => $this->company,
            'full_name' => $this->first_name . ' ' . $this->last_name,
        ]);
    }
}
```

Register:

```php
// config/data_transfer.php
return [
    \Wave8\Factotum\Base\Resources\Api\UserResource::class 
        => \App\Resources\UserResource::class,
];
```

### Override Services

Extend services to add custom logic:

```php
namespace App\Services;

use Wave8\Factotum\Base\Services\Api\UserService as BaseUserService;

class UserService extends BaseUserService
{
    public function create(CreateUserDto $data): User
    {
        $user = parent::create($data);
        
        // Custom logic
        $this->sendWelcomeEmail($user);
        $this->notifyAdmins($user);
        
        return $user;
    }
}
```

Register in Service Provider:

```php
$this->app->bind(
    \Wave8\Factotum\Base\Contracts\Api\UserServiceInterface::class,
    \App\Services\UserService::class
);
```

### Add Custom Migrations

Create new migrations that extend tables:

```bash
php artisan make:migration add_custom_fields_to_users_table
```

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone_number')->nullable()->after('email');
        $table->string('company')->nullable()->after('phone_number');
    });
}
```

### Add New Media Presets

In config:

```php
'media' => [
    'presets' => [
        'product-thumbnail' => [
            'suffix' => '_thumb',
            'actions' => [
                'fit' => [
                    'method' => 'crop',
                    'width' => 150,
                    'height' => 150,
                ],
                'optimize' => [],
            ],
        ],
        'product-large' => [
            'suffix' => '_large',
            'actions' => [
                'fit' => [
                    'method' => 'contain',
                    'width' => 1200,
                    'height' => 1200,
                ],
                'optimize' => [],
            ],
        ],
    ],
],
```

### Extend Query Builders

Add methods to Query Builder:

```php
namespace App\Builders;

use Wave8\Factotum\Base\Builders\UserQueryBuilder as BaseUserQueryBuilder;

class UserQueryBuilder extends BaseUserQueryBuilder
{
    public function active()
    {
        return $this->where('is_active', true);
    }
    
    public function withCompany()
    {
        return $this->whereNotNull('company');
    }
}
```

Use in Model:

```php
public function newEloquentBuilder($query)
{
    return new \App\Builders\UserQueryBuilder($query);
}
```

### Add New Settings

Create a custom seeder:

```php
Setting::create([
    'key' => 'maintenance_mode',
    'value' => 'false',
    'group' => 'system',
    'data_type' => SettingDataType::BOOLEAN,
    'is_editable' => true,
    'description' => 'Enable/disable maintenance mode',
]);
```

### Custom Events

You can hook events to models:

```php
// In a Service Provider
User::created(function ($user) {
    // Send welcome email
    Mail::to($user->email)->send(new WelcomeEmail($user));
});

Media::created(function ($media) {
    // Process image in background
    GenerateImageConversions::dispatch($media);
});
```

### Custom Artisan Commands

Extend existing commands or create new ones:

```bash
php artisan make:command SyncUsersFromLegacySystem
```

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Wave8\Factotum\Base\Models\User;

class SyncUsersFromLegacySystem extends Command
{
    protected $signature = 'users:sync-legacy';
    protected $description = 'Sync users from legacy system';

    public function handle()
    {
        // Sync logic
    }
}
```

### Testing

The package is fully testable. Test example:

```php
use Wave8\Factotum\Base\Models\User;

test('authenticated user can view their profile', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->getJson("/api/protected/users/{$user->id}");
    
    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ]);
});
```

---

## Available Artisan Commands

### Installation

```bash
php artisan factotum-base:install [--migrate] [--seed] [--force]
```

Installs the complete package:
- Publishes migrations
- Runs migrations (if --migrate)
- Runs seeders (if --seed)
- Copies necessary files

### Media Management

```bash
php artisan factotum:generate-image-conversions
```

Regenerates all conversions of existing images. Useful after modifying presets.

### Cleanup

```bash
php artisan factotum:prune-password-histories
```

Removes older password histories keeping only the last N (configured in config).

Can be scheduled:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('factotum:prune-password-histories')->weekly();
}
```

---

## Best Practices

### 1. Use DTO for Input Validation

❌ **Don't**:
```php
public function store(Request $request)
{
    $validated = $request->validate([...]);
    $user = User::create($validated);
}
```

✅ **Do**:
```php
public function store(CreateUserDto $dto)
{
    $user = $this->userService->create($dto);
}
```

### 2. Use Service Layer for Business Logic

❌ **Don't**:
```php
public function store(CreateUserDto $dto)
{
    $user = User::create($dto->toArray());
    $user->assignRole($dto->roles);
    $user->sendEmailVerificationNotification();
    return new UserResource($user);
}
```

✅ **Do**:
```php
public function store(CreateUserDto $dto)
{
    $user = $this->userService->create($dto);
    return new UserResource($user);
}
```

### 3. Use Policies for Authorization

❌ **Don't**:
```php
public function update(User $user)
{
    if (auth()->user()->id !== $user->id && !auth()->user()->hasRole('admin')) {
        abort(403);
    }
}
```

✅ **Do**:
```php
Route::put('users/{user}', 'update')->can('update', 'user');
```

### 4. Use Query Builders for Complex Filters

❌ **Don't**:
```php
$users = User::query();
if ($request->has('email')) {
    $users->where('email', 'like', '%'.$request->email.'%');
}
if ($request->has('is_active')) {
    $users->where('is_active', $request->is_active);
}
return $users->get();
```

✅ **Do**:
```php
return User::query()->filterByRequest()->paginate();
```

### 5. Use Resources for API Responses

❌ **Don't**:
```php
return response()->json([
    'id' => $user->id,
    'name' => $user->first_name . ' ' . $user->last_name,
    'email' => $user->email,
]);
```

✅ **Do**:
```php
return new UserResource($user);
```

### 6. Configure Appropriate Media Presets

Define specific presets for each use case instead of manipulating images on the fly:

```php
'presets' => [
    'user-avatar' => [...],
    'blog-featured' => [...],
    'product-gallery' => [...],
],
```

### 7. Use Soft Delete

Always keep deleted data for audit and recovery:

```php
use SoftDeletes;
```

### 8. Implement Password Policies

Configure appropriate password requirements:

```env
PASSWORD_EXPIRATION_DAYS=90
PASSWORD_PRUNE_KEEP=10
PASSWORD_VALIDATE_LATEST=5
```

---

## Troubleshooting

### Error: "Class User not found"

**Cause**: You haven't extended the package's User model.

**Solution**: 
```php
// app/Models/User.php
use Wave8\Factotum\Base\Models\User as FactotumUser;

class User extends FactotumUser
{
}
```

### Error: "Token mismatch"

**Cause**: CSRF token not configured or Sanctum not configured correctly.

**Solution**:
```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi();
})
```

### Error: "This action is unauthorized"

**Cause**: Missing permissions or policies not configured.

**Solution**:
1. Verify that the user has the necessary role/permission
2. Verify that the policy is registered correctly
3. Check logs for details

### Media Conversions Not Generated

**Cause**: Jobs not processed or presets not configured.

**Solution**:
1. Verify queue configuration
2. Regenerate conversions: `php artisan factotum:generate-image-conversions`
3. Check that presets are defined in config

### Migrations Not Executed

**Cause**: Migrations not published.

**Solution**:
```bash
php artisan vendor:publish --tag=factotum-base-migrations
php artisan migrate
```

---

## Support and Contributions

### Documentation

- **README**: `/README.md`
- **Changelog**: `/CHANGELOG.md`

### Bug Reporting

To report bugs or issues, contact the development team or open an issue in the repository.

### Testing

The package includes a complete test suite:

```bash
composer test          # Runs all tests
composer test:unit     # Unit tests only
composer test:lint     # Code style check
```

### Code Style

The project uses Laravel Pint for code style:

```bash
composer lint          # Formats code
composer test:lint     # Verifies code style
```

---

## License

Factotum Base Module is released under **MIT** license.

Copyright © 2025 8 Wave S.r.l.

---

## Credits

Developed and maintained by **8 Wave S.r.l.**

### Third-Party Libraries

- Laravel Framework
- Spatie Laravel Permission
- Spatie Laravel Data
- Spatie Laravel Query Builder
- Spatie Image & Image Optimizer
- Laravel Sanctum

---

## Roadmap

### Future Features

- [ ] Two-Factor Authentication (2FA)
- [ ] OAuth2 Integration
- [ ] Advanced Media Gallery
- [ ] Activity Log
- [ ] Data Export/Import
- [ ] Advanced Notification System
- [ ] WebSocket Support
- [ ] Multi-tenancy Support

---

**Documentation Version**: 1.0.0  
**Date**: December 2025  
**Package Version**: ^1.6
