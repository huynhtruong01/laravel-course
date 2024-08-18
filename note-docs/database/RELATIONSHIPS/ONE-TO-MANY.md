## One to Many

1. **`One to Many là gì?`**

- Tức là 1 record trong table `Blog` có thể có 0 hoặc nhiều records của table `Comment`.
- `Blog` có related tới 1, 2, 3, n... `Comment`. Vì vậy relation này được store trong `Comment`. Tức là `Comment` phải có 1 `foreign key` tới `id (primary key)` table `Blog`.

> **⚠️Notes**: 
> - `has*`: methods on Model that own the relation, không có relation column.
> - `belong*`: methods on Models nằm phía bên kia mối quan hệ, có relation column.

2. **`Coding`**

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
        return $this->hasMany(Comment::class); // blog is owner
    }
}

// comment
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function blog()
    {
        return $this->belongsTo(Blog::class); // comment is belong to blog
    }
}
```

- Cách để add data giữa 2 table trong `tinker`.

```php
<?php

$blog = new Blog();
$blog->save(); // save to generate id blog

$comment = new Comment();
$blog->comment()->save($comment); // save for comment
```