ExceptionHandler 异常处理
====

* ExceptionHandler 通过Runtime实例化。
* 在构造函数内，注入事件管理器 `EventManager`, 并通过`set_exception_handler` 和`set_error_handler`注册异常和错误处理句柄。
* 
```php
    /**
     * 初始化异常捕获句柄
     *
     * @param EventManager $eventManager 事件管理器
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
        
        // @formatter:off
        set_exception_handler([$this, 'onException']);
        set_error_handler([$this, 'onError']);
        // @formatter:on
    }
```

* 通过EventManager::addListener注册实现ExceptionEventListener接口的实例，为委托异常处理句柄
```php
abstract class ApplicationBase implements ExceptionEventListener
{
    public function __construct(ContainerInterface $container, $path, $profile = null)
    {
        ...
        $this->eventManager->addEventListener($this);
    }
    
    ...
}
```

* 当异常发生时，通过EventManager::trigget(Event::EVENT_ONEXCEPTION) 触发异常事件;  
```php
        // 触发onexception事件
        $event = new Event(Event::EVENT_ONEXCEPTION, [
            'exception' => $exception,
            'exceptions' => $this->exceptions
        ]);
        
        $this->eventManager->triggerEvent($event);   
```



* ApplicationBase的异常处理成员函数OnException
```php
    /**
     * 异常触发事件
     *
     * @param array $exception 异常
     * @param array $exceptions 所有异常
     */
    public function onException(array $exception, array $exceptions)
    {       
            // 配置异常通过日志方式输出
            
            if ($this->properties['exception.log']) {
                $logId = $this->properties['exception.logid'];
                $logMsg = $exception['handle'] . ':' . $exception['message'] . ' from ' . $exception['file'] . ' on line ' . $exception['line'];
                $this->error($logId, $exception['level'], $logMsg);
            }
            
            // 如果是需要抛出的异常级别，则直接输出
            if ($exception['isThrow']) { 
                // 在response没有实例化前，直接输出。否则通过debug模块输出异常信息
                if (!$this->response) {
                    print_r($exceptions);
                }
                
                // 终止执行
                $this->end();
               
            }
    }
```



具体参考可见 [Tiny\Runtime/运行时环境](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/runtime.md)
