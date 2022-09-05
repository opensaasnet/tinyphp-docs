文件结构
=======

1.0 文件类型
----

在.php文件里，框架设定承载内容的文件主要有五种:`包文件`、`类文件`、`函数文件`、`配置文件`，`程序执行文件`。

`包文件(package)`
> 文件包含一个或多个命名空间，每个命名空间里可有多个常量、函数和类。  
> 包文件以命名空间的名称命名，`遵循驼峰命名方式，首字母大写`，`存在多个命名空间时，必须包含相同的命名空间前缀`。  
> 例： 命名空间tiny.Configuration的包文件为Tiny/Config.php

`类文件:` 
> 以一文件一类的形式编写，文件名与类名保持一致，`遵循驼峰命名方式，首字母需大写`  
> 例: PublicController.php。

`函数文件:` 
 > 函数文件一般指全局函数的集合文件，在面向对象的PHP框架里，应尽量避免使用。  
 > 命名方式`遵循驼峰命名方式，首字母小写`，如果存在多个同系列的函数，需采取相同的前缀或后缀。   
> 例：  

```php
function getCookie($key);
...
function setCookie($key, $value);
```

`配置文件` 
> 文件名需与返回数组名保持一致，`不采用驼峰方式命名，以全小写加_分隔符方式`。:
```php
$default = include './default.php'。
```
 > default.php   
```php
return [
    'name' => 'tinyphp';
];
```
 > default.php   
```php
$default = [];
$default['name'] = 'tinyphp';
``` 

`程序执行文件`
> `不采用驼峰方式命名，以全小写加_分隔符方式`。   
> 例: 框架入口文件   
```php
php public/index.php
```

1.1 版权和版本声明
----
* 版权和版本的声明位于头文件和定义文件的开头（参见示例1-1），主要内容有：    
    * (1)版权信息。   
    * (2)文件名称，标识符，摘要。   
    * (3)当前版本号，作者/修改者，完成日期。   
    * (4)版本历史信息。   

示例 1-1:
```php
/**
*
* [填写文件内容简介]
* 
* @Copyright (C), 2011-2012, XX公司, da.jin@tinyphp.org
* @Name [文件名]
* @Author [coder name]
* @Version Beta 1.0
* @Date Sun Dec 25 23:35:04 CST 2011
* @Class List:
*     1.  [填写Class列表简介]
* @Function List:
*     1.    [填写Function列表简介]
* @History
*     <author>    <time>                        <version >   <desc>
*       [coder]      当前日期时间，以GMT标准格式填写           第一次建立该文件
*/
```
 

 

1.2 文件结构
----

包文件和类文件主要有三部分内容：
* 定义文件开头处的版权和版本声明（参见示例1-1）。   
* 对一些命名空间和类的引用。   
* 程序实现（包括数据和代码)。  

> 假设控制器文件的名称为 `MainController.php`, 我们定义文件的结构参见示例1-2。    

示例 1-2:
```php
<?php
// 版权和版本声明见示例1-1，此处省略。

namespace App\Controller;  // 命名空间

use Tiny\MVC\Controller\Controller; // 引用的类

 // 类的实现体
class MainController extend Controller
{
    public function indexAction(…)
    {
        ... 
    }
}
?>
```
