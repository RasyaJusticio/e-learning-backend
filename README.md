# E-Learning

## Requirements
This projects requires the following to be installed in your system

- PHP 8.2 or higher
- Composer 2.x or higher
## Run Locally

Clone the project

```bash
git clone https://github.com/RasyaJusticio/e-learning-backend.git
```

Go to the project directory

```bash
cd e-learning-backend
```

Install dependencies

```bash
composer install
```

Configure your .env file. And migrate the migrations.

```bash
php artisan migrate --seed
```

Run the server
```bash
php artisan serve
```
