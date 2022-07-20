应用配置文件
====

在创建新的Application的实例时，通过引入配置文件profile.php，可配置管理整个应用组件和MVC流程。 

3.1 profile.php路径设置
----
profile路径通过public/index.php入口文件中的Tiny\Tiny::createApplication($path, $profile)设置
$path 即APPLICATION_PATH;   
$profile缺省配置为$path/profile.php;  
  
  
3.2 profile.php的实例化
----
参考类: Tiny\MVC\Application\Properties，继承自Tiny\Config\Configuration

```php
// 在applicationBase::__construct()内实例化。
$app->properties;  // 引用properties实例
```

### Controller的引用
```php

// App\Controller\Main
$this->application->properties;

// or
$this->properties;

// 自动注解
public function indexAction(Properties $properties)
{
    $properties;
}
```

### 其他

+ 在Model层原则上不允许引用

+ View层中 通过$app->properties引用

3.3 Debug 调试模式
----

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
> 具体参考 [Debug/调试模式](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/debug.md)

3.4 ExceptionHandler 异常处理句柄
----
> exception.eanbled = true开启框架的异常处理。   
> Debug模式开启的开发模式下，会输出异常的详细信息。   
> Debug模式关闭的生产环境下，会静默输出异常信息，通过开启日志记录异常信息。   
```php
/**
 * application的异常处理
 * 
 * exception.enabled 开启application的异常处理
 *      true 设置application实例为异常处理句柄，监听异常事件并处理
 *      false 通过runtime默认异常处理
 * 
 * exception.log 异常日志
 *      true 开启 异常输出到日志中
 *      false 关闭输出
 * 
 * exception.logid 默认的异常日志id 
 *      如果是文件存储，则保留在runtime/log文件夹下，以logid命名的日志文件中
 */
$profile['exception']['enabled'] = true;
$profile['exception']['log'] = true;
$profile['exception']['logid'] = 'tinyphp_exception';
```
> 具体参考 [Runtime.ExceptionHandler/异常句柄](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/exception.md)   

3.5 Boostrap 引导
---- 
> bootstrap.enabled = true|false 选择关闭或开启引导类。   
> bootstrap.event_listener  = class 可更改为自定义的实现了MvcEvent::EVENT_BOOTSTRAP监听事件的引导类  
  
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
> 具体可参考 [Bootstrap/引导程序:application/events/Bootstrap.php](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/bootstrap.md)   

3.6 Builder 单文件打包
----
> Builder是基于Phar扩展并以监听事件方式运行的打包器。   
> build.enabled = true|false 开启或关闭
> build.event_listener = class 可自定义实现打包的监听事件接口    
```php
/**
 * 打包器
 * 
 * 仅在命令行环境的ConsoleApplication实例生效
 * 
 * builder.enabled 是否开启单文件打包器
 *      true 开启  false 关闭监听
 *      
 * builder.param_name 参数名
 *      php public/index --build 即可开启单文件打包
 *      
 * builder.event_listener 打包器监听事件类
 *      监听到输入参数  --build，即开始初始化打包器
 *      
 * builder.path   打包器的配置文件夹 
 *      根据配置文件打包
 * 
 * builder.config_path 
 *      附加到单文件执行时的application的配置数据
 * 
 * builder.profile_path 
 *      附加到单文件执行时的application的propertis数据
 *            
 */
$profile['builder']['enabled'] = true;
$profile['builder']['param_name'] = 'build';
$profile['builder']['event_listener'] = \Tiny\MVC\Event\BuilderEventListener::class;
$profile['builder']['path'] = 'build/builder';
$profile['builder']['config_path'] = 'build/config';
$profile['builder']['profile_path'] = 'build/profile';
```
具体可参考 [Builder/打包单一可执行文件](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/builder.md)

3.7 Daemon守护进程
----

Daemon 是基于pcntl扩展实现的父子进程守护程序。
daemon.enabled = true|false 选择开启或关闭daemon的监听事件
daemon.event_listener = class 可更改为自定义的daemon守护进程配置类

