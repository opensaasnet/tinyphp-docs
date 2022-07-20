Configuration 配置
====


### 示例
```
// 使用默认配置 build/builder/tinyphp.php
php public/index.php --build

// 生成tinyphp.phar
php tinyphp.phar

```

Application内的打包器初始化
----
通过Application中属性实例Properties的执行构造函数时，获取打包器配置，并注册到事件监听器中。 

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
        // 初始化打包器配置
        $this->initBuilder();
    }
    
    /**
     * 是否开启命令行下的打包机制
     */
    protected function initBuilder()
    {
        $config = $this['builder'];
        
        // 配置是否开启打包器
        if (!$config || !$config['enabled'] || !$config['event_listener']) {
            return;
        }
        
        // 注册事件监听器
        $this['event.listeners.builder'] = $config['event_listener'];
    }    
```

打包器的事件监听实现流程
----

通过MvcEvent::EVENT_ROUTER_STARTUP事件触发事件监听类Tiny\MVC\Event\BuilderEventListener

```php
 
 // 监听命令行参数--build 是否开启打包器流程
 
 // 获取builder的配置文件 profile.php中的build.path配置
 
 // 构建$buildOptions配置数组
  ...
  
  // 执行打包器
  new \Tiny\Build\Builder($buildOptions))->run();
```

profile.php 配置
----
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

通过builder.path定义的打包器配置文件说明
----
```
$profile['builder']['path'] = 'build/builder';   
```

该路径下每个.php配置文件都可以读取配置并单独打包成一个可执行文件
例如：
```php
build/builder/tinyphp.php
build/builder/tinyphp1.php
```
```php
/**
 * 返回一个单文件打包配置
 * 
 * [
 *      name 打包后生成的单文件名  
 *          example: tinyphp-demo => tinyphp-demo.phar
 *          
 *      header_php_env 是否在打包文件首行添加PHP运行环境
 *          example: #!/usr/bin/php
 *          运行单文件的方式可由php tinyphp-demo.phar 变为 ./tinyphp-demo
 *          
 *      namespaces [namespace => dirname]
 *          加载命名空间对应的加载目录和路径
 *      
 *      controller 运行单文件时的默认控制器
 *      
 *      action 运行单文件时的默认动作
 *      
 *      framework_path 框架源代码目录
 *      
 *      vendor_path composer库引入的目录路径
 *      
 *      exclude 打包时忽略的文件正则
 *      
 *      attachments => [dirname...]
 *          运行单文件时解压在单文件所在目录的文件和文件夹
 *           example: ['config/app', APPLICATION_PATH . 'config/'],
 *      
 *      home_attachments => [dirname...]
 *          运行单文件时解压在单文件用户所在目录的文件和文件夹
 *          example : 用户为wwww，解压目录则为 /home/www/.tinyphp-demo
 * ]
 */
return [
    'name' => 'tinyphp',
    'header_php_env' => true,
    'namespaces' => [],
    'controller' => 'main',
    'action' => 'build',
    'framework_path' => TINY_FRAMEWORK_PATH,
    'vendor_path' => dirname(APPLICATION_PATH) . '/vendor',
    'exclude' => ["/\.(git|buildpath|project|dat|log|settings|md|svn)$/","/vendor\/smarty\/smarty/", "/tinyphp-ui\/(node_modules|src\/js|conf|dist|build|templates\/pages\/)/"],
    'attachments' => [], 
    'home_attachments' => [], 
];
```

### 具体参考可见   
[Configaurtion/配置类:Tiny\Config\Configuration](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/configuration.md)
