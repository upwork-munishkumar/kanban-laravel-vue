# kanban-laravel-vue

# Kanban Board in Laravel and VUE

### Open Terminal and clone project. (git clone https://github.com/upwork-munishkumar/kanban-laravel-vue.git)

### Do the below steps to run project. (Make sure to run commands in project directory)

##### 1. Change file name .env.example to .env and configure it with your DB connection.

##### 2. Install composer to load all dependencies

```
composer install
```

##### 3. Generate Key for Laravel Application

```
php artisan key:generate
```

##### 4. Link Storage

```
php artisan storage:link
```

##### 5. Install node modules depenedencies

```
npm install
```

##### 6. Compile assets

```
npm run dev
```

##### 7. Now run migrations - (Need to run only once after cloning or if created new migration after cloning)

```
php artisan migrate
```

##### 8. Start Laravel Server

```
php artisan serve
```# kanban-laravel-vue
