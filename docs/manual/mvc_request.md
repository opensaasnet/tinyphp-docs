Request 请求实例
====

* Request通过Application的__construct()内初始化。   
* Web环境下的WebRequest管理所有外部输入参数，包括$_GET, $_POST,$_SERVER, $_COOKIE, $_REQUEST, $_ENV, $_FILE;   
* Console环境下的ConsoleRequest管理命令行参数， 包括$_arg等。   
* 为了符合安全和框架的编码规范，除非通过Request实例引用外部参数，禁止使用以上全局变量。   

Request的实例化。
----

* Request不破坏已有全局变量的参数。
* Request管理路由匹配的控制器Controller,动作Action, 模块Moudle的参数名和值。
* Request的参数初始化仅支持Readonly，不允许变更，防止多人协作时的外部参数意外变更。
```php
/**
 * 请求体基类
 *
 * @package Tiny.MVC.Request
 * @since 2017年4月4日下午8:47:29
 * @final 2017年4月4日下午8:47:29
 */
abstract class Request
{

    /**
     * 当前应用实例
     *
     * @param ApplicationBase $app
     */
    public function __construct(ApplicationBase $app)
    {
        $this->application = $app;
        if (!key_exists('argv', $_SERVER)) {
            $_SERVER['argv'] = [];
        }
        $this->server = new Readonly($_SERVER);
        unset($_SERVER);
        $this->param = new Readonly();
        $this->routeParam = new Readonly();
        $this->initData();
    }
    ...
}

class WebRequest extend Request
{
    /**
     * 
     * {@inheritDoc}
     * @see \Tiny\MVC\Request\Request::initData()
     */
    protected function initData()
    {
        $this->param->merge($_REQUEST);
        $this->get = new Get($_GET);
        $this->post = new Post($_POST);
        ...
    }
}
```
