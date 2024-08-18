## `01` - One to One

1. **`One to One là gì?`**

- Là table `User` chỉ có 1 record trong table `Book` và relation được store trong `Book` (`unique`).

> **⚠️Notes**: 
> - `has*`: methods on Model that own the relation, không có relation column.
> - `belong*`: methods on Models nằm phía bên kia mối quan hệ, có relation column.

2. **`Coding`**

```php
// author
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public function profile()
    {
        return $this->hasOne(Profile::class); // author is owner
    }
}

// profile
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(Author::class); // profile is belong to author
    }
}
```

- Cách để add data giữa 2 table trong `tinker`.

```php
<?php

$author = new Author();
$author->save(); // save to generate id author

$profile = new Profile();
$author->profile()->save($profile); // save for profile
```