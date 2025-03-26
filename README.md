# Todo List API

Это проект на Laravel, предоставляющий REST API для управления списком задач (To-Do List), с поддержкой JWT-аутентификации для безопасного доступа и взаимодействия с задачами.

## Технологии:
- **Backend**: Laravel 8.x
- **База данных**: MySQL
- **Аутентификация**: JWT (JSON Web Token)

## Установка и настройка

### 1. Клонировать репозиторий

```bash
git clone https://github.com/yourusername/todo-list.git
cd todo-list
```

### 2. Установить зависимости с помощью Composer

```bash
composer install
```

### 3. Настроить файл .env

Скопируйте файл `.env.example` и переименуйте его в `.env`:

```bash
cp .env.example .env
```

Теперь настройте параметры базы данных и JWT в файле `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_list
DB_USERNAME=root
DB_PASSWORD=

# JWT settings
JWT_SECRET=your_generated_jwt_secret
```

Для генерации `JWT_SECRET` используйте команду:

```bash
php artisan jwt:secret
```

### 4. Выполните миграции для создания таблиц в базе данных

```bash
php artisan migrate
```

### 5. Запустите сервер

```bash
php artisan serve
```

Сервер будет доступен по адресу: `http://localhost:8000`.

## Использование API

### Регистрация

POST-запрос на `POST /api/register`

```json
{
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

Ответ:

```json
{
    "message": "User registered successfully",
    "token": "your_jwt_token"
}
```

### Авторизация

POST-запрос на `POST /api/login`

```json
{
    "email": "user@example.com",
    "password": "password"
}
```

Ответ:

```json
{
    "token": "your_jwt_token"
}
```

### Список задач

GET-запрос на `GET /api/tasks` (для получения списка задач текущего пользователя).

Ответ:

```json
[
    {
        "id": 1,
        "title": "Task 1",
        "description": "Description of task 1",
        "status": "pending",
        "created_at": "2025-03-25T10:00:00Z",
        "updated_at": "2025-03-25T10:00:00Z"
    },
    ...
]
```

### Создание задачи

POST-запрос на `POST /api/tasks`:

```json
{
    "title": "New Task",
    "description": "Task description",
    "status": "pending"
}
```

Ответ:

```json
{
    "id": 2,
    "title": "New Task",
    "description": "Task description",
    "status": "pending",
    "created_at": "2025-03-25T11:00:00Z",
    "updated_at": "2025-03-25T11:00:00Z"
}
```

### Обновление задачи

PUT-запрос на `PUT /api/tasks/{id}`:

```json
{
    "title": "Updated Task",
    "description": "Updated description",
    "status": "completed"
}
```

Ответ:

```json
{
    "id": 2,
    "title": "Updated Task",
    "description": "Updated description",
    "status": "completed",
    "created_at": "2025-03-25T11:00:00Z",
    "updated_at": "2025-03-25T12:00:00Z"
}
```

### Удаление задачи

DELETE-запрос на `DELETE /api/tasks/{id}`.

Ответ:

```json
{
    "message": "Task deleted successfully"
}
```

### Выход (Logout)

POST-запрос на `POST /api/logout` для выхода.

Ответ:

```json
{
    "message": "Successfully logged out"
}
```

## Дополнительные настройки

### Кэширование и оптимизация

После установки зависимостей рекомендуется выполнить оптимизацию приложения для улучшения производительности:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

Этот `README` предоставляет все необходимые шаги для запуска и работы с проектом, включая создание задач, управление ими, а также регистрацию и аутентификацию через API.
