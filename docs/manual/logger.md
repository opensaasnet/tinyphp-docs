日志收集器
====

Tiny\Log\Logger 日志采集器
====
   当前Application实例的Logger实例   
   通过profile.php的logger.path配置日志存储路径 默认路径为runtime/log;   

### Logger的实例获取
```php

// 支持参数注入和自动注解
// controller/model

/**
* @autowired 自动注入
*/
protected Logger $lang;

/**
* 参数注入
*/
public function __construct(Logger $logger)
{
  ...
}

/**
* @autowired 自动注入
*/
public function logger(Logger $logger) 
{
  ...
}

// 也可通过别名调用
public function getLoggerByAlias(ContainerInterface $container)
{
   return $container->get('app.log');
}
```

Logger 的使用
----

```php
$logger->write($id, $message, $priority);
```

Logger 在Application内的实例化
----

通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。

```php
    /**
     * 获取日志生成器的容器定义
     *
     * @return void|\Tiny\DI\Definition\CallableDefinition
     */
    protected function getLoggerDefinition()
    {
        // 配置是否开启日志收集器
        $config = (array)$this->properties['log'];
        if (!$config['enabled']) {
            return;
        }
        
        return new CallableDefinition(Logger::class, function (array $config) {

            // 加载日志驱动
            foreach ((array)$config['drivers'] as $type => $className) {
                Logger::regLogWriter($type, $className);
            }
            
            // 创建实例
            $logger = new Logger();
            
            // 文件型日志写入器的配置
            $writerConfig = ('file' === $config['writer']) ? [
                'path' => $config['path'],
            ] : [];
            
            // 添加日志写入器
            $logger->addLogWriter($config['writer'], $writerConfig);
            return $logger;
        }, ['config' => $config]);
    }
```

profile.php 配置
----
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

### 具体参考可见   
[Logger/日志采集器:Tiny\Log\Logger](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/log.md)
