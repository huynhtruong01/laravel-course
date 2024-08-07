## Request & Response

---

### Request

---

### Response

1. **`Response Object`**

```php
Route::get('/posts', function() {
    return response([
        ['name' => 'Huynh Phuoc Truong', 'age' => 20],
    ], 200)
    ->header('Content-Type', 'application/json')
    ->cookie('MY_TOKEN', '__token__', $minutes, $path, $domain, $secure, $httpOnly);
})
```