
Session
====
   WebApplication下的Session实例   
   通过profile.php的session配置。
  
* 在Tiny\MVC\Web\Session的构造函数内，如果配置开启，则解析profile.php的session配置,并通过`ssession_set_save_handler`管理session句柄。   
```php
/**
 * 服务器临时变量实例
 *
 * @package Web
 * @since Sun Dec 18 22:53 40 CST 2011
 * @final Sun Dec 18 22:53 40 CST 2011
 */
class HttpSession implements \ArrayAccess, \Iterator,\Countable, SessionAdapterInterface
{
    /**
     * 设置Session句柄
     *
     * @param string $id 句柄ID标示
     * @return bool
     */
    public function __construct(array $config = [])
    {
        // parse config
        if ($config['enabled']) {
            $this->config = $this->parseConfig($config);
        }
        session_start();
    }
    
    /**
     * 解析配置
     * 
     * @param array $config
     */
    protected function parseConfig(array $config)
    {
        // SESSIONID的cookie配置
        $domain = (string)$config['domain'] ?: '';
        $expires = intval($config['expires']);
        $path = $config['path'] ?: '/';
        session_set_cookie_params($expires, $path, $domain);
        
        // 注册新的session驱动
        $adapters = (array)$config['adapters'];
        foreach($adapters as $adapterId => $adapterClass) {
            self::registerSessionAdpater($adapterId, $adapterClass);
        }
        
        // SESSION的适配器adpater配置
        $adapter = (string)$config['adapter'];
        if (!$config['adapter']) {
            throw new SessionException('Initialization failed, profile.session.adapter is required!');
        }
        
        // 适配器不存在则抛出异常
        if (!key_exists($adapter, self::$sessionAdapterMap)) {
            throw new SessionException(sprintf("Initialization failed, %s is not registered ", $adapter));
        }
        
        // 配置指定的SESSION适配器
        $config['class'] = self::$sessionAdapterMap[$adapter];
        session_set_save_handler($this, true);
        return $config;
    }
```

### Session的适配器
* Session默认为本地文件存储，为了提高SESSION的读写性能，可以将SESSION的存储路径配置为内存文件系统。
* 如果配置有分布式的负载均衡集群，需要共享全局SESSION，提高SESSION的读写性能，则需要使用SESSION适配器模式。
* SESSION适配器目前支持Redis和Memcached，通过配置profile.php的session.dataid和session.adapter 从DATA数据层获取操作实例。

* 注册Session适配器
```php
    /**
     * 注册session适配器
     *
     * @param string $adapterId 驱动ID
     * @param string $adapterClass 类名
     */
    public static function registerSessionAdapter($adapterId, $adapterClass)
    {
        if (key_exists($adapterId, self::$sessionAdapterMap)) {
            throw new SessionException('Failed to register the session adapter %s into the map: session id already exists!', $adapterClass);
        }
        self::$sessionAdapterMap[$adapterId] = $adapterClass;
    }
```

### Session的实例获取
#### 为了保持Model的无状态模式，禁止在模型层调用Session;

```php

// 支持参数注入和自动注解
// controller

/**
* @autowired 自动注入
*/
protected HttpSession $session;

/**
* 参数注入
*/
public function __construct(HttpSession $session)
{
  ...
}

/**
* @autowired 自动注入
*/
public function cookie(HttpSession $session) 
{
  ...
}

// 也可通过别名调用
public function getSessionByAlias(ContainerInterface $container)
{
   return $container->get('app.session');
}
```
Session 的使用
----

```php
// get
// SESSIONID="tinyphp"
echo $session['session'];
// output "tinyphp";

// set
$session['name'] = "tinyphp1";
echo $session['name'];
// output "tinyphp1";
```


Session 在Application内的实例化
----

通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。

```php
namespace Tiny\MVC\Application;
   
 class ApplicationProvider implements DefinitionProviderInterface
 {
    /**
     * 获取HttpSession 的实例定义
     *
     * @return \Tiny\DI\Definition\CallableDefinition
     */
    protected function getSessionDefinition()
    {
        // 只允许在WebApplication下实例化
        if (!$this->app instanceof WebApplication) {
            return;
        }
        // 引入profile.php下的session配置
        return new CallableDefinition(HttpSession::class, function (Properties $prop) {
            return new HttpSession((array)$prop['session']);
        });
    }
}
```

profile.php 配置
----
```php
/**
 * HTTP SESSION设置
 * 
 * 仅在WEB环境下有效
 * 
 * session.enabled 
 *      开启框架自动代理SESSION处理
 *      
 * session.domain 
 *      session cookie生效的域名设置     
 * 
 * session.path
 *      session cookie生效的路径设置
 *      
 *  session.expires 
 *      SESSION过期时间
 *  
 *  session.adapters 添加自定义的SESSION适配器
 *      adapterid 适配器ID
 *      adapterClass 实现了session适配器接口的自定义session adapter class
 *      
 *  session.adapter SESSION适配器
 *      redis 以datasource的redis实例作为session适配器
 *      memcache 以datasource的rmemcached实例作为session适配器
 *  
 *  session.dataid
 *      根据session.adapter选择对应的data资源实例
 * */ 

$profile['session']['enabled'] = true;
$profile['session']['domain'] = '';
$profile['session']['path'] = '/';
$profile['session']['expires'] = 36000;
$profile['session']['adapters'] = [];
$profile['session']['adapter'] = 'redis';
$profile['session']['dataid'] = 'redis_session';
```

### 可参考标准库   
[HttpSession:Tiny\MVC/Web/Session](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/mvc.md)