```php
/**
 * 守护进程的基本设置
 * 
 * 仅在命令行环境的ConsoleApplication实例生效
 * 
 * daemon.enabled 
 *      是否开启自动监听Daemon的命令行参数监听
 * 
 * daemon.id 默认启动的服务ID
 *      id 即 daemon.policys数组里的key
 *      
 * daemon.event_listener Daemon事件监听器
 *      监听D命令行的-d --daemon参数 并实例化Daemon
 *    
 *  daemon.piddir 
 *      守护进程的PID保存目录
 *      
 *  daemon.tick 
 *      默认子进程退出后重建的间隔    
 *      
 *  daemon.daemons 配置服务数组
 *      daemonid => [
 *          workers,子进程配置
 *          options => 附加给当前服务实例的选项
 *      ]
 *      workers的配置: 【
 *          id => worker的身份标识
 *          type => worker worker类型，默认为限定循环执行的子进程模式
 *          dispatcher => [controller,action,module]代理执行worker进程的控制器,动作参数, 模块
 *          num => 子进程的数量
 *          options => [] 附加给worker实例的参数
 *              type = worker: 
 *                  options => [
 *                  runmax => 最大运行次数，避免内存占用过多系统阻塞
 *                  tick  => 重建子进程的间隔 
 *              ]
 *      】
 */
$profile['daemon']['enabled'] = true;
$profile['daemon']['id'] = 'tinyphp-daemon';
$profile['daemon']['event_listener'] = \Tiny\MVC\Event\DaemonEventListener::class;
$profile['daemon']['piddir'] = '{runtime}/pid/';
$profile['daemon']['tick'] = 2;
$profile['daemon']['daemons'] = [
    'tinyphp-daemon' => [
        'workers' => [
           ['id' => 'index', 'type' => 'worker' , 'dispatcher' => ['controller' => 'main', 'action' => 'index', "module" => ''], 'num' => 1, 'options' => ['runmax' => 1024, 'tick' => '10']],
           ['id' => 'test', 'worker' => 'worker' , 'dispatcher' => ['controller' => 'main', 'action' => 'test', 'module' => ''], 'num' => 1, 'options' => ['runmax' => 1024, 'tick' => '1']]
        ],
        'options' => [],
    ],
];
```
> 具体可参考 [Daemon/守护进程](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/daemon.md)。

3.8 Configuration 配置
----
> config.enabled = true|false 选择是否开启应用的配置实例。

```php
/**
 * 当前Application实例下的Configuration实例设置
 * 
 * config.enabled 是否开启配置
 *      true 开启 | false 关闭
 *  
 * config.path 配置文件的相对路径
 *      array [file|dir] 可配置多个路径
 *      string file      单个配置文件路径
 *      string dir       文件夹路径
 * 
 * config.cache.enabled 是否缓存配置
 *      开启缓存，将读取所有配置文件并解析后，缓存至本地PHP文件
 *      配置文件内严禁函数，类等命名和操作，否则缓存数据无法解析      
 * 
 */
$profile['config']['enabled'] = true;
$profile['config']['path'] = 'config/';
$profile['config']['cache']['enabled'] = true;
```
### 示例
```php
// 通过.分隔子节点
$config->get('default.a.b');

// 可用数组形式调用配置
$config['default.a.b'];

// 获取配置所有数据
$config->get();

// 动态更改配置节点
$config['default.a.b'] = 'tinyphp';
$config->set('default.a.b', 'tinyphp');
```
#### 注意： configuration可通过set方式更改配置节点数据，但并不会持久化保存。

具体可参考 [Configuration配置/应用配置类:application/config](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/configuration.md)

3.9 Lang配置
----
> lang.enabled = true|false 选择是否开启应用的配置实例。
   
```php
/**
 * Application的语言包设置
 * 
 * lang.enabled 开启语言包实例化
 *   
 * lang.locale 默认语言包
 *      zh_cn 中文语言包
 *  
 *  lang.path 存放语言包配置文件的路径
 *      路径配置同config
 *      
 * lang.cache.enabled 开启缓存
 *      开启将所有语言包数据缓存
 */
$profile['lang']['enabled'] = true;          // 是否开启
$profile['lang']['locale'] = 'zh_cn';        // 默认语言包
$profile['lang']['path'] = 'lang/';          // 存放语言包的目录
$profile['lang']['cache']['enabled'] = true; // 配置模块缓存设置 提高性能
```
> 具体可参考 [Lang/语言包配置:application/lang](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lang.md)

3.10 Logger配置
----
> log.enabled = true|false 可选择开启或关闭日志  

