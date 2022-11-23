Debug配置
====

Debug仅在当前Application实例的生命周期中存在。
输出各种调试信息

### 在profile.php中开启

```php
$profile['debug']['enabled'] = true;      // 是否开启调试模式: bool FALSE 不开启 | bool TRUE 开启
$profile['timezone'] = 'PRC';             // 设置时区
$profile['charset'] = 'utf-8';            // 设置编码

#debug
$profile['debug']['event_listener'] = \Tiny\MVC\Event\DebugEventListener::class; // 通过注册监听事件 可通过此节点自定义新的debug插件
$profile['debug']['param_name'] = 'debug';     // 命令行下  通过--debug开启
$profile['debug']['cache']['enabled'] = true; // 是否在debug模式下启用应用缓存
$profile['debug']['console'] = false;   // web环境下 debug信息是否通过javascript的console.log输出在console
```

### 通过调用application实例开启/关闭


```php

// 全局调用
Tiny::currentApplication()->setDebug(true|false);

// 自动注解
/**
* @autowired
*/
protected Properties $properties

// 参数注入
public function indexAction(ApplicationBase $app) 
{
      $app->setdebug(true|false);
}

//控制器中
public function indexAction() 
{
    $this->setDebug(true|false);
}
```

### Debug的实现方式： 通过实现EventListener接口实现

通过触发 MvcEvent的onEndRequest()事件 开始处理调试数据，并注入到$app->response应用响应实例中去;

```php
// 在application.__construct中注册事件
$app = Tiny::currentApplication();
$debugEvenentListener = Tiny\MVC\Event\DebugEventListener::class;
$app->eventManager->addListener($debugEvenentListener);

// 触发 MvcEvent的onEndRequest()事件
$debugContent = ...;
$this->app->response->appendBody($debugContent);

```

具体参考库 
----
----

Tiny\MVC\Event\DebugEventListener
