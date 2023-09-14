<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

#  laravel-9 + Layui + Laymini 

## 服务器配置
```bash
CPU = 16 或 8 或 4核心
RAM = 32 或 16 或 8GB 
SSD = 1TB  或 512GB
主机位置 = 香港
```

## 宝塔配置
```bash
Php - 8.0
Nginx 
Mysql  
数据库 用 utf-8 或者 utf-16 charset 和 collation
```

## Php

### 安装扩展
```bash
BCMath 
Ctype 
Fileinfo 
JSON 
Mbstring 
OpenSSL 
PDO 
Tokenizer
XML 
```

## Nginx 
#### 配置
```bash
访问目录 需要指向 public 目录
```
#### Server Nginx
```bash
add_header X-Frame-Options "SAMEORIGIN";
add_header X-Content-Type-Options "nosniff";
add_header Referrer-Policy "origin-when-cross-origin";
add_header X-XSS-Protection " 1; mode=block";
add_header Content-Security-Policy "script-src 'unsafe-inline' 'self' 'unsafe-eval'";
index index.php;
charset utf-8;
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
location = /favicon.ico { access_log off; log_not_found off; }
location = /robots.txt  { access_log off; log_not_found off; }
location =  /index/ { access_log off; log_not_found off;}
```


#### 安装项目一
```bash
cd /www/wwwroot/项目目录
chsh -s /bin/bash www
su www
composer install 
cp .env.example .env 
```
#### 编辑.env配置
```bash
APP_NAME=“项目名称”
APP_ENV=production
APP_DEBUG=false
APP_URL= "域名"
APP_IP =  'ip'
DB_PORT= '数据端口 默认3306'
DB_DATABASE='数据库名称'
DB_USERNAME='数据库用户'
DB_PASSWORD='数据库密码'
URL_SUFIX = '比如 PMJbsLbMmmRpJXyp'
```
#### 安装项目二
```bash
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
composer install --optimize-autoloader --no-dev 
php artisan config:cache
php artisan route:cache
php artisan view:cache
```


## 后台

<ul>
<li>链接 - http://ip:端口/PMJbsLbMmmRpJXyp</li>
<li>账号 - admin 密码 123456</li>
</ul>



## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
