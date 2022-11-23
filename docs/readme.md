TinyPHP
====

Tinyphp是基于 TinyPHP Framework 的脚手架。

### TinyPHP Framework
项目地址: [https://github.com/tinyphporg/tinyphp-framework](https://github.com/tinyphporg/tinyphp-framework)     
* 基于敏捷开发理念的PHP MVC框架;   
* 可应用高并发高性能的生产环境，经过10亿PV级别的生产实践检验;   
* 可基于TinyPHP-UI 快速开发响应式的H5/PC Web应用;   
* 支持分布式的RPC和微服务处理；      
* 支持Web/Console/RPC等运行环境，包括单一命令行文件打包，多任务的服务端守护进等。     

适用场景
----
* 客户端应用(IOS/Android/小程序的API接口开发：
    * 高性能，大并发。
    * 敏捷开发。
    * 支持分布式RPC微服务架构。   
    
*  H5/Web应用:
    * 响应式设计，提供各种成熟的UI组件和页面，极大减少UI和设计的人力投入。
    * 适用于PHP全栈工程师，及不具备UI设计师和前端工程师的研发团队。
    * 集成了tinyphp-ui前端框架，只需少量的JS前端代码。   

* 10人以下创业团队敏捷开发:
    * 敏捷开发
    * UI复用
    * 产品快速成型。   
    
* 100+人研发团队协作:
    * 支持RPC和微服务部署，适用于大规模的高性能高并发应用场景
    * 可将大型架构的多种后端语言需求有效控制为PHP一种后端开发语言，有效降低项目的人力维护成本和团队管理难度。    

快速开始
----
```shell
composer create-project tinyphporg/tinyphp

#console 运行
php public/index.php

#编译单文件
php public/index.php --build

#服务端守护进程
php public/index.php -d  //开启
php public/index.php --daemon=stop  //关闭

#配置文件 
application/config/profile.php
```
 
核心组件
----
* TinyPHP Framework v2.0 
    * 框架地址：   [https://github.com/tinyphporg/tinyphp-framework](https://github.com/tinyphporg/tinyphp-framework)    

* tinyphp-docs
    * 中文文档: 使用手册、标准库。  
    * 项目地址: [https://github.com//tinyphp-docs](https://github.com/tinyphporg/tinyphp-docs)   

* tinyphp-ui  
    * 前端UI组件库: webpack5+bootstrap5+jquery...     
    * 项目地址： : [https://github.com/tinyphporg/tinyphp-ui](https://github.com/tinyphporg/tinyphp-ui)  

* lnmp-utils   
    * Linux(CentOS7X_64) +openresty(nginx)+Mysql+PHP+Redis一键安装包, 适用于生产环境。    
    * 项目地址: https://github.com/tinyphporg/lnmp-utils

运行环境
----
----

### CentOS X64 7.4+
适应于生产环境，依赖于lnmp-utils。   
lnmp-utils: Linux(CentOS7X_64) +openresty(nginx)+Mysql+PHP+Redis一键安装包。    
项目地址: https://github.com/tinyphporg/lnmp-utils    

```shell
git clone https://github.com/tinyphporg/lnmp-utils.git
cd ./lnmp-utils
./install.sh -m tinyphp
```

### docker
 适应于开发环境

```shell
#可更改/data/workspace/tinyphp为自定义IDE工作目录
workspace=/data/workspace/

docker pull centos:7
docker run -d -p 80:80 -p 3306:3306 -p 8080:8080 -p 8989:8989 -p 10022:22 -v $workspace:/data/web  --name="tinyphp" --hostname="tinyphp" --restart=always -w /data/worksapce/ centos:7 /sbin/init

#port 8080 
#   用于tinyphporg/tinyphp-ui调试
# npm run dev

#port 8989 
#    用于tinyphporg/tinyphp-ui打包详情查看
# npm run build

docker exec -it tinyphp /bin/bash

git clone https://github.com/tinyphporg/lnmp-utils.git
cd ./lnmp-utils
./install.sh 

cd /data/web/tinyphporg/tinyphp
php public/index.php

```

中文手册
-----
本框架的编码规范遵守PSR规范标准，仅少数细节做灵活调整。       

* 快速开始
    * [环境搭建/lnmp-utils: http://github.com/tinyphporg/lnmp-utils.git](http://github.com/tinyphporg/lnmp-utils.git)
    * [Demo/tinyphp: http://github.com/tinyphporg/tinyphp.git](http://github.com/tinyphporg/tinyphp.git)
* 代码规范
    * [编码规范](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/standard/coding.md)   
    * [数据库操作规范](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/standard/db.md)
    * [团队协作规范](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/standard/team.md)
* 使用手册
   * [Index/入口文件:    public/index.php](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/index.md)      
   * [Runtime/运行时环境: runtime/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/runtime.md)    
      * [Environment/运行时环境参数](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/runtime_env.md)  
      * [ExceptionHandler/异常处理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/runtime_exception.md)   
      * [Autoloader/自动加载](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/runtime_autoloader.md)   
      * [Container/容器](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/runtime_container.md)   
      * [EventManager/事件管理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/runtime_event.md)  
    * [Application/应用程序: application/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/application.md)   
      * [Proptrites/Application配置文件:application/config/profile.php](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/profile.md)
      * [Debug/调试模式配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/debug.md)
      * [Bootstrap/引导程序配置:application/events/Bootstrap.php](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/bootstrap.md)
      * [Lang/语言包配置:application/lang](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lang.md)
      * [Data/数据源配置:/application/data](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/data.md)
      * [Cache/缓存配置:runtime/cache](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/cache.md)
      * [Logger/日志收集配置:runtime/log](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/logger.md)
      * [Configuration/配置类配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/configuration.md)
      * [Builder/打包单文件的配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/builder.md)
      * [Daemon/守护进程配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/daemon.md)
      * [Filter/过滤器配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/filter.md)
      * [MVC/Event/事件配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_event.md)
      * [MVC/Controller/控制器配置:application/controllers/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_controller.md)
      * [MVC/Model/模型配置:application/models/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_model.md)
      * [MVC/Viewer/视图配置:application/views/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_viewer.md)
      * [MVC/Router/路由器配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_router.md)
      * [MVC/Controller/Dispatcher/派发器配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_dispatcher.md)
      * [MVC/Request/请求](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_request.md)
      * [MVC/Response/响应](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_response.md)
      * [MVC/Web/HttpCookie](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_cookie.md)
      * [Mvc/Web/HttpSession](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc_session.md)            
    
* 框架标准库参考
    * [Tiny：工具包](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/tiny.md)
    * [Tiny\Runtime：运行时](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/runtime.md)
    * [Tiny\Build：打包](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/build.md)
    * [Tiny\Cache：缓存](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/cache.md)
    * [Tiny\Config：配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/config.md)
    * [Tiny\Console：命令行](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/console.md)
    * [Tiny\Data：数据层](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/data.md)
    * [Tiny\DI：依赖注入](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/di.md)
    * [Tiny\Event：事件](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/event.md)
    * [Tiny\Filter：过滤器](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/filter.md)   
    * [Tiny\Image：图片处理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/image.md)
    * [Tiny\Lang：语言包](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/lang.md)
    * [Tiny\Log：日志处理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/log.md)
    * [Tiny\MVC：MVC](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc.md)
    * [Tiny\Net：网络](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/net.md)
    * [Tiny\String：字符处理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/string.md) 