```php
/**
 * application的日志配置
 * 
 * log.enabled 开启日志处理
 * 
 * log.wirter 日志写入器
 *      file 写入到本地文件
 *      syslog 通过系统syslog函数写入到系统文件夹
 *      rsyslog 通过rsyslog协议，写入到远程文件夹
 */
$profile['log']['enabled'] = true;
$profile['log']['writer'] = 'file';    /*默认可以设置file|syslog 设置类型为file时，需要设置log.path为可写目录路径 */
$profile['log']['path'] = '{runtime}/log/';
```
> 具体可参考 [Logger/日志收集配置:runtime/log](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/logger.md)   

3.11 Data数据源管理
----
> Data管理所有的外部数据源   
> data.enabled  = true|false 是否开启数据池管理   
```php
/**
 * 数据资源池配置
 *  
 *  data.enabled 开启数据资源池
 *      true 开启|false 关闭
 
 *   data.default_id 默认ID
 *      默认调用datasource的ID
 *  
 *  data.drivers 驱动数组
 *  
 *  data.sources 数据资源池配置   
 *      driver = db.mysqli|db.pdo| [
 *          id => 调用时使用的ID字段
 *          host 通用的远程资源
 *          prot 通用的远程端口
 *          password 通用密码
 *          dbname 数据库名称
 *      ]
 *      
 *      driver = redis [
 *          id => 调用时使用的ID字段
 *          host => 远程host 单独设置的host & prot 会合并到servers内
 *          port => 远程端口
 *          db => 选择的DB Index
 *          servers => [[host => 服务, port => 端口]]  
 *      ]
 *      
 *      driver = memcached [
 *          servers => [[host=> 服务地址, port=> 端口]]
 *          persistent_id => 共享实例的ID
 *          options => [选项]
 *      ]
 */
$profile['data']['enabled'] = true;    /* 是否开启数据池 */
$profile['data']['default_id'] = 'default';
$profile['data']['drivers'] = [];
$profile['data']['sources'] = [
    ['id' => 'default', 'driver' => 'db.pdo', 'charset' => 'utf8mb4', 'host' => '127.0.0.1', 'port' => '3306', 'user' => 'root', 'password' => '123456', 'dbname' => 'mysql'],
    ['id' => 'redis', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379', 'db' => 0],
    ['id' => 'redis_cache', 'driver' => 'redis', 'servers' => [['host' => '127.0.0.1', 'port' => '6379']]],
	  ['id' => 'redis_session', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379'],
    ['id' => 'redis_queue', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379'],
    ['id' => 'memcached', 'driver' => 'memcached', 'servers' => [['host' => '127.0.0.1', 'port' => '11211']], 'persistent_id' => null, 'options' => []]
];
```
> 具体可参考 [Data/数据源配置](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/data.md)

3.12 Cache
----

