数据层
====

  Data是当前Application的Data实例
  作为tinyphp管理所有外部数据源的数据层

Data的实例获取
----

### Data一般情况下只允许在model层获取实例和操作，不允许在控制器层和视图层中获取并操作

```php

// 支持参数注入和自动注解
// 只允许在model中获取



/**
* 参数注入
*/
public function __construct(Data $data)
{
    $data;
    $this->data;
    ...
}

/**
* @autowired 自动注入
*/
public function data(Data $data) 
{
  ...
}

// 也可通过别名调用
public function getDataByAlias(ContainerInterface $container)
{
   return $container->get('app.data');
}
```

Data 的使用
----

```php
// 通过数据源ID获取数据源操作实例
// profile.php data.sources.default = ['derver' => 'db.pdo'];
$this->data['default']->fetchall('SELECT * FROM user');

```

Data 在Application内的实例化
----

通过Application中的容器定义源 Tiny\MVC\Application\ApplicationProvider自动加载入容器。

```php
    /**
     * 获取数据操作池的定义
     *
     * @return \Tiny\DI\Definition\CallableDefinition
     */
    protected function getDataDefinition()
    {
        $config = (array)$this->properties['data'];
        
        // 配置是否开启data实例化
        if (!$config['enabled']) {
            return;
        }
        
        return new CallableDefinition(Data::class, function (ApplicationBase $app, array $config) {
            
            // 通过配置节点profile.php data.drivers 添加数据源驱动
            foreach ((array)$config['drivers'] as $id => $className) {
                Data::regDataSourceDriver($id, $className);
            }
            
            // 实例化 
            $dataInstance = new Data();
            
            // 添加数据源
            foreach ((array)$config['sources'] as $sourceConfig) {
                $sourceConfig['is_record'] = (bool)$app->isDebug;
                $dataInstance->addDataSource($sourceConfig);
            }
            
            return $dataInstance;
        }, ['config' => $config]);
    }
```

profile.php 配置
----
```php
/**
 * 数据资源池配置
 *  
 *  data.enabled 开启数据资源池
 *      true 开启|false 关闭
 *  
 *  data.charset 数据库默认连接编码
 *      utf8 默认utf8
 *      false 无需自动设置编码
 *      utf8mb4 兼容表情包
 *  
 *   data.default_id 默认ID
 *      默认调用datasource的ID
 *  
 *  data.drivers 驱动数组
 *  
 *  data.sources 数据资源池配置   
 *      driver = db.mysqli|db.pdo| [
 *          id => 调用时使用的ID字段
 *          host 通用的远程资源
 *          prot 通用的远程端口
 *          password 通用密码
 *          dbname 数据库名称
 *      ]
 *      
 *      driver = redis [
 *          id => 调用时使用的ID字段
 *          host => 远程host 单独设置的host & prot 会合并到servers内
 *          port => 远程端口
 *          db => 选择的DB Index
 *          servers => [[host => 服务, port => 端口]]  
 *      ]
 *      
 *      driver = memcached [
 *          servers => [[host=> 服务地址, port=> 端口]]
 *          persistent_id => 共享实例的ID
 *          options => [选项]
 *      ]
 */
$profile['data']['enabled'] = true;    /* 是否开启数据池 */
$profile['data']['charset'] = 'utf8';
$profile['data']['default_id'] = 'default';
$profile['data']['drivers'] = [];
$profile['data']['sources'] = [
    ['id' => 'default', 'driver' => 'db.mysqli', 'host' => '127.0.0.1', 'port' => '3306', 'user' => 'root', 'password' => '123456', 'dbname' => 'mysql'],
    ['id' => 'redis', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379', 'db' => 0],
    ['id' => 'redis_cache', 'driver' => 'redis', 'servers' => [['host' => '127.0.0.1', 'port' => '6379']]],
	['id' => 'redis_session', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379'],
    ['id' => 'redis_queue', 'driver' => 'redis', 'host' => '127.0.0.1', 'port' => '6379'],
    ['id' => 'memcached', 'driver' => 'memcached', 'servers' => [['host' => '127.0.0.1', 'port' => '11211']], 'persistent_id' => null, 'options' => []]
];
```

### 具体参考可见   
[Data/数据层:Tiny\Data\Data](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/lib/data.md)
