Container 容器
====

* Container 通过Runtime实例化。
* 并注入到Runtime创建的ApplicationBase实例中，作为Application的核心机制存在。
* Container的作用:
    * 完成类在容器内的定义、自动创建和存储，并可通过类名或者别名获取唯一实例。
    * 通过参数注入和自动注解，可以轻松获取各种已在容器内注册的类实例。

容器的注入有三种方式
----

* 创建实例时，通过构造函数注入参数
* 通过invoke委托调用成员函数，并注入参数
* 通过自动注解@autowired方式，在创建实例后，@autowired自动注入参数，并运行注解了@autowired的成员函数。

### 参数注入的类型，必须在容器内有定义或者已经注册
```php

/**
* @autowired 标识该类无需通过Definition定义该类创建实例的方式，即可自动创建实例
*
*/
class Bootstrap 
{
   /**
   *
   * @autowired 自动注解的方式，自动注入。
   */
   private ApplicationBase $app;
   
   /**
   * 通过构造函数即可从容器中查找符合类型的类实例注入。
   */
   public function __construct(ContainerInterface $container)
   {
   }
   
   /**
   * @autowired 自动注解该成员函数，在实例化后自动调用，并在调用时从容器中寻找符合类型实例注入。
   */
   public function init(ApplicationBase $app)
   {
   }
}
```
可参考标准库 [Tiny\DI/容器注入](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/di.md)
