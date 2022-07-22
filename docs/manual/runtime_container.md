Container 容器
====

* Container 通过Runtime实例化。
* 并注入到Runtime创建的ApplicationBase实例中，作为Application的核心机制存在。
* Container的作用:
    * 完成类在容器内的定义、自动创建和存储，并可通过类名或者别名获取唯一实例。
    * 通过参数注入和自动注解，可以轻松获取各种已在容器内注册的类实例。

具体参考可见 [Tiny\DI/容器注入](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/di.md)
