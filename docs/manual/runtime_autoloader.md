Autoloader 自动加载
====

* Autoloader 通过Runtime实例化。
* 在构造函数内，通过`spl_autoload_register`注册自动加载句柄处理函数。
* 如果已经存在其他已注册`spl_autoload_register`的处理句柄，如`composer`，则将Tiny\Runtime\Autoloader注册为第一个处理句柄。   
```php
namespace Tiny\Runtime;

class Autoloader
{
    public function __construct()
    {
        $autoloadFunctions = spl_autoload_functions();
        if (!$autoloadFunctions) {
            // @formatter:off
            return spl_autoload_register([$this, 'loadClass']);
        }
        
        // 多个autoloader情况下，tinyphp首先加载
        foreach ($autoloadFunctions as $afunc) {
            spl_autoload_unregister($afunc);
        }
        
        array_unshift($autoloadFunctions, [$this, 'loadClass']);
        
        // @formatter:on
        foreach ($autoloadFunctions as $afunc) {
            spl_autoload_register($afunc);
        }
    }
    ...
}
```

* Autoloader 可以添加三种类文件的搜索方式
  * 全局类映射
  * 命名空间映射
  * 类文件映射
```php

// 全局类映射
$namespace = '*';
$autoloader->addToNamespacePathMap($namespace, $path);

// 命名空间;
$namespace = 'Tiny';
$autoloader->addToNamespacePathMap($namespace, $path);

// 类文件映射
$className = `Tiny\Runtime\Runtime`;
$autoloader->addToClassPathMap($className, $path);

// 也可通过Runtime实例调用
// 全局类映射
$namespace = '*';
$runtime->addToNamespacePathMap($namespace, $path);

// 命名空间;
$namespace = 'Tiny';
$runtime->addToNamespacePathMap($namespace, $path);

// 类文件映射
$className = `Tiny\Runtime\Runtime`;
$runtime->addToClassPathMap($className, $path);
```
* autoloader的类文件搜索规则。  
* 当class加载不到时，触发$autoloader->loadClass($className)函数开始搜索路径;
```php
  // 首先在classmap中查找
  if (key_exists($className, $this->classPathMap)) {
      $classfile = $this->classPathMap[$className];
      include_once $classfile;
      return;
  }
  
  //如果是全局类 则从全局路径中查找
  
  // 最后在命名空间映射路径中依次搜索。
  // 搜索规则:依次最下一层命名空间搜索不到，则会往上一层的命名空间文件搜索，依次到最上一层。
  // Tiny\MVC\Application\Properties;
  // 寻找路径依次为
  // Tiny/MVC/Application/Properties.php
  // Tiny/MVC/Application.php
  // Tiny/MVC.php
  // Tiny.php
  // 全部搜索不到则返回空。
```

具体参考可见 [Tiny\Runtime/运行时环境](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/runtime.md)
