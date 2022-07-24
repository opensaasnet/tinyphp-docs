Controller 控制器配置
==== 

MVC的控制流程主要包括: `请求Request`, `引导Bootstrap`, `路由Router`，`派发Dispatcher`，`执行Action`，`视图渲染Viewer`, `响应Response`.

Dispatch 派发
----

* Application派发的主要流程是从Request获取经过路由匹配的Module, Controller，并开始执行派发动作。   
    * 控制器的匹配主要通过路由匹配实现，否则使用默认的模块名，控制器名称和动作名。   
    * Action分为两种，名称后缀带Action的动作函数,及普通的成员函数。   
```php
class ApplicationBase
{
    /**
     * 派发
     *
     * @access protected
     * @param string $cname 控制器名称
     * @param string $aname 动作名称
     * @param array $args 参数
     * @param bool $isEvent 是否为成员函数本身
     * @return mixed
     */
    public function dispatch(string $cname = null, string $aname = null, string $mname = null, array $args = [], bool $isMethod = false)
    {
        $cname = $cname ?: $this->request->getControllerName();
        $aname = $aname ?: $this->request->getActionName();
        $mname =$mname ?: $this->request->getModuleName();
        return $this->getDispatcher()->dispatch($cname, $aname, $mname, $args, $isMethod);
    }
}

* 派发器的构建主要包括获取控制器的实例，并执行动作。   
    * 一个Action的执行包括三个动作,beginExceute, nameAction,endExceute;   
    * BegineExceute的执行结果如果是false,则不会执行Action和engExceute动作。  
```php
class Dispatcher
{
/**
     * 
     * @param ContainerInterface $container 当前容器实例
     * @param string $controllerNamespace 当前应用的默认控制器命名空间
     * @param string $actionSuffix 当前应用的默认动作后缀
     */
    public function __construct(ContainerInterface $container, string $controllerNamespace = '', string $actionSuffix = '');
    
    /**
     * 执行派发
     *
     * @param string $cname 控制器名称
     * @param string $aname 动作或者成员函数名
     * @param array $args 调用动作或成员函数的参数数组
     * @param bool $isMethod 是否为调用成员函数，true为成员函数,false为动作函数
     * @throws DispatcherException
     * @return void|boolean|mixed
     */
    public function dispatch(string $cname, string $aname, string $mname = null, array $args = [], bool $isMethod = false)
    {   
        try {
            $controllerClass = $this->getControllerClass($cname, $mname);
        } catch(DispatcherException $e) {
            throw $e;
        }
        ...
    }
    
    ...
}
```

