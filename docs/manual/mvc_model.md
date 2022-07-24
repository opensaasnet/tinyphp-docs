Model 模型
====

* Model是属于MVC流程中的数据层处理。
  * Model负责处理所有的外部数据源，包括db, Nosql, api等；   
  * Model负责处理本地数据源Config，Cache。   
  * Model作为业务数据处理的模型层，只需和Controller交换数据，所有接口需要做到无状态模式，即不直接与控制器层的Request, Router, Dispatch, Bootstrap,Controller,Response等产生数据交换。   
  * Model基于数据层Data构建与外部数据源的数据交换。   
  
* Model 通过DI方式自动实例化，并按需注入到控制器。  
```php
class MainController extends Controller
{
   /**
   * @autowired 通过自动注解标识可实例化模型
   */
   private UserInfoModel $usermodel;
   
   /**
   * 派发时，根据模型类型自动注入模型实例。
   */
   public function indexAction(UserInfoModel $usermodel)
   {
   }
   ...
}
```
profile 模型层配置
----
```php
/**
 * Application的模型层设置
 * 
 * model.namespace 
 *      相对app.namespace下的模型层命名空间  如\App\Model
 *      
 * model.src  模型层的存放目录
 */
$profile['model']['namespace'] = 'Model';
$profile['model']['src'] = 'models/';

```


具体参考可见 [Tiny\MVC/MVC库](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc.md)
