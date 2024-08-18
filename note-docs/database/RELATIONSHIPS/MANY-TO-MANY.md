## Many to Many

1. **`Many to Many là gì?`**

- Tức là 1 record trong table `Post` có zero hoặc nhiều record trong table `Tag` và ngược lại.
- Sử dụng `belongsToMany`, `pivot table (bảng trung gian cho 2 bảng)`.

- Sừ dụng `attach` để đưa blog id vào. `detach` dùng để remove 1 hoặc nhiều record trong **pivot table**.
> [https://laravel.com/docs/11.x/eloquent-relationships#attaching-detaching](https://laravel.com/docs/11.x/eloquent-relationships#attaching-detaching)

1. **`Coding`**

- Bạn cần tạo file migrate cho:
  - **Blog**
  - **Tag**
  - **BlogTag** (`pivot table`): `id`, `blog_id`, `tag_id`, `updated_at`, `created_at`.

```php
// blog
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->belongsToMany(Tag::class); // blog is owner
    }
}

// tag
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function blog()
    {
        return $this->belongsToMany(Blog::class); // tag is belong to blog
    }
}
```

- Cách để add data giữa 2 table trong `tinker`.

```php
<?php

$blog = new Blog();
$blog->save(); // save to blog to take id

$tag = new Tag();
$tag->blogs()->attach($blog->id); // save for tag with id blog
```