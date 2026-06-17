# WB API

Laravel-приложение для импорта и выдачи данных из WB API.

## Стек

- PHP 8.1
- Laravel 8
- Laravel Octane (Swoole)
- MySQL 8.0
- Docker / docker-compose

## Установка (Docker)

```bash
docker-compose up -d --build
docker-compose exec app php artisan migrate
docker-compose exec app php artisan wb:import
```

## Установка (локально)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan wb:import
```

## Импорт данных

```bash
php artisan wb:import           # все сущности
php artisan wb:import sales     # только продажи
php artisan wb:import orders    # только заказы
php artisan wb:import stocks    # только склады
php artisan wb:import incomes   # только доходы
```

## API

### Авторизация

Передача секретного токена в параметре `key` строки запроса.

### Формат дат

- Дата: `Y-m-d`
- Дата + время: `Y-m-d H:i:s`

### Пагинация

- По умолчанию 500 записей на страницу
- Параметр `limit` — количество записей (макс. 500)
- Параметр `page` — номер страницы

### Эндпоинты

#### Продажи

```
GET /api/sales?dateFrom={Y-m-d}&dateTo={Y-m-d}&page=1&limit=500&key={токен}
```

#### Заказы

```
GET /api/orders?dateFrom={Y-m-d}&dateTo={Y-m-d}&page=1&limit=500&key={токен}
```

#### Склады (только текущий день)

```
GET /api/stocks?dateFrom={Y-m-d}&page=1&limit=500&key={токен}
```

#### Доходы

```
GET /api/incomes?dateFrom={Y-m-d}&dateTo={Y-m-d}&page=1&limit=500&key={токен}
```

### Пример запроса

```
/api/orders?dateFrom=2024-01-01&dateTo=2024-12-31&page=1&limit=100&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
```

## Доступы к БД (Railway)

| Параметр | Значение |
|----------|----------|
| Host | thomas.proxy.rlwy.net |
| Port | 21213 |
| User | root |
| Password | nPHATOoBASgeUrytdWVeGnaFikJFxjPS |
| Database | railway |

Строка подключения:
```
mysql://root:nPHATOoBASgeUrytdWVeGnaFikJFxjPS@thomas.proxy.rlwy.net:21213/railway
```

## Таблицы

| Таблица | Описание | Записей |
|---------|----------|---------|
| sales | Продажи | 132 093 |
| orders | Заказы | 145 397 |
| stocks | Остатки на складах | 3 559 |
| incomes | Доходы/поставки | 2 959 |

## Источник данных

- Хост: `109.73.206.144:6969`
- Ключ: `E6kUTYrYwZq2tN4QEtyzsbEBk3ie`
