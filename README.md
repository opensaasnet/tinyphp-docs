Tinyphp-framework
====

> 本框架的编码规范基本遵守PSR规范标准，仅少数细节做灵活调整。

* [运行环境](#运行环境)
    * [开发环境部署，docker](#docker)
    * [生产环境部署，CentOS7X.X86_64](#centos)
 
* [Demo:http://github.com/tinyphporg/tinyphp.git](http://github.com/tinyphporg/tinyphp.git)    
    * [tinyphp](#tinyphp) 
* [中文文档:https://github.com/tinyphporg/tinyphp-docs.git](https://github.com/tinyphporg/tinyphp-docs)   
  * [语言基础规范](https://github.com/tinyphporg/tinyphp-docs/tree/master/docs/coding)
  * [SQL设计规范](https://github.com/tinyphporg/tinyphp-docs/tree/master/docs/sql)
  * [团队编码规范](https://github.com/tinyphporg/tinyphp-docs/tree/master/docs/team)

* [框架使用手册](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/) 
    * [Index/入口文件:    demo/public/index.php](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/index-001.md)
    * [Application/应用: demo/application/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/application-002.md)    
    * [Proptrites/应用配置:  demo/application/config/profile.php](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/profile-003.md)
        * [Debug/调试模式](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/debug-004.md)
        * [Bootstrap/引导程序:demo/application/libs/common/Bootstrap.php](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/bootstrap-005.md)
        * [Lang/语言包:demo/application/lang](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lang-006.md)
        * [Data/数据源](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/data-007.md)
        * [Cache/缓存:demo/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/cache-008.md)
        * [Router/路由器](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/router-009.md)
        * [Logger/日志收集:demo/application/runtime/log](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/logger-010.md)
        * [Dispatcher/派发器](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/dispatcher-011.md)
        * [Configuration/配置类](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/configuration-012.md)
        * [Builder/打包单一可执行文件](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/builder-013.md)
        * [Daemon/守护进程](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/daemon-014.md)
        * [Filter/过滤器](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/filter-015.md)
        * [Plugin/插件](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/plugin-016.md)
    * [Controller/控制器:demo/application/controllers/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/controller-017.md)
    * [Model/模型:demo/application/models](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/model-018.md)
    * [Viewer/视图:demo/application/views](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/viewer-019.md)
    
* [框架标准库参考](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/)
    * [Tiny：工具包](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/tiny.md)
    * [Tiny\Runtime：运行时](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/runtime.md)
    * [Tiny\Build：打包](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/build.md)
    * [Tiny\Cache：缓存](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/cache.md)
    * [Tiny\Config：配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/config.md)
    * [Tiny\Console：命令行](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/console.md)
    * [Tiny\Data：数据层](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/data.md)
    * [Tiny\Filter：过滤器](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/filter.md)   
    * [Tiny\Image：图片处理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/image.md)
    * [Tiny\Lang：语言包](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/lang.md)
    * [Tiny\Log：日志处理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/log.md)
    * [Tiny\MVC：MVC](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/mvc.md)
    * [Tiny\Net：网络](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/net.md)
    * [Tiny\String：字符处理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/string.md) 
   
                     


