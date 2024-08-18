## Request & Response

- [Request \& Response](#request--response)
  - [`01` - Request](#01---request)
  - [`02` - Response](#02---response)

---

### `01` - Request

1. **`Request Input`**

- `all()`: Get all `query params`, `form data (POST)`, `JSON payload`, `File`,.... Return về dạng `associative array`.
- `input($name, $defaultValue)`: Get value từ `query params`, `payload (POST)`.
- `query($name, $defaultValue)`: Get value từ `query params`.

```php
use Illuminate\Http\Request;

Route::get('/posts', function(Request $request) {
    dd($request->all());
    dd($request->input('limit', 1));
    dd($request->query('limit', 1));
})
```

> Mọi thứ lấy từ `query params`, `params` đều là `string` cho dù đó có phải là `number`.

---

### `02` - Response

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

2. **`Response Redirect`**

- **Redirect by URL**: `redirect('/home')`
- **Redirect by Name Route**: `redirect()->route('home_page')`
- **Redirect with Name Route & Params**: `redirect()->route('posts', ['id'=>2])`
- **Redirect với 1 specific URL `away`**: `redirect()->away('https://google.com')`
- **Back URL**: `back()`

3. **Return Response `JSON`**

```php
Route::get('/posts', function() {
    $posts = [
        [
            'id' => 1,
            'title' => 'Post 1'
        ],
        [
            'id' => 2,
            'title' => 'Post 2'
        ],
    ];
    return response()->json($posts);
});
```

4. **Response `Download File`**

> `response()->download($pathToFile, $name, $header)`

```php
Route::get('/download', function() {
    return response()->download(public_path('/image.png'), 'Natural');
});
// ⚠️ file must be in `/public` folder
```

5. **`Group Routes`**

```php
$posts = [
    [
        'id' => 1,
        'title' => 'Post 1',
    ],
    [
        'id' => 2,
        'title' => 'Post 2',
    ],
    [
        'id' => 3,
        'title' => 'Post 3',
    ],
]

Route::prefix('/posts')->group(function () use ($posts) {
    Route::get('/', function () use ($posts) {
        return response()->json($posts);
    });

    Route::get('/{id}', function (string $id) use ($posts) {
        $foundPost = null;
        foreach ($posts as $post) {
            if ($post['id'] === intval($id)) {
                $foundPost = $post;
                break;
            }
        }
        if (empty($foundPost)) {
            return response()->json('Not found post', 404);
        }
        return response()->json($foundPost);
    });
});
```