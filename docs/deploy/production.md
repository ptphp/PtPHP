# 部署文档：

## 新建一个数据库

    lvdiantong_misssion

##  绿电通 Nginx 参考配置 ,

    server{
        ...
        location ~ \.php$ {
            ...
            fastcgi_param APPLICATION_ENV "production";
            ...
        }
        ...
    }


## 初始化环境命令

    git clone git@123.56.233.100:lvdiantong/ldt_mission.git
    cd ldt_mission
    chmod -R 777 ./logs
    cp package.dist.json package.json
    npm install -d
    npm install webpack -g -d
    npm run fix_antd
    cp phinx.dist.yml phinx.yml
    
    vim phinx.yml  
    #修改生产环境数据库信息
        #>    production:
        #>        adapter: mysql
        #>        host: localhost            <===生产环境 MYSQL主机地址
        #>        name: lvdiantong_misssion
        #>        table_prefix: ldt_
        #>        user: root                 <===生产环境 MYSQL用户名 
        #>        pass: 'root'               <===生产环境 MYSQL密码
        #>        port: 3306                 <===生产环境 MYSQL端口号
        #>        charset: utf8
        
    cp src/config/app.dist.json src/config/app.json
    npm run build
    cp app/config/env/production.dist.php app/config/env/production.php
    
    cp composer.dist.json composer.json
    composer config repo.packagist composer https://packagist.phpcomposer.com
    composer clearcache
    composer install
    composer require ptphp/ptphp
    composer dump-autoload --optimize
    
    vendor/bin/phinx migrate -e production
    vendor/bin/phinx status -e production
    
如果执行  `compser install` 时遇到 `The Process class relies on proc_open, which is not available on your PHP installation.` ,需修改 `php.ini` 选项 `disable_functions`

# 后续代码部署
    
    npm run build
    vendor/bin/phinx migrate -e production
    vendor/bin/phinx status -e production
    
