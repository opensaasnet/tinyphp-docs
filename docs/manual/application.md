Application
====

1.1 Application特性
----

### Application实例基于Runtime运行时环境创建

Application在Tiny\Runtime\Runtime唯一实例上创建和销毁，同时管理整个MVC流程各个功能组件的按需加载。 
* Application的构建顺序是: `Tiny\Runtime` →  `Tiny\ApplicationBase` → `Tiny\MVC\WebApplication|Tiny\MVC\ConsoleApplication`;
*  Tiny\Runtime会根据运行环境自动创建`WebApplication` 或`ConsoleApplication`的实例   
    * `RpcApplication`是`WebApplication`的子类，做为RPC分布式服务的服务端提供对外服务。     
    * `WebApplication`的生命周期服从PHP-FPM的FastCGI协议, 即在用户每次访问的开始/结束会创建/销毁`WebApplication`实例。  
    * `ConsoleApplication`持续整个应用程序的生命周期，除非完成执行或主动中断。    

### profile.php 作为当前Application的Properties实例的配置文件。

1.2 Application的实例化
----

### ApplicationBase
> 具体参考可见 [Tiny/MVC/Application](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc.md)
### WebApplication 
> 具体参考可见 [Tiny/MVC/WebApplication](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc.md)
### ConsoleApplication
> 具体参考可见 [Tiny/MVC/ConsoleApplication](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/lib/mvc.md)

1.3 Application的MVC整体流程
----
Application中贯穿着整个应用的完整MVC流程实现，   
Application的生命周期由MVC流程和事件两部分组成。
```
Tiny\Runtime → createAppliation → Tiny\MVC\ApplicationBase::__construct;
创建 Properties 基于profile.php
↓   
创建: Request   
↓   
创建: Response   
↓   
事件: MvcEvent::EVENT_BEGIN_REQUEST   
↓   
运行: Application::run(); 
↓
引导: Application::bootstrap();
↓
事件: MvcEvent::EVENT_BOOTSTRAP
↓
路由: Application::route();
↓
事件: MvcEvent::EVENT_ROUTER_STARTUP
↓
事件: MvcEvent::EVENT_ROUTER_SHUTDOWN
↓
事件: MvcEvent::EVENT_PRE_DISPATCH;
↓
派发 Application::dispatch();
↓
事件: MvcEvent::EVENT_POST_DISPATCH;
↓
事件: MvcEvent::EVENT_END_REQUEST
↓
结束 Response::output() → Response::end();
```

具体参考可见
[Tiny/MVC/Request](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc_request.md)   
 [Tiny/MVC/Response](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc_response.md)  
 [Tiny/MVC/Bootstrap](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc_bootstrap.md)  
[Tiny/MVC/Router](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc_router.md)  
 [Tiny/MVC/Dispatch](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc_dispatch.md)  
 [Tiny/MVC/Controller](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc_controller.md)    
 [Tiny/MVC/Model](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc_model.md)   
 [Tiny/MVC/Viewer](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc_viewer.md)   

1.4 Application 的目录规划原则
----
* 功能文件夹一律小写,_分隔，并以复数形式规范命名。   
* 存放类文件的命名空间文件夹一律依照命名规范创建。   
* 需要写权限的文件一律放置于runtime下，仅在runtime设置写权限。   
