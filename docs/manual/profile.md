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

具体可参考 [Configuration/配置类](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/configuration.md)

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
> 更多可参考 [Lang/语言包:demo/application/lang](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/manual/lang.md)

3.10 Logger配置
----
> log.enabled = TRUE|FALSE application->getLogger()是否输出Logger的实例。   
> log.type = file|syslog file为文件系统记录  syslog是通过php的syslog扩展记录日志。   
> log.path log.type=file时 设置的log日志文件存放路径。   

```php
/**
 * 日志模块设置
 */
$profile['log']['enabled'] = TRUE;
$profile['log']['type'] = 'file';    /*默认可以设置file|syslog 设置类型为file时，需要设置log.path为可写目录路径 */
$profile['log']['path'] = 'runtime/log/';
```
> 更多可参考 [Logger/日志收集:demo/application/runtime/log](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/logger-010.md)   

3.11 Data数据源管理
----
> <b>Data管理的数据源可供model|session|cache调用</b>    
> data.enabled 是否开启数据池管理 application->getData()是否输出Data实例。   
> data.charset 默认编码为utf8。    
> data.policys 管理是有的数据源链接。   
>> id data源ID 在model中通过$this->data[id] 调用该数据源的实例。   
>> driver data驱动  支持mysql|mysqli|pdo_mysql|redis|memcache 可自定义扩展其他数据源管理。   
>>  其他参数为该驱动所需的连接参数，根据具体驱动实例需求设置。   
```php
/**
 * 数据模块设置
 * id为 default时，即为默认缓存实例
 *  driver mysql
 *  dirver mysqli
 *  dirver pdo_mysql
 *  driver redis
 *  driver memcache
 */
$profile['data']['enabled'] = TRUE;    /* 是否开启数据池 */
$profile['data']['charset'] = 'utf8';
$profile['data']['policys'] = [
    ['id' => 'default', 'driver' => 'db.mysql_pdo', 'host' => '127.0.0.1', 'port' => '3306', 'user' => 'root', 'password' => '123456', 'dbname' => 'mysql'],
    ['id' => 'redis', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379' ],
    ['id' => 'redis_cache', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379', 'servers' => [['host' => '127.0.0.1', 'port' => '6379'],['host' => '127.0.0.1', 'port' => '6379']]],
    ['id' => 'redis_session', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379'],
    ['id' => 'redis_queue', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379'],
    ['id' => 'memcached', 'driver' => 'memcached', 'host' => '127.0.0.1', 'port' => '11211']
];
```
> 更多可参考 [Data/数据源](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/data-007.md)

3.12 Cache
----

> cache.enabled = TRUE|FALSE application->getCache()是否输出Cache的实例。     
>> controller中调用 $this->cache;   
>> model中调用 $this->cache;   
>> viewer中调用 $cache。   
> cache.lifetime 缺省的缓存时间。 
> cache.filepath 本地文件缓存时的相对缓存路径
> cache.policys 缓存配置策略
>>  id  $this->cache[id] 调用具体配置的缓存实例
>>  driver 缓存驱动，具体类型的缓存类型 file|redis|memcache
>  
```php
/**
 * 缓存模块设置
 * id为 default时，即为默认缓存实例 可以用Cache::getInstance()使用 或者在controller以及Model中 直接以$this->cache使用
 * driver 
 *       driver=file     文件缓存  文件缓存填写相对application的路径，不允许绝对路径
 *       driver=memcache memcache缓存 dataid=data数据池driver=memcache配置ID
 *       driver=redis    Redis缓存    dataid=data数据池driver=redis配置ID
 */
$profile['cache']['enabled'] = TRUE; /* 是否默认开启缓存模块，若不开启，则以下设置无效 */
$profile['cache']['lifetime'] = 3600;
$profile['cache']['filepath'] = 'runtime/cache/'; /*文件缓存方式的缓存相对路径*/
$profile['cache']['policys'] = [
    ['id' => 'default', 'driver' => 'redis', 'lifetime' => 3600, 'dataid' => 'redis_cache'],
    ['id' => 'file', 'driver' => 'file', 'lifetime' => 3600, 'path' => '']
];
```
> 更多可参考 [Cache/缓存:demo/](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/cache-008.md)

