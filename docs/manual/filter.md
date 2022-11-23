Filter 过滤器
====

当前Application实例的Filter实例
* 主要作用于外部数据输入时的过滤，防止注入和渗透。
* 主要通过魔法函数 $request->formatInt()的方式调用;

### Filter的实例获取

```php

// 支持参数注入和自动注解
// controller/model

/**
* @autowired 自动注入
*/
protected Filter $filter;

/**
* 参数注入
*/
public function __construct(Filter $filter)
{
  ...
}

/**
* @autowired 自动注入
*/
public function filter(Filter $filter) 
{
  ...
}

// 也可通过别名调用
public function getFilterByAlias(ContainerInterface $container)
{
   return $container->get('app.filter');
}
```

### Filter 的使用


```php
// 通过.分隔子节点
echo $filter->formatWeb('tinyphp Select');
// output "tinyphp"

// 可用数组形式调用 但无法替换里面的%s%d等字符参数
// url /index.php?name=8788dx&id=34234s
echo $request->get->formatInt('id', 0);
// output 34234
```

### Filter 在Application内的实例化


通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。
可根据不同运行环境，启用不同的过滤器，主要为Web/cli

```php
namespace Tiny\MVC\Application;
   
 class ApplicationProvider implements DefinitionProviderInterface
 {
    /**
     * 过滤器定义
     * @return CallableDefinition
     */
    protected function getFilterDefinition()
    {
        // 是否开启过滤实例化
        $config = (array)$this->properties['filter'];
        if (!$config['enabled']) {
            return false;
        }
        
        return new CallableDefinition(Filter::class, function (ApplicationBase $app, array $config) {
            
            // 创建实例
            $filterInstance = new Filter();    
            
            // web和console下调用不同的过滤器
            $filterClass = ($app instanceof WebApplication) ? $config['web'] : $config['console'];
            
            // 添加过滤器
            if ($filterClass && is_array($filterClass)) {
                foreach ($filterClass as $fclass) {
                    $filterInstance->addFilter($fclass);
                }
            } elseif ($filterClass && is_string($filterClass)) {
                $filterInstance->addFilter($filterClass);
            }
            
            // 添加通用的过滤器
            foreach ((array)$config['filters'] as $fname) {
                $filterInstance->addFilter($fname);
            }
            
            return $filterInstance;
        }, ['config' => $config]);
    }
```

### Filter在profile.php内的配置项

```php
/**
 * application的过滤器配置
 * 
 * filter.enabled 开启过滤
 * 
 * filter.web WEB环境下的过滤器设置
 *      string classname 实现FilterInterface的过滤器
 *      array [filterInterface]
 * 
 * filter.console 命令行环境下的过滤器设置
 *      string classname 实现FilterInterface的过滤器
 *      array [filterInterface]
 * 
 * filter.filters 通用过滤器设置
 *      array [FilterInterface]
 */
$profile['filter']['enabled'] = true;
$profile['filter']['web'] = \Tiny\Filter\WebFilter::class;
$profile['filter']['console'] = \Tiny\Filter\ConsoleFilter::class;
$profile['filter']['filters'] = [];
```

具体参考可见   
-----
-----
[Filter/过滤器:Tiny\Filter\Filter](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/lib/filter.md)
