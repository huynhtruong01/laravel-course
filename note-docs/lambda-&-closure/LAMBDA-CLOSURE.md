## Lambda & Closure

---

### `01` - Lambda

- Là 1 **anonymous function**, nó **không có tên**.

- `1`. **Assign cho variable**

```php
<?php

$sayHello = function() {
    echo 'Hello world';
}
```

- `2`. **Sử dụng lambda function và bỏ lambda function vào function như 1 parameter**

```php
<?php
function sayHello($messageCallback) {
    return $messageCallback();
}

sayHello(function() {
    return 'Hello world';
})
```

---

### `02` - Closure (`Hàm bao đóng`)

- Là 1 function bao đóng, nó cũng giống như **Lambda**.
- Nó cũng có truy cập data từ bên ngoài khi dùng **use**.

```php
<?php

$user = [
    'name' => 'Huynh Truong',
    'age' => 20,
];

function renderUserInfo() use ($user) {
    echo $user['name'] . 'with' . $user['age'];
}

renderUserInfo();
```