[![Build Status](https://travis-ci.org/ptphp/ptphp.svg)](https://travis-ci.org/ptphp/ptphp)
[![Latest Stable Version](https://poser.pugx.org/ptphp/ptphp/v/stable.png)](https://packagist.org/packages/ptphp/ptphp)


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
    ├── src                     # 源码
    │   └── PtPHP               # PtPHP
    ├── tests                   # 测试代码
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
    cp app/config/app.dist.json app/config/app.json
    #webpack config
    cp webpack.config.dist.js webpack.config.js 
    
    #系统环境(虚拟主机专用)
    #cp app/config/.env.dist.php app/config/.env.php 
    
    
# 运行
    
    npm run start
    npm run php

# open in browser

    http://127.0.0.1:3080
    
# 编译

    npm run build