3.13 Session
----

> session.enabled 是否开启框架内的session管理。   
> session.domain决定SESSIONID的作用域。    
> session.path 决定SESSIONID的作用路径。   
> session.expires  决定SESSIONID的过期时间。   
> driver=redis|memcache 支持redis|memcache两种全局共享的session方式。   
> dataid 配置为driver对应的dataid。    
```php
/**
 * HTTP SESSION设置
 * driver 为空 PHP自身Session
 * driver memcache Memcache
 * driver redis Redis作为Session */
$profile['session']['enabled'] = TRUE;
$profile['session']['domain'] = '';
$profile['session']['path'] = '/';
$profile['session']['expires'] = 36000;
$profile['session']['domain'] = '';
$profile['session']['driver'] = 'redis';
$profile['session']['dataid'] = 'redis_session';
```
> 更多可参考 [Controller/控制器:demo/application/controllers/](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/controller-017.md)

3.14 Filter过滤器设置
----
> filter.enabled application->getFilter()是否输出Filter实例。   
> filter.web WEB环境下的过滤器配置，可自定义更换。    
> filter.console console环境下的过滤器配置，可自定义更换。   
> filter.filters 可自定义实现了Tiny\Filter\IFilter接口的filter实例。   
> 主要影响为 controller下的$this->get $this->post $this->param等参数的过滤。
   
```php
/**
 * 过滤器配置
 */
$profile['filter']['enabled'] = TRUE;
$profile['filter']['web'] = '\Tiny\Filter\WebFilter';
$profile['filter']['console'] = '\Tiny\Filter\ConsoleFilter';
$profile['filter']['filters'] = [];
```

3.15 Cookie
----
> Cookie的缺省参数配置
> Cookie在框架中的管理，仅支持Controller的引用 $this->cookie
```php
/**
 * HTTP COOKIE设置
 */
$profile['cookie']['domain'] = '';
$profile['cookie']['path'] = '/';
$profile['cookie']['expires'] = 3600;
$profile['cookie']['prefix'] = '';
$profile['cookie']['encode'] = FALSE;
```
> 更多可参考 [Controller/控制器:demo/application/controllers/](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/controller-017.md)

3.16 MVC流程控制
----
> 相关MVC流程的命名空间配置
> controller.default 默认控制器名称    
> controller.param 默认输入的控制器参数名 http://localhost/index.php?c=main   
> controller.namespace web环境下的控制器命名空间   
> controller.console console环境下的控制器命名空间   
> controller.rpc     rpc环境下的控制器命名空间  rpc目前未实现   
>  model.namespace 模型的命名空间设置   
>  action.default 默认的动作名称   
>  action.param 默认的动作输入参数名  http://localhost/index.php?a=index   
> response.formatJsonConfigId。   
>> 在控制器中通过$this->outFormatJSON($status=0)格式化输出JSON响应体时，通过$status 在$this->config寻找status配置节点名的值。   
```php
/**
 * 控制器设置
 */
$profile['controller']['default'] = 'main';
$profile['controller']['param'] = 'c';
$profile['controller']['namespace'] = 'Controller';
$profile['controller']['console'] = 'Controller\Console';
$profile['controller']['rpc'] = 'Controller\RPC';


/**
 * 模型
 */
$profile['model']['namespace'] = 'Model';

/**
 * 动作设置
 */
$profile['action']['default'] = 'index';
$profile['action']['param'] = 'a';

/**
 * response输出JSON时 默认指定的配置ID
 */
$profile['response']['formatJsonConfigId'] = 'status';

/**
 * 视图设置
 * 视图引擎绑定
 * 通过扩展名绑定解析引擎
 * php PHP原生引擎
 * 类型 tpl Smarty模板引擎
 * 类型 htm Template模板引擎
 */
$profile['view']['src']     = 'views/';
$profile['view']['lang']['enabled'] = true;
$profile['view']['cache']   = 'runtime/view/cache/';
$profile['view']['compile'] = 'runtime/view/compile/';
$profile['view']['config']  = 'runtime/view/config/';
$profile['view']['engines'] = [];
$profile['view']['assign'] = [];

/**
 * 路由规则设置
 */
$profile['router']['enabled'] = TRUE; /* 是否开启router */
$profile['router']['routers'] = [];   /*注册自定义的router*/
$profile['router']['rules'] = [
    ['router' => 'pathinfo', 'rule' => ['ext' => '.html'], 'domain' => ''],
    ];

/**
 * 是否开启插件
 */
$profile['plugin']['enabled'] = FALSE;
```
> 更多可参考 [Controller/控制器:demo/application/controllers/](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/controller-017.md)   
> [Router/路由器](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/router-009.md)     
> [Dispatcher/派发器](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/dispatcher-011.md)   
> [Controller/控制器:demo/application/controllers/](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/controller-017.md)   
> [Model/模型:demo/application/models](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/model-018.md)   
> [Viewer/视图:demo/application/views](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/viewer-019.md)   
> [Plugin/插件](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/plugin-016.md)   

