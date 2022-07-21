Router 路由器
====
   当前Application实例的Router实例  
```   
   位于MVC流程的开始位置   
   $application->run();   
   ↓   
   引导 Bootstrap   
   ↓   
   路由 Route   
   ↓   
   派发 Dispatch;   
```

### Router的实例获取
```php

// 对Router的操作，仅在派发动作前有效，如引导事件等
use Tiny\MVC\Bootstrap\Bootstrap as Base;
class Bootstrap extends Base
/**
* @autowired 自动注入
*/
protected Router $router;

/**
* 参数注入
*/
public function __construct(Router $router)
{
  ...
}

/**
* @autowired 自动注入
*/
public function addRoute(Router $router) 
{
  ...
}

// 也可通过别名调用
public function getRouterByAlias(ContainerInterface $container)
{
   return $container->get('app.router');
}
```

Lang 的使用
----

```php
// 通过.分隔子节点
// lang/default.php  "hello %s!"
echo $lang->translate('default.a.b', "tinyphp");
// output "hello tinyphp!"

// 可用数组形式调用 但无法替换里面的%s%d等字符参数
$lang['default.a.b'];
// output "hello %s!"

// 获取配置所有数据
$lang->getData();

```
#### 注意： Lang的配置数据无法动态修改


Router 在Application内的实例化
----

通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。

```php
namespace Tiny\MVC\Application;
   
 class ApplicationProvider implements DefinitionProviderInterface
 {
    /**
     * 获取路由的容器定义
     *
     * @return void|\Tiny\DI\Definition\CallableDefinition
     */
    protected function getRouterDefinition()
    {
        // profile.php router.enabled 开启路由实例化
        if (!$this->properties['router.enabled']) {
            return;
        }
        
        return new CallableDefinition(Router::class, function (Request $request, array $config) {
           
            // router config
            $config['routes'] = (array)$config['routers'];
            $config['rules'] = (array)$config['rules'];
            
            // 创建
            $routerInstance = new Router($request);
            
            // 注册新的路由
            foreach ($config['routes'] as $routerName => $routerclass) {
                $routerInstance->addRoute($routerName, $routerclass);
            }
            
            // 注册新的路由规则
            foreach ($config['rules'] as $rule) {
                $rule = (array)$rule;
                $routerInstance->addRouteRule($rule);
            }
            return $routerInstance;
        }, ['config' => $this->properties['router']]);
    }
```

profile.php 配置
----
```php
/**
 * Application的路由设置
 * 
 * router.enabled 开启路由
 *      true 开启 | false 关闭
 * 
 * router.routes 注册自定义的route 
 *      [
 *          routeid => route classname
 *      ]
 *      
 *  router.rules 注册的路由规则    
 *      [
 *          route = pathinfo [
 *              rule => [ext => 扩展名, domain => 适配域名]
 *          ]
 *          route = regex [
 *              rule => [regex => 匹配正则, keys => [匹配正则后替换的键值映射表，$1-9即regex匹配数组的索引值]]
 *          ]
 *      ]
 */
$profile['router']['enabled'] = true;  // 是否开启router
$profile['router']['routes'] = [];     // 注册自定义的route
$profile['router']['rules'] = [
    ['route' => 'pathinfo', 'rule' => ['ext' => '.html' , 'domain' => '*']],
];
```

### 具体参考可见   
[Router/路由器:Tiny\MVC\Router](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/lib/mvc/router.md)
