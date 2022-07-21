Environment 运行时环境参数
====

* Environment通过Runtime实例化，可作为数组操作。
```php
class Environment implements \ArrayAccess, \Iterator, \Countable
{
    ...
}
```

* 大多数键值为只读的系统环境参数，仅少数位于自定义列表的参数名可设置。   
```php
    /**
     * 被允许的自定义运行时环境参数
     *
     * @var array
     */
    const ENV_CUSTOM_LIST = [
        'RUNTIME_TICK_LINE'
    ];
    
    ... 
    
    /**
     * 设置运行时的默认环境参数 仅运行时实例化有效
     *
     * @param array $env 环境参数数组
     * @return array
     */
    public static function setEnv(array $envs)
    {
        foreach ($envs as $ename => $evar) {
            if (in_array($ename, self::ENV_CUSTOM_LIST)) {
                self::$defaultENV[$ename] = $evar;
            }
        }
    }    
```

* 默认的系统参数数组。
* 为了性能考虑，默认值为null的缓存参数会通过成员函数$environment->lazyload()惰性加载。
```php
        'FRAMEWORK_NAME' => Runtime::FRAMEWORK_NAME,
        'FRAMEWORK_PATH' => Runtime::FRAMEWORK_PATH,
        'FRAMEWORK_VERSION' => Runtime::FRAMEWORK_VERSION,
        'PHP_VERSION' => PHP_VERSION,
        'PHP_VERSION_ID' => PHP_VERSION_ID,
        'PHP_OS' => PHP_OS,
        'PHP_PATH' => null,
        'PID' => null,
        'GID' => null,
        'UID' => null,
        'USER' => null,
        'SYSTEM_NAME' => null,
        'HOSTNAME' => null,
        'SYSTME_VERSION_NAME' => null,
        'SYSTEM_VERSION_INFO' => null,
        'MACHINE_TYPE' => null,
        'RUNTIME_TICK_LINE' => 10,
        'RUNTIME_MEMORY_SIZE' => null,
        'RUNTIME_DEBUG_BACKTRACE' => null,
        'SCRIPT_DIR' => null,
        'SCRIPT_FILENAME' => null,
        'PHP_PATH' => null,
        'RUNTIME_MODE' => TINY_RUNTIME_MODE_WEB,
        'RUNTIME_MODE_CONSOLE' => TINY_RUNTIME_MODE_CONSOLE,
        'RUNTIME_MODE_WEB' => TINY_RUNTIME_MODE_WEB,
        'RUNTIME_MODE_RPC' => TINY_RUNTIME_MODE_RPC
```
*   Environment的键值列表会合并$_SERVER,$_ENV,self::ENV_DEFAULT_LIST, self::$defaultENV;    
```php
$env = array_merge($_SERVER, $_ENV, self::ENV_DEFAULT_LIST, self::$defaultENV);
```

具体参考可见 [Tiny\Runtime/运行时环境](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/runtime.md)


