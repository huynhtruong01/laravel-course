## Routes

---

### `01` - Define Routes

- Bạn có thể define route trong **Laravel** như này. Đươc define trong file `routes/web.php`.

```php
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return 'Hello world';
});

// 📌 GET, POST, PUT/PATCH, DELETE
```

- Syntax define Route:

```php
use Illuminate\Support\Facades\Route;

Route::get(<url>, <callback>);
```

---

### `02` - Manage & Name Routes

1. **`Show list routes`**

```bash
php artisan route:list
# 📌 to show list routes
```

2. **`Define Name Routes`**

```php
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return 'Home page';
})->name('home');
```

```php
<a href="{{ redirect('home') }}">Go back home</a>
```

- Giúp thuận tiện trong việc redirect đến các trang specific.
- Có nghĩa là khi define name routes và sử dụng nó, nó sẽ `auto mapping với url trong route`.
- `Dễ dàng tạo liên kết và redirect`.

---

### `03` - Route Params & Optional Params

1. **`Params`**

```php
Route::get('/products/{id}', function(int $id) {
    // do something...
    return 'Post id ' . $id;
});
```

2. **`Optional Params`**

```php
Route::get('/recent-post/{day_ago?}', function($dayAgo = 20) {
    return 'Recent post ' . $dayAgo;
})->name('recent post');
```

---

### `04` - Constraint Params

- Bạn có thể check input params từ route trả về.

```php
Route::get('/recent-post/{day_ago?}', function($dayAgo = 20) {
    return 'Recent post ' . $dayAgo;
})
    ->where('id', '[0-9]+') // 📌 check id param là number
    ->name('recent post');
```

- **Bạn có thể đặt nó ở global trong `app\Providers\AppServiceProvider`**. Khi route được call, nó sẽ đi qua chỗ này.

```php
use Illuminate\Support\Facades\Route;
 
/**
 * Bootstrap any application services.
 */
public function boot(): void
{
    Route::pattern('id', '[0-9]+');
}
```