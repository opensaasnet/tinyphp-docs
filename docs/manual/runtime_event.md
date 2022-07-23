EventManager 事件管理
====

事件管理器EventManager 在Runtime内实例化，一个完整的事件管理器包括三个部分：
* 管理器EventManager，维护所有事件监听器的添加，和事件触发。   
* 事件监听器 EventListener,定义所有触发事件的委托函数。   
* 事件Event, 定义该事件触发对应的事件监听器EventListener。   

* EventManger主要由两个关键的成员函数构成,`addListener`,`triggerEvent`;
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

* EventListener 主要是对事件委托的函数定义，所有事件监听器均需继承EventListenerInterface接口。
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