> cache.enabled = true|false 是否开启应用的缓存实例。    
```php
/**
/**
 * Application的缓存设置
 * 
 * 支持的存储器类型
 *      file => Tiny\Cache\Storager\File 文件存储 
 *      memcached => Tiny\Cache\Storager\Memcached memcache存储
 *      php      => Tiny\Cache\Storager\PHP PHP文件序列化存储
 *      redis => Tiny\Cache\Storager\Redis  Redis存储
 *      SingleCache => Tiny\Cache\Storager\SingleCache 单文件存储 适合小数据快速缓存
 *      
 *  cache.enabled 开始缓存
 *      true 开启  | false 关闭
 * 
 * cache.ttl 默认的缓存过期时间
 *      ttl 可单独设置
 * 
 * cache.dir 默认的本地文件缓存路径
 *      string dir 只可设置为文件夹
 *      
 * cache.application_storager
 *      string 当前应用实例的缓存存储器
 *      
 * cache.default_id 默认的缓存资源ID
 *      $cache 将缓存实例当缓存调用时所调用的cacheID
 * 
 * cache.application 
 *      是否对application的lang container config等数据进行缓存
 * 
 * cache.storagers 缓存存储器的注册列表
 *      [
 *          key => value
 *          存储器ID => 存储器类全程
 *          'file' => \Tiny\Cache\File::class
 *      ]
 *      添加后，即可在cache.sources节点的storager引用
 *  
 *  cache.sources 缓存源
 *      本框架的远程缓存源通过datasource统一调度管理
 *      id => 调用缓存资源的ID
 *      storager = redis [
 *          options => [
 *              ttl => 默认过期时间
 *              dataid => 调用的data sources ID
 *          ]
 *      ]
 *      
 *      storager => memcached [
 *          options => [
 *              ttl => 默认的过期时间
 *              dataid => 调用的data source id
 *          ]
 *          
 *      ]
 *      
 *      storager => file [
 *          options =>
 *      ]
 */
$profile['cache']['enabled'] = true;
$profile['cache']['ttl'] = 3600;
$profile['cache']['dir'] = '{runtime}/cache/';
$profile['cache']['default_id'] = 'default';
$profile['cache']['storagers'] = [];
$profile['cache']['sources'] = [
   ['id' => 'default', 'storager' => 'redis', 'options' => ['ttl' => 3600, 'dataid' => 'redis_cache']],
    ['id' => 'memcached', 'storager' => 'memcached', 'options' => ['ttl' => 3600, 'dataid' => 'memcached']],
    ['id' => 'file', 'storager' => 'file', 'options' => ['ttl' => 3600, 'path' => '']],
   ['id' => 'php', 'storager' => 'php', 'options' => ['ttl' => 3600, 'path' => '']]
];

/**
 * 当前应用实例的缓存配置
 * 
 * cache.application_storager ApplicationCache调用的存储器类型
 *      默认为SingleCache 适合小数据的快速存储应用，php文件存储于opcache内存中，IO性能很好。
 *      
 * cache.application_ttl ApplicationCache的缓存过期时间
 *      int 60
 * 
 */
$profile['cache']['application_storager'] = SingleCache::class;
$profile['cache']['application_ttl'] = 60;
```
> 具体可参考 [Cache/缓存配置:runtime/cache](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/cache.md)

3.13 Session
----

> session.enabled  = true|false 是否开启当前应用的session实例。   
>   
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
$profile['session']['adapter'] = 'redis';
$profile['session']['dataid'] = 'redis';
```
> 具体可参考 [Session配置:Tiny/MVC/Web/Session](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc/session.md)

3.14 Filter过滤器设置
----
> filter.enabled  = true|false 是否开启当前应用的filter实例。    
```php
/**
 * application的过滤器配置
 * 
 * filter.enabled 开启过滤
 * 
 * filter.web WEB环境下的过滤器设置
 *      string classname 实现FilterInterface的过滤器
 *      array [filterInterface]
 * 
 * filter.console 命令行环境下的过滤器设置
 *      string classname 实现FilterInterface的过滤器
 *      array [filterInterface]
 * 
 * filter.filters 通用过滤器设置
 *      array [FilterInterface]
 */
$profile['filter']['enabled'] = true;
$profile['filter']['web'] = \Tiny\Filter\WebFilter::class;
$profile['filter']['console'] = \Tiny\Filter\ConsoleFilter::class;
$profile['filter']['filters'] = [];
```

3.15 Cookie
----
> Cookie的配置。。。 
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
> 具体可参考 [HttpCookie/Cookie配置:Tiny\MVC\Web\HttpCookie](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/mvc/cookie.md)

3.16 MVC的流程控制
----

> 路由配置

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
> Response 当前应用的响应实例配置。    
```php
/**
 * Application的响应实例配置
 *      
 * response.formatJsonConfigId     
 *    response格式化输出JSON 默认指定的语言包配置节点名
 *    status => $this->lang['status'];
 */
$profile['response']['formatJsonConfigId'] = 'status';

> 当前应用的控制器实例配置。    
/**
 * Application的控制器配置
 * 
 *  controller.namespace 相对Application命名空间的命名空间配置 
 *      default Controller Web环境下的控制器命名空间, 如App的命名空间为\App, 即\App\Controller
 *      console Console\Console 命令行下的相对控制器命名空间
 *      rpc    Controller\Rpc 
 *      
 *  controllr.src  
 *      控制器的源码加载目录
 *      
 *  controller.default  
 *      默认的控制器名称
 *      
 *  controller.param 
 *      默认的控制器参数
 *      
 * controller.action_default 
 *      默认的控制器动作名称
 * 
 * controller.action_param 
 *      默认的控制器动作参数              
 *          
 */
