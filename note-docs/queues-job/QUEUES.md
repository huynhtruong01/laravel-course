## QUEUES

---

### `01` - Job & Queue & Worker

1. **`Queue dùng để làm gì?`**

- **Queue**: Dùng để xử lý các tác vụ nặng và cho nó chạy ngầm trong khi xử lý những tác vụ khác, giúp cho trải nghiệm của người dùng tốt hơn vaf không bị tắc nghẽn khi thực thi 1 request.

- **Queue Laravel** gồm có **`3 phần`**:
  - **Job**
  - **Queue**
  - **Worker**

- **Step Queue Laravel**:

![Queue Steps](../images/steps-queue-laravel.png)

2. **`Job`**

**Connection**:

- **Laravel** cung cấp cho bạn nhiều connection khác nhau để sử dụng: `Beanstalk`, `Amazon SQS`, `Redis` hoặc `Database`.
- Nó được `config` trong `config/queue.php`. 
- Mặc định **Queue Driver** được dùng để thực thi khi có **Job** thêm vào.

**Create Job**:

- **QUEUE_CONNECTION** mặc định sẽ là `sync`, thông thường chúng ta sẽ save nó trong `database`, nên ta cần đổi sang `database` trong `config/queue.php`.
- Create migration `jobs`:

```bash
php artisan queue:table

php artisan migrate
```

- Create `first job`:

```bash
php artisan make:job SendWelcomeEmail
```

**Class Structure**:

- Trong class **Job** sẽ có method `handle()` sẽ xử lý logic.

```php
<?php
 
namespace App\Jobs;
 
use App\Models\Podcast;
use App\Services\AudioProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
 
class ProcessPodcast implements ShouldQueue
{
    use Queueable;
 
    /**
     * Create a new job instance.
     */
    public function __construct(
        public Podcast $podcast,
    ) {}
 
    /**
     * Execute the job.
     */
    public function handle(AudioProcessor $processor): void
    {
        // 📌 Process uploaded podcast...
    }
}
```

3. **`Queue`** 

- Sau khi tạo **Job** xong, bạn cần `dispatch` **Job** vào **Queue** 

```php
ProcessPodcast::dispatch();
```

- Có rất nhiều cách để `dispatch`:
  - `Queue::push(new SendWelcomeEmail());`
  - `Bus::dispatch(new SendWelcomeEmail());`
  - `dispatch(new SendWelcomeEmail());`
  - `(new SendWelcomeEmail())->dispatch();`

**Conditional Dispatch**:

- Gồm: `dispatchIf`, `dispatchUnless`

**Delay Dispatch**:

```php
SendWelcomeEmail::dispatch()->delay(300); // 300s
```

- **⚠️ Notes**: Trong **Amazon SQS** chỉ được `delay` tối đa là `15 phút`.

**Dispatch after Response**: `dispatchAfterResponse`

```php
SendWelcomeEmail::dispatchAfterResponse();
```

- **⚠️ Notes**: Dispatch này sẽ không được đưa vào **Queue** và không cần xử lý bằng **worker**.

**Sync Dispatch**: `dispatchSync`

- Nó sẽ xử lý đồng bộ mà không cần đưa vào **Queue** và không handle bởi **worker**.

```php
SendWelcomeEmail::dispatchSync();
```

- Nó giống như `bạn bỏ 1 function vào để chạy nhưng nó sẽ đóng gói logic chạy bên trong`.

4. **`Worker`**

- **Worker**: là 1 tác vụ chạy ngầm trong **PHP**, lấy **Job** từ `storage (database, redis, AWS)` ra và chạy.

```bash
php artisan queue:work
```

- **⚠️Notes**: 
  - Nó sẽ chạy vô hạn cho đến khi đóng thủ công hoặc đóng terminal. 
  - Code bạn change, nó cũng không áp dụng cho lần chạy này. Bắt buộc phải restart lại **worker** khi có code change.

- Bạn có thể cho phép **worker** chỉ được chạy 1 **Job**:

```bash
php artisan queue:work --once
```

- Bạn có thể cho phép **worker** có thể chạy với code mới nhất. (`listen`). Nó sẽ chạy command `queue:work –once` trong 1 vòng loop vô hạn, điều này dẫn đến tốn tài nguyên server hơn.

```bash
php artisan queue:listen
```

**Queue với Priority**

```bash
# 📌 chạy đúng thứ tự high > default > low
php artisan queue:work --queue=high,default,low
```

**Queue với Retries**

- Cho phép thực thi job đó `tối đa 3 lần`. Nếu không process được, job đó sẽ được đưa vào là **Job Failed**.

```bash
php artisan queue:work --tries=3
```

**Queue với Timeout**

- Cho phép **Job** thực thi trong 1 khoảng thời gian. Nếu **Job** không thực thi được, **Job** sẽ đánh giá là **failed**. 

```bash
php artisan queue:work --timeout=30 # 30s
```

---

### `02` - Queue Failed

1. **`Create & Migration`**

- Nó cũng phải create 1 table để lưu **queue failed**.

```bash
php artisan make:queue-failed-table
 
php artisan migrate
```

2. **`View All Queue`**

- Dùng để view tất cả **queue failed**.

```bash
php artisan queue:failed
```

- Bạn có thể `retry` queue bị **failed** theo `id`.

```bash
# queue retry by id
php artisan queue:retry ce7bb17c-cdd8-41f0-a8ec-7b4fef4e5ece

# queue retry all
php artisan queue:retry all
```

3. **`Delete Queue Failed`**

- Dùng `forgot` để xóa 1 **queue failed**.
- Dùng `flush` để xóa toàn bộ.

```bash
# delete 1 queue
php artisan queue:forget 91401d2c-0784-4f43-824c-34f94a33c24d

# delete all
php artisan queue:flush
```