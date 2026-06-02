# nexion Framework

A full-fledged organized PHP framework inspired by Laravel.

## Features
- **PSR-4 Autoloading**: Custom implementation for clean namespace management.
- **Routing**: Support for GET/POST, dynamic parameters (`/user/{id}`), and closures.
- **Middleware**: Intercept requests before they reach your controllers.
- **ORM**: Laravel-style Active Record implementation (`all()`, `find()`, `save()`).
- **Template Engine**: Regex-based engine with `@if`, `@foreach`, `{{ }}` and layout inheritance (`@extends`, `@yield`).
- **MVC Architecture**: Clearly separated Core and App logic.

## Directory Structure
- `app/`: Your application logic (Controllers, Models, Middleware, Views).
- `core/`: The framework's engine.
- `public/`: Public entry point.
- `routes/`: Route definitions.
- `storage/`: Cache and logs.

## Getting Started
1. Point your web server to the `public/` directory.
2. Configure your database in `public/index.php`.
3. Define your routes in `routes/web.php`.
4. Create controllers in `app/Controllers/`.

## Example Usage

### Routing
```php
$router->get('/user/{id}', [UserController::class, 'show'])->middleware(AuthMiddleware::class);
```

### Controller
```php
class UserController extends BaseController {
    public function show($request, $id) {
        $user = User::find($id);
        return $this->render('user.profile', compact('user'));
    }
}
```

### Model
```php
class User extends Model {
    protected string $table = 'users';
}
```

### View (Blade-like)
```html
@extends('layout.main')
@section('content')
    <h1>Hello, {{ $user->name }}</h1>
@endsection
```