$profile['controller']['namespace']['default'] = 'Controller';
$profile['controller']['namespace']['console'] = 'Controller\Console';
$profile['controller']['namepsace']['rpc'] = 'Controller\RPC';
$profile['controller']['src'] = 'controller/';
$profile['controller']['default'] = 'main';
$profile['controller']['param'] = 'c';
$profile['controller']['action_default'] = 'index';
$profile['controller']['action_param'] = 'a';
```
> 模型层配置

```php
/**
 * Application的模型层设置
 * 
 * model.namespace 
 *      相对app.namespace下的模型层命名空间  如\App\Model
 *      
 * model.src  模型层的存放目录
 */
$profile['model']['namespace'] = 'Model';
$profile['model']['src'] = 'models/';
```
> 视图层控制。
>    
```php
/**
 * 视图设置
 * 
  *  默认模板解析的扩展名列表
 *      .php PHP原生引擎
 *      .tpl Smarty模板引擎
 *      .htm|.html Template模板引擎
 * 
 * view.src 
 *      视图模板存放的根目录
 *      example: application/views/
 *      
 * template_dirname
 *      视图模板目录下的默认存放子级目录
 *          example: views/default/
 * 
 * lang.enabled
 *      是否加载对应的语言包子级目录
 *      example: views/zh_cn/ 查找不到后，去默认模板目录里views/default/寻找
 *      
 * view.compile  
 *      视图模板编译后的存放目录
 * 
 * view.config 
 *      视图模板的配置存放目录
 * 
 * view.assign 
 *      视图模板的预先加载配置数组
 * 
 * view.engines 视图引擎配置
 *      engine => 视图模板解析类名
 *      ext => []  可解析的模板文件扩展名数组
 *      config => [] 引擎初始化时的配置
 *      
 *      Example: Template引擎的插件配置
 *          engine => \Tiny\MVC\View\Engine\Template:
 *          config => [plugins => [
 *              'plugin' => '\Tiny\MVC\View\Engine\Template\Url' , 'config' => []
 *      ]]
 *      
 * view.helper 视图助手配置
 *      helper => classname 助手类名
 *      config => [] 助手初始化时的配置
 *  
 *  view.cache.enabled 是否开启视图缓存
 *      默认不开启
 *  
 *  view.cache.dir 缓存目录
 *  view.cache.ttl 缓存过期时间
 */
$profile['view']['basedir'] = 'views/';
$profile['view']['theme'] = 'default';
$profile['view']['lang'] = true;     //自动加载语言包
$profile['view']['paths'] = [];
$profile['view']['compile'] = '{runtime}/view/compile/';
$profile['view']['config']  = '{runtime}/view/config/';
$profile['view']['assign'] = [];

// 引擎和助手配置
$profile['view']['engines'] = [];
$profile['view']['helpers'] = [];

/*
 * 视图的全局静态资源配置
 * 
 * view.static.basedir 视图静态资源的存储根目录
 *      {static} => $profile['src']['static']
 * 
 * view.static.public_path 视图静态资源的公开访问地址
 *      /static/ 当前域名下的绝对路径
 *      http://demo.com/static 可指定域名
 *      
 * view.static.engine 是否开启视图解析的模板引擎
 *      当前支持css js 图像文件的自动解析和生成
 *       
 * view.static.minsize 静态模板引擎复制文件的最小大小
 *      小于最小大小的，直接注入文件内容
 *      大于最小大小的，在staic目录下生成对应外部文件在html下加载
 *      
 * view.static.exts 
 *      view.static.engine支持解析的静态资源扩展名     
 *      
 */

$profile['view']['static']['basedir'] = '{static}';
$profile['view']['static']['public_path'] = '/static/';
$profile['view']['static']['engine'] = true;
$profile['view']['static']['minsize'] = 2048;
$profile['view']['static']['exts'] = ['css', 'js','png', 'jpg', 'gif'];

