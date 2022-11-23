EventManager 事件管理
====

事件管理器EventManager 在Runtime内实例化，一个完整的事件管理器包括三个部分：
* 管理器EventManager，维护所有事件监听器的添加，和事件触发。   
* 事件监听器 EventListener,定义所有触发事件的委托函数。   
* 事件Event, 定义该事件触发对应的事件监听器EventListener。   

### EventManger的实现

主要由两个关键的成员函数构成,`addListener`,`triggerEvent`

```php
class EventManager
{
    /**
     * 添加事件监听者并确定优先级
     *
     * @param string $listenerclass the classname of EventListenerInterface
     *        EventListenerInterface $listenerclass 监听接口实例
     * @param int $priority 事件触发的优先级
     */
    public function addEventListener($eventListener, int $priority = 0);
    
    /**
     * 触发事件
     *
     * @param EventInterface $event 事件实例
     */
    public function triggerEvent(EventInterface $event);
    
```

EventListener 主要是对事件委托的函数定义，所有事件监听器均需继承EventListenerInterface接口。

```php
/**
 * 异常事件监听句柄接口
 *
 * @package Tiny.Event
 * @since 2022年1月11日下午10:26:25
 * @final 2022年1月11日下午10:26:25
 */
interface ExceptionEventListener extends EventListenerInterface
{
    /**
     * 异常处理事件
     *
     * @param array $exception
     * @param array $exceptions
     */
    public function onException(array $exception, array $exceptions);
}
```

Event事件是一系列委托触发EventListenerInterface成员函数的集合，定义有两种方式。
* EventListener的类名，触发该事件，将会触发该EventListener所有接口的成员函数。
* EventListener的类名+ '.' + 该EventListener的指定成员函数名称，触发该事件，只会触发符合对应EventListener的指定成员函数名称。

```php
/**
 * 事件
 *
 * @package Tiny.Event
 * @since 2022年1月11日下午11:32:55
 * @final 2022年1月11日下午11:32:55
 */
class Event implements EventInterface
{
    
    /**
     * 错误事件
     *
     * @var
     */
    const EVENT_ONEXCEPTION = ExceptionEventListener::class;
    
     ...
}

/**
 * MVC事件
 *
 * @package Tiny.MVC
 * @since 2022年1月15日上午8:58:45
 * @final 2022年1月15日上午8:58:45
 */
class MvcEvent extends Event
{   
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
    
    ...
}
```

### 完整的事件管理流程。
 一个完整的事件管理流程，包括`事件的监听器添加`，`触发事件`，`事件处理`。

```php
// ApplicationBase 实现了ExceptionEventListener;
$app = $container->get(ApplicationBase::class);
$eventManager->addListener($app);

// 触发异常事件
$eventManager->triggerEvent(Event::EVENT_ONEXCEPTION);

// ApplicationBase
/**
 * app实例基类
 *
 * @author King
 * @package Tiny.MVC
 * @since 2013-3-21下午04:55:41
 * @final 2017-3-11下午04:55:41
 */
abstract class ApplicationBase implements ExceptionEventListener
{
    ...
    
    public function onException(Event $event, array $exception, array $exceptions)
    {
        ...
        // 阻止触发EventManager注册队列里的下一个异常处理。
        $event->stopPropagation(true);
    }
}
```

可参考标准库 
----
----

[运行时环境/Tiny\Runtime](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/runtime.md)
