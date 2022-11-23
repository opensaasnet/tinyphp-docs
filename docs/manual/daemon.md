Daemon 服务端守护进程
====

基于PHP的pcntl扩展实现， 
* 需要安装pcntl扩展;


### 使用示例
```
// 使用默认的profile.php配置
php public/index.php --daemon 

// 停止
php public/index.php --daemon=stop

// 支持单文件模式
php tinyphp.phar --daemon=start|stop
```

### Application内的实例化

通过Application中属性实例Properties的执行构造函数时，获取守护进程的配置，并注册到事件监听器中。 

```php
namespace Tiny\MVC\Application;
   
 class Properties extend Configuration
 {    
    public function __construct()
    {
        ...
        // 执行命令行程序的一些初始化
        $this->initInConsoleApplication();
    }
    
    /**
     * 应用于命令行时的初始化
     */
    protected function initInConsoleApplication()
    {
        // 判断是否在命令行环境中
        if (!$this->app instanceof ConsoleApplication) {
            return;
        }
        ... 
        // 初始化守护进程配置
        $this->initDaemon();
    }
    
    /**
    * 是否开启服务守护进程
    */
    protected function initDaemon()
    {
        $config = $this['daemon'];
        
        // 配置是否开启守护进程
        if (!$config || !$config['enabled'] || !$config['event_listener']) {
            return;
        }
        
        // 注册守护进程的事件监听器
        $this['event.listeners.daemon'] = $config['event_listener'];
    }
```

### Daemon的事件监听实现流程


通过MvcEvent::EVENT_ROUTER_STARTUP事件触发事件监听类Tiny\MVC\Event\DaemonEventListener

```php
 
 // 监听命令行参数--daemon 是否开启守护进程
 
 // 获取daemon的配置节点 profile.php中的build.path配置
 
 // 构建$options配置数组
  $options = [...];
  
  // Daemon
  $daemonInstance = new Daemon($id, $options);  //创建实例
  $daemonInstance->addWorkersByConfig($workers, $this->app);  // 设置子进程的配置
  $daemonInstance->setDaemonHandler($this->app);   // 设置子进程执行的委托句柄
  $daemonInstance->run();  // 执行守护
  $this->response->end(); // 终止
```

### Daemon在profile.php的配置项

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

具体参考可见   
-----
-----

[Console/命令行库:Tiny\Console\Daemon](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/daemon.md)    
[Event/事件:Tiny\MVC\Event](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/event.md)  
