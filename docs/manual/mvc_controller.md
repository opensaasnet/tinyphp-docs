Controller 控制器
====


MVC的控制流程主要包括: `请求Request`, `引导Bootstrap`, `路由Router`，`派发Dispatcher`，`执行Action`，`视图渲染Viewer`, `响应Response`.


### Controller的实例化

Web环境下，需要继承Tiny\MVC\Controller\Controller

Console环境下，需要继承Tiny\MVC\Controller\ConsoleController

他们的基类是Tiny\MVC\Controller\ControllerBase. Tiny\MVC\Controller\ControllerBase的实现包括三部分，`自动注入的实例`，`视图处理函数和无视图情况下的输出`，`模型及其他类的自动注入`。
 
 
```php
/**
 * 控制器积类
 *
 * @package Tiny.Application.Controller
 * @since 2017年3月12日下午2:57:20
 * @final 2017年3月12日下午2:57:20
 */
abstract class ControllerBase
{
   // 自动注入的参数边
    
    /**
     * 当前应用程序实例
     *
     * @var ApplicationBase
     */
    protected $application;
    
    /**
     * 当前请求参数
     *
     * @var Request
     */
    protected $request;
    
    /**
     * 当前响应实例
     *
     * @var Response
     */
    protected $response;

    /**
     * 模块设置数组
     * 
     * @autowired
     * @var Module
     */
    protected ?Module $module = null;
    
    ...
}
```

### Controller在profile.php的配置项

```php
/**
 * Application的响应实例配置
 *
 * response.formatJsonConfigId
 *    response格式化输出JSON 默认指定的语言包配置节点名
 *    status => $this->lang['status'];
 */
$profile['response']['formatJsonConfigId'] = 'status';

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

可参考标准库
-----
-----

 [Tiny\MVC/MVC](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc.md)

