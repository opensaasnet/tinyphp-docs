Configuration 配置
====
   当前Application实例的Configuration实例   
   通过profile.php的config.path配置路径，默认为application/config;   

### Configuration的实例获取
```php

// 支持参数注入和自动注解
// controller/model

/**
* @autowired 自动注入
*/
protected Configuartion $config;

/**
* 参数注入
*/
public function __construct(Configuration $config)
{
  ...
}

/**
* @autowired 自动注入
*/
public function config(Configurartion $config) 
{
  ...
}

// 也可通过别名调用
public function getConfigByAlias(ContainerInterface $container)
{
   return $container->get('app.config');
}
```

Configuration 的使用
----


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

Configuration在Application内的实例化
----

通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。

```php
namespace Tiny\MVC\Application;
   
 class ApplicationProvider implements DefinitionProviderInterface
 {
    /**
     * 获取配置的容器定义
     *
     * @throws ApplicationException
     * @return void|\Tiny\DI\Definition\CallableDefinition
     */
    protected function getConfigDefinition()
    {
        $config = (array)$this->properties['config'];
        
        // 配置是否开启
        if (!$config['enabled']) {
            return;
        }
        
        return new CallableDefinition(Configuration::class, function (ContainerInterface $container, array $config) {
            
            // 检测配置路径
            if (!$config['path']) {
                throw new ApplicationException("properties.config.path is not allow null!");
            }
            
            // 实例化
            $configInstance = new Configuration($config['path']);
           
            // 是否开启缓存
            if (!$config['cache']['enabled']) {
                return $configInstance;
            }
            
            // 缓存实例存在则从缓存加载或写入配置数据
            if ($container->has('app.application.cache')) {
                $cacheInstance = $container->get('app.application.cache');
                $configData = $cacheInstance->get('application.config');
                if ($configData) {
                    $configInstance->setData($configData);
                } else {
                    $configData = $configInstance->get();
                    $cacheInstance->set('application.config', $configData);
                }
            }
            return $configInstance;
        }, ['config' => $config]);
    }
```

profile.php 配置
----
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

### 具体参考可见   
[Configaurtion/配置类:Tiny\Config\Configuration](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/configuration.md)
