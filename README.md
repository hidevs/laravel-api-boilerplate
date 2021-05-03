<p align="center">
<a href="https://hidevs.team" target="_blank">
<img src="public/welcome/img/logo.png" width="900">
</a>
</p>

# Laravel API base boilerplate


## Installed packages
Review this package documentation before start project

* [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth)
* [silber/bouncer](https://github.com/JosephSilber/bouncer)
* [qcod/laravel-settings](https://github.com/qcod/laravel-settings)
* [spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary)
* [morilog/jalali](https://github.com/morilog/jalali)


## Customization

### [Exception Handler](app/Exceptions/Handler.php)
Complete API base exception handler

### [Helper](app/Helpers/base.php)
Create good helpers

### [JsonHandler Middleware](app/Http/Middleware/JsonHandler.php)
Set all request header `Accept` key to `application/json`

### [Media-Library Package](https://spatie.be/docs/laravel-medialibrary)
Associate files with eloquent models

### [Media-Library Config](config/media-library.php)
Media-Library configuration

### [Bouncer Seeder](database/seeders/BouncerSeeder.php)
ACL role and permission seeder

### [Setting Seeder](database/seeders/SettingSeeder.php)
Project setting seeder

### [JWT Config](config/jwt.php)
JWT configuration

### [Welcome Page](resources/views/welcome.blade.php)
First page in project at route `/`




## Usage

### 1. Create .env file and config
```bash
$ cp .env.example .env
```
```dotenv
APP_KEY=
.
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
.
CACHE_DRIVER=database
QUEUE_CONNECTION=database
.
JWT_SECRET=
```


### 3. Build Setup

```bash
# install packages
$ composer install

# install first configuration
$ php artisan hd:install
# this command contains these:
# 1. generate jwt secret token
# 2. link cdn storage
# 3. remove this year directory in cdn storage
# 4. drop all tables
# 5. migrate tables
# 6. seeding first data to tables
# 7. running queue
```

