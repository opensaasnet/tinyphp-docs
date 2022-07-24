Response 响应实例
====

* Response通过Application的__construct()内初始化。   


Response的实例获取
----

### 为保持Model的无状态模式，Response禁止在Model层内调用。

```php

// 支持参数注入和自动注解
// controller

/**
* @autowired 自动注入
*/
protected Response $response;

/**
* 参数注入
*/
public function __construct(Response $response)
{
  ...
}

/**
* @autowired 自动注入
*/
public function getRes(Response $response) 
{
  ...
}

// 也可通过别名调用
public function getResponseByAlias(ContainerInterface $container)
{
   return $container->get('app.response');
}
```

Response 的使用
* 在通过视图Viewer处理的情况下，视图渲染后的结果将会通过$response->appendBody加入Response内；
* Response分为两部分,Header, Body;
* Header包含Status code,响应格式，各种Header报文，Cookie,SessionID等。
* Body可为文本流和二进制流。
----

```php
// 通过.分隔子节点
$response->appendBody('hello world!');
$response->output();
// output "Hello world!"

// 终止并输出响应
$response->end('hello world!');
// output "hello world!"

// file
```
* Response输出JSON的方式，分为格式化和非格式化两种,如果是调试模式，将会主动关闭调试模式输出。
* 非格式化。
```php
$data = ['name' => 'tinyphp'];
$response->outJson($data);
// output "{"name": "tinyphp"}"
```

* 格式化输出JSON的使用方式
```php

// application/lang/status.php ['0' => 'success %s!'];
$response->outFormatJSON(0, 'tinyphp', ["tinyphp"]);
// output {"status": 0, "msg": "success tinyphp!", "data": ["tinyphp"]};
```

* 格式化输出JSON的profile.php 配置，将会通过status code寻找语言配置文件内的节点输出。

```php
/**
 * Application的响应实例配置
 *
 * response.formatJsonConfigId
 *    response格式化输出JSON 默认指定的语言包配置节点名
 *    status => $this->lang['status'];
 */
$profile['response']['formatJsonConfigId'] = 'status';
```

* 格式化输出JSON的函数实现。   
```php
    /**
     * 输出格式化
     *
     * @param int $status 状态码
     * @param string $param 可替换msg里面的% 最后一个参数如果是数组 则输出data
     * @example $this->response->formatJSON(0, 'msg1', 'msg2', ['aaa', 'aaaa']);
     *          {"status":0,"msg":"msg1msg2","data":["aaa","aaaa"]}
     */
    public function outFormatJSON($status = 0, ...$param)
    {
        ...
    }
}
```

### 可参考标准库 
[MVC/MVC:Tiny\MVC\Response](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/mvc.md)
