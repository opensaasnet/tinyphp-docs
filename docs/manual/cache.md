Cache 缓存层
====

当前Application实例的Cache实例

### Cache的实例获取
```php

// 支持参数注入和自动注解
// controller/model

/**
* @autowired 自动注入
*/
protected Cache $cache;

/**
* 参数注入
*/
public function __construct(Cache $cache)
{
  ...
}

/**
* @autowired 自动注入
*/
public function cache(Cache $cache) 
{
  ...
}

// 也可通过别名调用
public function getCacheByAlias(ContainerInterface $container)
{
   return $container->get('app.cache');
}
```

Cache 的使用
----

```php
$cache->set('name', 'tinyphp');
echo $cache->get('name');
// output "tinyphp"

// 可用数组形式调用 key为profile.php cache.source.default
$cache['default']->get('name');
// output "tinyphp"
```

Cache 在Application内的实例化
----

通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。

```php
namespace Tiny\MVC\Application;
   
 class ApplicationProvider implements DefinitionProviderInterface
 {
    ...
    
    /**
     * 获取缓存的定义
     *
     * @return CallableDefinition
     */
    protected function getCacheDefinition()
    {
        
        // 获取profile.php的配置 是否允许cache实例化
        if (!$this->properties['cache.enabled']) {
            return;
        }
        
        return new CallableDefinition(Cache::class, function (array $config) {
            
            // 添加缓存存储器
            $storagers = (array)$config['storagers'];
            foreach ($storagers as $storagerId => $storagerClass) {
                Cache::regStorager($storagerId, $storagerClass);
            }
            
            $defaultId = (string)$config['default_id'];
            $ttl = (int)$config['ttl'];
            $path = (string)$config['dir'];
            
            // 创建实例
            $cacheInstance = new Cache();
            
            // 设置默认的文件缓存路径  默认为runtime/cache
            $cacheInstance->setDefaultPath($path);
            
            // 默认的缓存调用source id
            $cacheInstance->setDefaultId($defaultId);
            
            // 默认的缓存周期
            $cacheInstance->setDefaultTtl($ttl);
            
            // 添加缓存池的源配置
            $caches = (array)$config['sources'];
            foreach ($caches as $cacheConfig) {
                $cacheInstance->addStorager($cacheConfig['id'], $cacheConfig['storager'], $cacheConfig['options']);
            }
            return $cacheInstance;
        }, ['config' => (array)$this->properties['cache']]);
    }
```

profile.php 配置
----
```php
/**
 * Application的缓存设置
 * 
 * 支持的存储器类型
 *      file => Tiny\Cache\Storager\File 文件存储 
 *      memcached => Tiny\Cache\Storager\Memcached memcache存储
 *      php      => Tiny\Cache\Storager\PHP PHP文件序列化存储
 *      redis => Tiny\Cache\Storager\Redis  Redis存储
 *      SingleCache => Tiny\Cache\Storager\SingleCache 单文件存储 适合小数据快速缓存
 *      
 *  cache.enabled 开始缓存
 *      true 开启  | false 关闭
 * 
 * cache.ttl 默认的缓存过期时间
 *      ttl 可单独设置
 * 
 * cache.dir 默认的本地文件缓存路径
 *      string dir 只可设置为文件夹
 *      
 * cache.application_storager
 *      string 当前应用实例的缓存存储器
 *      
 * cache.default_id 默认的缓存资源ID
 *      $cache 将缓存实例当缓存调用时所调用的cacheID
 * 
 * cache.application 
 *      是否对application的lang container config等数据进行缓存
 * 
 * cache.storagers 缓存存储器的注册列表
 *      [
 *          key => value
 *          存储器ID => 存储器类全程
 *          'file' => \Tiny\Cache\File::class
 *      ]
 *      添加后，即可在cache.sources节点的storager引用
 *  
 *  cache.sources 缓存源
 *      本框架的远程缓存源通过datasource统一调度管理
 *      id => 调用缓存资源的ID
 *      storager = redis [
 *          options => [
 *              ttl => 默认过期时间
 *              dataid => 调用的data sources ID
 *          ]
 *      ]
 *      
 *      storager => memcached [
 *          options => [
 *              ttl => 默认的过期时间
 *              dataid => 调用的data source id
 *          ]
 *          
 *      ]
 *      
 *      storager => file [
 *          options =>
 *      ]
 */
$profile['cache']['enabled'] = true;
$profile['cache']['ttl'] = 3600;
$profile['cache']['dir'] = '{runtime}/cache/';
$profile['cache']['default_id'] = 'default';
$profile['cache']['storagers'] = [];
$profile['cache']['sources'] = [
   ['id' => 'default', 'storager' => 'redis', 'options' => ['ttl' => 3600, 'dataid' => 'redis_cache']],
    ['id' => 'memcached', 'storager' => 'memcached', 'options' => ['ttl' => 3600, 'dataid' => 'memcached']],
    ['id' => 'file', 'storager' => 'file', 'options' => ['ttl' => 3600, 'path' => '']],
   ['id' => 'php', 'storager' => 'php', 'options' => ['ttl' => 3600, 'path' => '']]
];

/**
 * 当前应用实例的缓存配置
 * 
 * cache.application_storager ApplicationCache调用的存储器类型
 *      默认为SingleCache 适合小数据的快速存储应用，php文件存储于opcache内存中，IO性能很好。
 *      
 * cache.application_ttl ApplicationCache的缓存过期时间
 *      int 60
 * 
 */
$profile['cache']['application_storager'] = SingleCache::class;
$profile['cache']['application_ttl'] = 60;
```

### 具体参考可见   
[Cache/缓存:Tiny\Cache\Cache](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/lib/cache.md)
