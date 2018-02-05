 学习一段时间的Laravel后写的一个小后台。
包含用户权限管理、简单的文章管理。

 github
https://github.com/iScript/YCms.git

## Requirements
- ** PHP : 5.6+
- ** Laravel : 5.2+
- ** Mysql
- ** Redis
## Installation
- git clone https://github.com/iScript/YCms.git
- cd YCms
- chmod -R 777 storage/
- composer install
- cp .env.example .env && vim .env
- php artisan migrate
- php artisan db:seed
- php artisan serve

- UserName: admin
- password: 123456