```
> 具体可参考 [Controller/控制器配置:application/controllers/](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/mvc/controller.md)   
> [Router/路由器配置](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/mvc/router.md)     
> [Dispatcher/派发器配置](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/mvc/dispatcher.md)   
> [Model/模型:application/models](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/mvc/model.md)   
> [Viewer/视图:demo/application/views](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/mvc/viewer.md)   
> [Event/MVC事件配置](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/mvc/event.md)   

3.17 application的路径管理和配置
----

> 路径设置   
> 每个src下的路径节点，都可以在路径中通过{node}表示并被替代。   
> {app} = APPLICATION_PATH   
> {public} = $profile['src']['public'];   
```php
/**
 * application的路径设置
 *
 *  {app} 默认为APPLICATION_PATH
 *  每个src.nodename可作为标签{nodename}按顺序在后续的路径中被自动替换
 *
 * src.path
 *      application的根路径
 *
 * src.public
 *      入口文件夹，存放静态文件和项目文件夹
 *
 * src.resources
 *      资源文件的存放目录 一般与application目录平行
 *
 * src.runtime
 *      运行时文件存放目录
 *
 * src.tmp
 *      运行时的临时文件夹
 *
 * src.global
 *      存放全局类的文件夹
 */
$profile['src']['path'] = '{app}';                    // application源码路径
$profile['src']['public'] = '{app}../public/';        // 入口文件夹
$profile['src']['static'] = '{public}static/';        // 静态资源文件夹
$profile['src']['resources'] = '{app}../resource/';   // 资源文件夹
$profile['src']['runtime'] = '{app}../runtime/';      // 运行时文件夹
$profile['src']['tmp'] = '{runtime}tmp/';             // 临时文件夹
$profile['src']['global'] = 'librarys/global/';           // 存放全局类的文件夹
$profile['src']['library'] = 'librarys/';          // 除了composer外，引入的其他项目的库文件夹
$profile['src']['controller'] = 'controllers/web/';   // web环境下的控制器类文件夹
$profile['src']['model'] = 'models/';                 // 模型类文件夹
$profile['src']['console'] = 'controllers/console/';  // 命令行环境下的控制器类文件夹
$profile['src']['rpc'] = 'controllers/rpc/';          // rpc模式下的控制器类文件夹
$profile['src']['view'] = 'views/';                   // 存放、、】视图模板的文件夹
$profile['src']['vendor'] = '{app}../vendor/';
$profile['src']['event'] = 'events/';
$profile['src']['common'] = 'librarys/common/';
```
> 需要进行路径处理的profile节点配置。    

```php
/**
 * 需要做路径处理的路径节点列表
 *      [propertis.nodename...]
 *      作为路径传递的配置节点名，在相对路径前添加application_path的绝对路径，并替换src里的标签,./,../,相对路径等。
 */
$profile['path'] = [
    'src.path',
    'src.public',
    'src.static',
    'src.runtime',
    'src.resources',
    'src.tmp',
    'src.vendor',
    'builder.path',
    'builder.profile_path',
    'builder.config_path',
    'config.path',
    'lang.path',
    'log.path',
    'cache.dir',
    'view.basedir',
    'view.cache.dir',
    'view.compile',
    'view.config',
    'view.path',
    'module.tinyphp-ui.template_dirname',
    'view.static.basedir',
    'src.library',
    'src.global',
    'src.controller',
    'src.console',
    'src.rpc',
    'src.model',
    'src.common',
    'src.event',
    'daemon.piddir',
    'daemon.logdir',
    'container.provider_path',
    'module.path',
];
```
> 具体可参考 [Tiny\MVC\Application\Properties/Properties配置:application/config/profile](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/properties.md)
3.18 application下的自动加载管理
----

```php
/**
 * 自动加载类配置
 * xc v≈Ω
 * autoloader.namespaces 命名空间加载配置
 *      namespace => properties.path.nodes 
 *      
 *  autoloader.classes 类文件的加载配置
 *      classname => propertis.path.node
 *      
 * autoloader.is_realpath  是否绝对路径加载
 *      true 绝对路径加载
 *      false propertis.path里的路径加载
 */
$profile['autoloader']['namespaces'] = [
        'App' => 'src.library',
		'App\Controller' => 'src.controller',
		'App\Controller\Console' => 'src.console',
		'App\Controller\Rpc' => 'src.rpc',
		'App\Model' => 'src.model',
        'App\Event' => 'src.event',
        'App\Common' => 'src.common',
		'*' => 'src.global',
];
$profile['autoloader']['classes'] = [];
$profile['autoloader']['is_realpath'] = false;
```
> 具体可参考 [Tiny\Runtime\Autoloader/自动加载配置:application/](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/runtime/autoloader.md)
