## 简介
一个商城
## 运行环境
```
laradock
```

## 安装
```
git clone https://gitee.com/zero8coder/shop.git shop
```
## 配置 host
```angular2html
127.0.0.1 shop.test
```
## 配置 laradock 站点文件
nginx/sites/shop.conf
```angular2html
.
.
server_name shop.test;
root /var/www/shop/public;
.
.
```
## 配置 .env 文件
```angular2html
cp .env.example .env
```
`.env`文件
```
APP_URL=shop.test
.
.
DB_HOST=laradock_mysql_1
DB_PORT=3306
DB_DATABASE=shop
DB_USERNAME=root
DB_PASSWORD=root
```

## composer 安装包
```
composer install
```
## 生成key
```angular2html
php artisan key:generate
```
## 填充默认用户
```angular2html
php artisan db:seed --class=UsersTableSeeder
```
