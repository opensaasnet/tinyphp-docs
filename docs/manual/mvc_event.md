MvcEvent MVC事件处理
====

> EventManager 在Runtime被实例化，并作为tinyphp事件驱动的核心管理者。

具体参考见 [Tiny\Event/事件管理](https://github.com/tinyphporg/tinyphp-docs/docs/lib/event.md)    
[Runtime事件管理](https://github.com/tinyphporg/tinyphp-docs/docs/manual/runtime_event.md)

* MvcEvent的事件定义
```php
    /**
     * 引导事件
     *
     * @var string
     */
    const EVENT_BOOTSTRAP = BootstrapEventListenerInterface::class;
    
    /**
     * 路由初始化事件
     *
     * @var string
     */
    const EVENT_ROUTER_STARTUP = RouteEventListenerInterface::class . '.onRouterStartup';
    
    /**
     * 路由结束事件
     *
     * @var string
     */
    const EVENT_ROUTER_SHUTDOWN = RouteEventListenerInterface::class . '.onRouterShutdown';
    
    /**
     * 派发前事件
     *
     * @var string
     */
    const EVENT_PRE_DISPATCH = DispatchEventListenerInterface::class . '.onPreDispatch';
    
    /**
     * 派发后事件
     *
     * @var string
     */
    const EVENT_POST_DISPATCH = DispatchEventListenerInterface::class . '.onPostDispatch';
    
    /**
     * 请求开始事件
     *
     * @var string
     */
    const EVENT_BEGIN_REQUEST = RequestEventListenerInterface::class . '.onBeginRequest';
    
    /**
     * 请求结束事件
     *
     * @var string
     */
    const EVENT_END_REQUEST = RequestEventListenerInterface::class . '.onEndRequest';
}
```
具体参考 [Application的MVC完整流程](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/application.md)


Application::onException 异常处理事件
----
> 在MVC流程内，Application会作为实现了Tiny\Event\ExceptionListener异常监听接口的句柄，被Runtime\ExceptionHandler调用处理异常。   


如何自定义事件监听
----

具体参考见 [Tiny\Event/事件管理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/event.md)  
[Tiny\Event/运行时的事件管理](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/runtime_event.md)  
