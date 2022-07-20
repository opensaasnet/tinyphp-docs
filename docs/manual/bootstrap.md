Bootstrap
====

Application流程内的引导事件实现
----

引导类是通过继承Tiny\MVC\ApplicationBase的Applicatioan实例初始化，$app->run()执行MVC流程的第一个动作。   
```php
namespace Tiny\MVC;

class ApplicationBase
{
    ...
    
    public function run()
    {
        $this->bootstrap();
        ...
    }
    
    public function bootstrap()
    {
        // 判断是否进行引导类加载和触发引导事件
        if (!$this->properties['bootstrap.enabled']) {
            return;
        }
        
        // 获取配置的引导类class
        $eventListener = $this->properties['bootstrap.event_listener'];
        if (!is_string($eventListener) && !is_array($eventListener)) {
            throw new ApplicationException('properties.bootstrap.eventListeners must be an array type or a string type class name');}
        
        // 加入监听onBootstrap事件
        $this->eventManager->addEventListener($eventListener);
        
        // 触发onBootstrap事件
        $this->eventManager->triggerEvent(new MvcEvent(MvcEvent::EVENT_BOOTSTRAP));
    }
   
}
```
> 标准库参考  [Tiny\Event:事件库](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/event.md)   [Tiny\MVC\Event:MVC事件](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lib/mvc/event.md)


profile.php内的配置
----
 bootstrap.enabled = true|false 选择关闭或开启引导类。   
 bootstrap.event_listener  = class 可更改为自定义的实现了MvcEvent::EVENT_BOOTSTRAP监听事件的引导类     
 也可通过profile.php的event.listeners[] = class配置添加。 
```php
/**
 * Application引导类
 * 
 * 通过监听引导事件触发
 * 
 * bootstrap.enabled 
 *      开启引导
 * 
 * bootstrap.event_listener
 *      string 实现Bootstrapevent_listener的类名
 *      array [实现Bootstrapevent_listener的类名]
 *      
 */
$profile['bootstrap']['enabled'] = true;
$profile['bootstrap']['event_listener'] = \App\Event\Bootstrap::class;
```


引导类的实现
----

* 
* 自定义Bootstrap类，需要继承基类Tiny\MVC\Bootstrap\Bootstrap; 该基类实现了事件监听接口Tiny\MVC\Event\BootstrapEventListenerInterface;   
* 触发MvcEvent::EVENT_BOOTSTRAP事件时，Tiny\MVC\Bootstrap\Bootstrap会调用成员函数onbootstrap进行事件处理, 其流程为：   
    * 寻找所有前缀带有init的成员函数，通过容器自动注入并执行。    
    * 支持@autowired 自动加载类，自动注解注入成员属性，执行其成员函数。   

```php
namespace App\Event;

use Tiny\MVC\Bootstrap\Bootstrap as Base

/**
* 自定义引导类
*
* @autowired 自动注解可自动加载入容器
*/
class Bootstrap extend Base
{
    
    // 触发onBootstrap引导事件时，自动执行init前缀的成员函数
    public function initAutoloader()
    {
        // 引入其他库
        $path = TINY_ROOT_PATH . 'lib/tinyphporg';
        $namespace = 'tinyphporg';
        $runtime = \Tiny\Runtime\Runtime::getInstance();
        $runtime->import($path, $namespace);  
    }
    
    // 重设properties的节点data设置
    public function initConfig()
    {
        $data = [];
        $this->application->properties->set('data',$data);
    }
    
    // 注册插件
    
    
    //注册视图引擎
    
    //注册路由
    
    ...
}
```

#### namespace Tiny.MVC.Bootstrap

