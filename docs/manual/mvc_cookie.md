Cookie
====

基于WebApplication的Cookie实例   
通过profile.php的cookie配置。

### Cookie的实例获取

`为了保持Model的无状态模式，禁止在模型层调用Cookie;`

```php

// 支持参数注入和自动注解
// controller

/**
* @autowired 自动注入
*/
protected HttpCookie $lang;

/**
* 参数注入
*/
public function __construct(HttpCookie $cookie)
{
  ...
}

/**
* @autowired 自动注入
*/
public function cookie(HttpCookie $cookie) 
{
  ...
}

// 也可通过别名调用
public function getCookieByAlias(ContainerInterface $container)
{
   return $container->get('app.cookie');
}
```

### Cookie 的使用


```php
// get
// COOKIE="name=tinyphp"
echo $cookie['name'];
// output "tinyphp";

// set
$cookie['name'] = "tinyphp1";
echo $cookie['name'];
// output "tinyphp1";

// 复杂的使用方式
$cookie->set("name", "tinyphp", "tinyphp.org");
```


### Cookie 在Application内的实例化


通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。

```php
namespace Tiny\MVC\Application;
   
 class ApplicationProvider implements DefinitionProviderInterface
 {
    /**
     * 获取HttpCookie的实例定义
     *
     * @return \Tiny\DI\Definition\CallableDefinition
     */
    protected function getCookieDefinition()
    {
        // Web环境下才有Cookie
        if (!$this->app instanceof WebApplication) {
            return;
        }
        return new CallableDefinition(HttpCookie::class, function (Properties $prop) {
            $config = (array)$prop['cookie'];
           
            // 引入全局变量$_COOKIE进行初始化。
            $config['data'] = $_COOKIE;
            return new HttpCookie($config);
        });
    }
}
```

### Cookie在profile.php内的配置项

```php
/**
 * HTTP COOKIE设置
 * 
 * 仅在web环境下生效
 * 
 * cookie.domain 
 *      默认的cookie生效域名
 * 
 * cookie.path 
 *      默认的cookie生效路径
 *      
 * cookie.expires
 *      默认的cookie过期时间
 *      
 *  cookie.prefix
 *      默认的cookie前缀
 *      
 *  cookie.encode
 *      cookie是否编码             
 */
$profile['cookie']['domain'] = '';
$profile['cookie']['path'] = '/';
$profile['cookie']['expires'] = 3600;
$profile['cookie']['prefix'] = '';
$profile['cookie']['encode'] = false;
```

具体参考可见   
-----
-----

[Web/HttpCookie:Tiny\MVC/Web/Cookie](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/mvc.md)

