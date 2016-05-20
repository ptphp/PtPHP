[![Build Status](https://travis-ci.org/ptphp/ptphp.svg)](https://travis-ci.org/ptphp/ptphp)
[![Latest Stable Version](https://poser.pugx.org/ptphp/ptphp/v/stable.png)](https://packagist.org/packages/ptphp/ptphp)


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
    ├── src                     # 源码
    │   └── PtPHP               # PtPHP
    ├── tests                   # 测试代码
    ├── webroot                 # 网站根目录
    ├── package.json            # package.json
    ├── composer.json           # composer.json
    ├── phinx.yml               # 数据库配置
    └── webpack.config.js       # webpack配置文件