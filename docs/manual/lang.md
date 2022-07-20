Lang 语言包
====
   当前Application实例的Lang实例   
   通过profile.php的lang.path配置路径，默认为application/lang;   

### Lang的实例获取
```php

// 支持参数注入和自动注解
// controller/model

/**
* @autowired 自动注入
*/
protected Lang $lang;

/**
* 参数注入
*/
public function __construct(Lang $lang)
{
  ...
}

/**
* @autowired 自动注入
*/
public function lang(Lang $lang) 
{
  ...
}

// 也可通过别名调用
public function getLangByAlias(ContainerInterface $container)
{
   return $container->get('app.lang');
}
```

Lang 的使用
----

```php
// 通过.分隔子节点
// lang/default.php  "hello %s!"
echo $lang->translate('default.a.b', "tinyphp");
// output "hello tinyphp!"

// 可用数组形式调用 但无法替换里面的%s%d等字符参数
$lang['default.a.b'];
// output "hello %s!"

// 获取配置所有数据
$lang->getData();

```
#### 注意： Lang的配置数据无法动态修改


Lang 在Application内的实例化
----

通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。

```php
namespace Tiny\MVC\Application;
   
 class ApplicationProvider implements DefinitionProviderInterface
 {
    /**
     * 获取语言包容器定义
     *
     * @return boolean|\Tiny\DI\Definition\CallableDefinition
     */
    protected function getLangDefinition()
    {
        $config = (array)$this->properties['lang'];
        if (!$config['enabled']) {
            return false;
        }
        return new CallableDefinition(Lang::class, function (ContainerInterface $container, array $config) {   
            $locale = (string)$config['locale'];
            $configPath = (string)$config['path'];
            
            // create
            $langInstance = new Lang();
            $langInstance->setLocale($locale);
            $langInstance->setPath($configPath);
            
            if (!$config['cache']['enabled']) {
                return $langInstance;
            }
            
            // config cache
            if ($container->has('app.application.cache')) {
                $cacheInstance = $container->get('app.application.cache');
                $cacheKey = (string)$config['cache']['key'] ?: 'application.cache.lang';
                $langData = $cacheInstance->get($cacheKey);
                if ($langData && is_array($langData)) {
                    $langInstance->setData($langData);
                } else {
                    $langData = $langInstance->getData();
                    $cacheInstance->set($cacheKey, $langData);
                }
            }
            return $langInstance;
        }, ['config' => $config]);
    }
```

profile.php 配置
----
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

### 具体参考可见   
[Configaurtion/配置类:Tiny\Config\Configuration](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/configuration.md)
