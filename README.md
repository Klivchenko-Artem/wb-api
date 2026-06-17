# WB API Data Import

Laravel-приложение для импорта данных из тестового WB API в MySQL.

## Стек

- PHP 8.1
- Laravel 8
- MySQL 8.0

## Установка

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Настройка .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wb_api
DB_USERNAME=root
DB_PASSWORD=

WB_API_HOST=http://109.73.206.144:6969
WB_API_KEY=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
```

## Импорт данных

```bash
# Импорт всех сущностей
php artisan wb:import

# Импорт конкретной сущности
php artisan wb:import sales
php artisan wb:import orders
php artisan wb:import stocks
php artisan wb:import incomes
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

## API Эндпоинты

| Эндпоинт | Метод | Параметры |
|----------|-------|-----------|
| /api/sales | GET | dateFrom, dateTo |
| /api/orders | GET | dateFrom, dateTo |
| /api/stocks | GET | dateFrom (только текущий день) |
| /api/incomes | GET | dateFrom, dateTo |

Авторизация: параметр `key` в строке запроса.
