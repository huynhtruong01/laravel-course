## Middleware

- **Mục đích**: Dùng để thực hiện trước khi thực hiện **controller** tiếp theo.

---

### `01` - Structure của 1 middleware

1. **`Create a middleware file`**

```bash
php artisan make:middleware VerifyAccount
```

2. **`Structure`**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Log request::', ['request' => $request->route()->parameters()]);
        return $next($request);
    }
}
```

- Gồm `2 parameters`: 
  - **request**: Nhận `request`. 
  - **Closure next**: `Là 1 function move tới logic tiếp theo đê`

---

### `02` - Registration Middleware

1. **`Global Middleware`**

- Bạn có thể add **middleware** vào `bootstrap/app.php` khi bạn muốn all router đều phải dùng **middleware**.

```php
use App\Http\Middleware\EnsureTokenIsValid;
 
->withMiddleware(function (Middleware $middleware) {
     $middleware->append(EnsureTokenIsValid::class);
})
```
- `append`: Dùng để add vào cuối list **global middleware**.
- `prepend`: Dùng để add vào đầu list **global middleware**.

- Hoặc bạn có thể dùng **use** dùng để `manage stack` **global middleware**.

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->use([
        // \Illuminate\Http\Middleware\TrustHosts::class,
        \Illuminate\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ]);
})
```

2. **`Assign Middleware to Routes`**

- Bạn có thể `assign` **middle** trên **route**. (`middleware`)

```php
use App\Http\Middleware\EnsureTokenIsValid;
 
Route::get('/profile', function () {
    // ...
})->middleware(EnsureTokenIsValid::class);
```

- Bạn thể `exclude` **middleware** khi dùng **global middleware**. (`withoutMiddleware`)

```php
use App\Http\Middleware\EnsureTokenIsValid;

Route::get('/profile', function () {
    // ...
})->withoutMiddleware(EnsureTokenIsValid::class);

// ❓❓ HOẶC

Route::withoutMiddleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('/profile', function () {
        // ...
    });
});
```

- Bạn có thể define `1 list alias` cho **các middleware** trong `bootstrap/app.php`.

```php
use App\Http\Middleware\VerifyAccount;
 
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'verify-account' => VerifyAccount::class
    ]);
})
```

- **Sau đó**, bạn use **alias middleware** vào **routes**. Giúp đơn giản hóa được `name` của **middleware**.

```php
Route::get('/profile', function () {
    // ...
})->middleware('verify-account');
```

3. **`Group Middleware to Routes`**

---

### `03` - Middleware Parameters

- Bạn có truyền parameter cho middleware ngoài `$request, $next`. Ví dụ bạn muốn truyền **role** để check, bạn có thể truyền `role` vào.

- Ở đây bạn có thể separate nó bằng cách define `name của middle` và `value`.

```php
<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()->hasRole($role)) {
            // Redirect...
        }
 
        return $next($request);
    }
 
}

// routes

Route::put('/users', function () {
    // to do ...
})->middleware('role:admin');
```