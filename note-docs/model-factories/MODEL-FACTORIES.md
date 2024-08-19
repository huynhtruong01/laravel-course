## Model Factories

- [Model Factories](#model-factories)
  - [`01` - Generate \& Create Model Factories \& Add Seeder to Run](#01---generate--create-model-factories--add-seeder-to-run)
  - [`02` - Factory State](#02---factory-state)
  - [`03` - AfterMaking \& AfterCreating](#03---aftermaking--aftercreating)

---

### `01` - Generate & Create Model Factories & Add Seeder to Run

- **Model Factories**: là 1 công cụ dùng để tạo dummy data **instead** phải tạo data thủ công bằng cách bạn tạo 1 api rồi bạn chạy bằng **postman** tốn rất nhiều thời gian.
- Trong Laravel, bạn có thể create dummy data với [**Faker**](https://github.com/fzaninotto/Faker).

- **Generate Factory Model với command**:

```bash
php artisan make:factory CategoryFactory

# nếu có model đươc tạo sẵn
php artisan make:factory CategoryFactory --model=Category
```

- Sau khi tạo xong, sẽ có code:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(), // 📌 define factory theo fields trong model
        ];
    }
}
```

- Sau đó, bạn có thể apply nó vào **seeder** để seed data vào **database**.

```php
public function run(): void {
    Category::factory()->count(3)->create(['article_id' => 1]);
}

// 📌 php artisan db:seed CategorySeeder
```

---

### `02` - Factory State

- **Factory State**: Cho phép bạn có thể `modify` 1 số attribute trong default attribute đã được tạo.
- Nó giống như **pipeline (`đường ống`)**, tức là sau khi tạo xong attribute basic sau đó đi qua **state** cho phép `modify` những attribute đã rồi.

```php
<?php

public function modifyCategoryName(): Factory
{
    return $this->state(function (array $attributes) {
        // 📌 attributes: là những đã tạo rồi và qua state để modify
        return [
            'name' => 'Modify name category',
        ];
    });
}
```

- **Apply it**:

```php
public function run(): void {
    Category::factory()->count(3)->modifyCategoryName()->create(['article_id' => 1]);
}
```

---

### `03` - AfterMaking & AfterCreating

> [https://laravel.com/docs/11.x/eloquent-factories#factory-callbacks](https://laravel.com/docs/11.x/eloquent-factories#factory-callbacks)

- **afterMaking** & **afterCreating**: Cho phép bạn có thể thêm tasks sau khi `make` hoặc `create` 1 model.
- Register nó trong **configure** method.

```php
namespace Database\Factories;
 
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class UserFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (User $user) {
            // ...
        })->afterCreating(function (User $user) {
            // ...
        });
    }
 
    // ...
}
```

- 📌 Hoặc bạn có thể chạy nó cùng với **state factory**

```php
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
 
/**
 * Indicate that the user is suspended.
 */
public function suspended(): Factory
{
    return $this->state(function (array $attributes) {
        return [
            'account_status' => 'suspended',
        ];
    })->afterMaking(function (User $user) {
        // ...
    })->afterCreating(function (User $user) {
        // ...
    });
}
```