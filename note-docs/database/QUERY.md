## Query

- [Query](#query)
  - [`01` - Lazy Loading vs Eager Loading](#01---lazy-loading-vs-eager-loading)

---

### `01` - Lazy Loading vs Eager Loading

1. **`Lazy Loading`**

- **`Lazy loading`**: Là behavior default của Eloquent. Những relation data sẽ không tải ngay lập tức, nó chỉ load khi mà bạn truy cập dến nó.
- **Example**:

```php
<?php

$posts = Post::all(); // 📌it only query list post, not query relation data inside.

foreach($posts as $post) {
    $post->comments = $post->comments; // 📌 it query relation data 'comments' cho từng post access.
}

// ⚠️ nếu có 100 posts, query tới 101 queries (tính get all posts)
```

- Điều này ảnh hưởng tới performance của app, khi mà query relation data với lượng data lớn.
- Ảnh hưởng tới `N+1` query không hiệu quả.

```sql
SELECT * FROM `posts`; -- 📌 get all posts

SELECT * FROM `comments` WHERE `post_id` = 1; -- 📌 get relation comment for each post
SELECT * FROM `comments` WHERE `post_id` = 2;
SELECT * FROM `comments` WHERE `post_id` = 3;
...
```

> - **Ưu điểm**: Đơn giản, dễ sử dụng, không tải dữ liệu không cần thiết.
> - **Nhược điểm**: Có thể dẫn đến nhiều truy vấn không hiệu quả (N+1 query).

2. **`Eager Loading`** (`Tải data chủ động`)

- **`Eager Loading`**: Tức là nó sẽ query trước những relation data. Khi gọi relation data, nó sẽ không query bởi vì nó đã query trước đó rồi.

- Query relation data của `Post` & `Comment` sẽ đươc **tối ưu** hơn với 1 query (`không cần query từng record 1 như Lazy Loading`).
- Chỉ với `2 query` là đủ.

```sql
SELECT * FROM `posts`; -- 📌 get all posts first
SELECT * FROM `comments` WHERE `post_id` IN (1, 2, 3, ...); -- 📌 get relation comment for each post
```

> - **Ưu điểm**: Giảm số lượng truy vấn, tránh vấn đề `N+1 query`, tối ưu hiệu suất.
> - **Nhược điểm**: Tải nhiều dữ liệu ngay lập tức, có thể không cần thiết nếu không sử dụng tất cả các mối quan hệ.