3.17 application的路径管理和配置
----
> 一般设置为相对APPLICATION_PATH下的相对路径。      
> 单文件打包时，会自动修改该选项。   
> src配置数组全部为相对路径
> path 配置数据，即将profile.php中的对应节点添加APPLICATION_PATH的真实路径前缀。
```php
/**
 *  应用基本设置
 */
$profile['app']['namespace'] = 'App';        /*命名空间*/
$profile['app']['resources'] = 'resource/';  /*资源文件夹*/
$profile['app']['runtime'] = 'runtime/';     /*运行时文件夹*/
$profile['app']['tmp'] = 'runtime/tmp/';     /*临时文件夹*/


/**
 * application的源码设置
 */
$profile['src']['path'] = '';             /*源码路径*/
$profile['src']['global'] = 'libs/global/';       /*全局类*/
$profile['src']['library'] = 'libs/vendor/';       /*外部引入实例库*/
$profile['src']['controller'] = 'controllers/web/'; /*控制类*/
$profile['src']['model'] = 'models/';           /*模型类*/
$profile['src']['console'] = 'controllers/console/';        /*命令行控制类*/
$profile['src']['rpc'] = 'controllers/rpc/';               /*rpc控制类*/
$profile['src']['common'] = 'libs/common/';         /*通用类*/
$profile['src']['view'] = 'views/';             /*视图源码*/


/**
 * 需要添加绝对路径APPLICATION_PATH的配置项
 */
$profile['path'] = [
            'src.path',
            'app.assets',
            'build.path',
            'build.profile_path',
            'build.config_path',
            'config.path',
            'lang.path',
            'log.path',
            'cache.path',
            'view.src',
            'view.cache',
            'view.compile',
            'view.config',
            'src.library',
            'src.global',
            'src.controller',
            'src.console',
            'src.rpc',
            'src.model',
            'src.common',
            'daemon.piddir',
            'daemon.logdir'
];
```

3.18 application下的自动加载管理
----

> autoloader.librarys 配置加载类库
> KEY=VALUE 为命名空间=profile.php中的配置节点的值
> * 为全局命名空间
> src.rpc = $profile[src][rpc];
> autoloader.no_realpath = TRUE|FALSE 是否在$profile[src][rpc]前加上APPLICATION_PATH;

```php
/**
 * 自动加载库的配置
 */

$profile['autoloader']['librarys'] = [
        'App\Controller' => 'src.controller',
        'App\Controller\Console' => 'src.console',
        'App\Controller\Rpc' => 'src.rpc',
        'App\Model' => 'src.model',
        'App\Common' => 'src.common',
        '*' => 'src.global',
];
$profile['autoloader']['no_realpath'] = FALSE;   /*是否替换加载库的路径为真实路径 phar兼容性*/
```
> 更多可参考 [Tiny\Runtime：运行时](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/lib/runtime.md)
