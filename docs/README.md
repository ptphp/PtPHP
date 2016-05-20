PtReact
=======

# 网址:

- [开发域名: mission.dev.solardian.com](http://mission.dev.solardian.com/)

# 目录结构

    project
    ├── README.md
    ├── app                     # 后台源码
    │   ├── common              # 常用代码库
    │   ├── config              # 网站后端配置
    │   ├── src                 # PHP主要源代码  
    │   │   ├── Controlelr      # 控制器  
    │   │   ├── Model           # 模型 
    │   │   ├── Models          # 未迁移模型,会合并到 Model,没有使用 namespace
    │   │   └── View            # 视图 
    │   ├── api.php             # 接口初始化代码  
    │   ├── ptphp.php           # Cli初始化代码
    │   └── init.php            # 后台初始化代码    
    ├── bin                     # 命令行执行代码
    │   └── deploy.php          # 部署脚本   
    ├── etc                     # 服务器配置
    │   ├── nginx               # Nginx配置
    │   └── supervisor          # 守护进程配置
    ├── logs                    # 日志
    ├── docs                    # 文档说明
    ├── src                     # 前端源码
    │   ├── app                 # 前端项目模块  
    │   │   ├── manage          # 后台管理  
    │   │   │   ├── components  # 组件 
    │   │   │   ├── containers  # 容器 
    │   │   │   ├── stores      # stores 
    │   │   │   ├── entry.jsx   # 入口文件  
    │   │   │   └── routers.jsx # 路由 
    │   │   └── mission         # 任务 
    │   ├── components          # 通用组件  
    │   ├── config              # 前端配置 
    │   ├── libs                # 库 
    │   │   └── weui            # weui库  
    │   ├── style               # 样式 
    │   │   ├── base            # weui less 引入文件 
    │   │   │   └── fn.less     # fn less 引入文件 
    │   │   └── weui.less       # weui less 引入文件 
    │   ├── template            # 模板 
    │   └── utils               # Utils
    ├── test                    # 测试代码
    ├── webroot                 # 网站根目录
    ├── package.json            # package.json
    ├── composer.json           # composer.json
    ├── phinx.yml               # 数据库配置
    └── webpack.config.js       # webpack配置文件

# 安装
    
    npm install webpack-dev-server webpack -g -d
    cp package.dist.json package.json
    npm install -d
    npm run fix_antd
    cp composer.dist.json composer.json
    composer config repo.packagist composer https://packagist.phpcomposer.com
    composer clearcache
    composer install
    composer require ptphp/ptphp

# 配置
    
    #数据库
    cp phinx.dist.yml phinx.yml
    
    #系统配置
    cp app/config/env/development.dist.php app/config/env/development.php
    
    #前段配置
    cp src/config/app.dist.json src/config/app.json
    
    #系统环境(虚拟主机专用)
    cp app/config/.env.dist.php app/config/.env.php 

# 数据迁移

    vendor/bin/phinx status
    vendor/bin/phinx migrate  
    
# 数据seed

    vendor/bin/phinx seed:create Mission
    vendor/bin/phinx seed:run
    vendor/bin/phinx seed:run -s Mission

# hosts:

开发环境:

    sudo vim /etc/hots
    127.0.0.1 mission.dev.solardian.com

    
# nginx 参考配置

    
    server {
        listen 8077;
        #autoindex on;
        root /alidata/www/ptreact/webroot;
        index index.php index.html;
        server_name mission.solardian.com;
        charset utf-8;
    
        location = /favicon.ico {
            log_not_found off;
            access_log off;
        }
        location = /robots.txt {
            allow all;
            log_not_found off;
            access_log off;
        }
    
        location ~ \.php$ {
            try_files $uri =404;
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            #fastcgi_pass unix:/var/run/php5-fpm.sock;
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param APPLICATION_ENV "production";
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }
    
        #access_log  /tmp/access_mission.log;
    	#error_log   /tmp/error_mission.log;
    	location ~ /\.ht {
    		deny all;
    	}
    }

重启NGINX
    
    sudo nginx -s reload

# 测试

    NODE_ENV=testing webpack-dev-server --hot --inline --progress --colors --content-base ./src/template/ --port 3000 --host 123.57.74.34

# Usage

## Dependencies

* You need node.js v4+ installed globally on your machine. If using OS X, best to install node using [Homebrew](http://brew.sh/). Node v0.12 might work too, but I don't test it anymore.
* npm `dependencies` are required to build and run the app in production.
* npm `devDependencies` are additionally required to build and run the app in development.
* Core dependencies:  Webpack, Babel 6, React, react-transform-hmr.


# 运维部署 

- [测试](./docs/deploy/testing.md)
- [生产](./docs/deploy/production.md)

# 开发公众号

![开发公众号](http://mmbiz.qpic.cn/mmbiz/icxuASxSXKnjK1eMZXZOS2Xkvdf3yoLzJuuBhI1c9aCksBpJENDsGmntvPSibggZmQ0JHJs25u0jG1JuuAekTiaUQ/0)

# PHINX

[文档](http://docs.phinx.org/en/latest/)

# Resources

- [phinx](https://phinx.org/)
- [composer](http://docs.phpcomposer.com/)
- [html-webpack-template](https://github.com/jaketrent/html-webpack-template)
- [lodash](https://lodash.com/docs)
- [ant.design](http://ant.design/docs/react/introduce)
- [react](http://facebook.github.io/react/)
- [iconfont](http://iconfont.cn/)

# Author

[joseph@ptphp.com](mailto:joseph@ptphp.com)

### 文档

- [phinx](./docs/phinx.md)

### License

The MIT License([http://opensource.org/licenses/MIT](http://opensource.org/licenses/MIT))
