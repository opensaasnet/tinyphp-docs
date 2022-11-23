Viewer 视图层
====

Viewer是属于MVC流程中的视图渲染处理。
* 在基于敏捷开发理念的MVC模式下，Web/H5的应用开发，才会有视图层管理。
* 在IOS/Android/小程序的MVVM模式下，视图层由前端应用负责渲染，Tinyphp作为后端，仅需要提供数据接口。
* 管理后台应用多数是web应用。


### Viewer的视图引擎和前端库

Web应用的视图引擎包括`Smarty` , `PHP原生`，`Template自定义视图渲染引擎`,可自行扩展。
TinyPHP集成了tinyphp-ui，通过它组织和管理html/css/js

### View的实例化

Viewer 在控制器内，可通过DI方式自动实例化，并按需注入到控制器。  
也可以通过控制器，直接调用视图层。

```php
class MainController extends Controller
{
   /**
   * @autowired 通过自动注解标识可实例化模型
   */
   private View $view;
   
   /**
   * 派发时，根据模型类型自动注入模型实例。
   */
   public function indexAction(View $view)
   {
        $this->assign();  //视图变量
        $this->display(...); //渲染显示
        $this->fetch(); //获取渲染的视图内容
     
        $view->assign();  //视图变量
        $view->display(...); //渲染显示
        $view->fetch(); //获取渲染的视图内容
                
   }
   ...
}
```

### View在profile.php 内的配置项

```php
/**
 * 视图设置
 * 
  *  默认模板解析的扩展名列表
 *      .php PHP原生引擎
 *      .tpl Smarty模板引擎
 *      .htm|.html Template模板引擎
 * 
 * view.src 
 *      视图模板存放的根目录
 *      example: application/views/
 *      
 * template_dirname
 *      视图模板目录下的默认存放子级目录
 *          example: views/default/
 * 
 * lang.enabled
 *      是否加载对应的语言包子级目录
 *      example: views/zh_cn/ 查找不到后，去默认模板目录里views/default/寻找
 *      
 * view.compile  
 *      视图模板编译后的存放目录
 * 
 * view.config 
 *      视图模板的配置存放目录
 * 
 * view.assign 
 *      视图模板的预先加载配置数组
 * 
 * view.engines 视图引擎配置
 *      engine => 视图模板解析类名
 *      ext => []  可解析的模板文件扩展名数组
 *      config => [] 引擎初始化时的配置
 *      
 *      Example: Template引擎的插件配置
 *          engine => \Tiny\MVC\View\Engine\Template:
 *          config => [plugins => [
 *              'plugin' => '\Tiny\MVC\View\Engine\Template\Url' , 'config' => []
 *      ]]
 *      
 * view.helper 视图助手配置
 *      helper => classname 助手类名
 *      config => [] 助手初始化时的配置
 *  
 *  view.cache.enabled 是否开启视图缓存
 *      默认不开启
 *  
 *  view.cache.dir 缓存目录
 *  view.cache.ttl 缓存过期时间
 */
$profile['view']['basedir'] = 'views/';
$profile['view']['theme'] = 'default';
$profile['view']['lang'] = true;     //自动加载语言包
$profile['view']['paths'] = [];
$profile['view']['compile'] = '{runtime}/view/compile/';
$profile['view']['config']  = '{runtime}/view/config/';
$profile['view']['assign'] = [];

// 引擎和助手配置
$profile['view']['engines'] = [];
$profile['view']['helpers'] = [];

/*
 * 视图的全局静态资源配置
 * 
 * view.static.basedir 视图静态资源的存储根目录
 *      {static} => $profile['src']['static']
 * 
 * view.static.public_path 视图静态资源的公开访问地址
 *      /static/ 当前域名下的绝对路径
 *      http://demo.com/static 可指定域名
 *      
 * view.static.engine 是否开启视图解析的模板引擎
 *      当前支持css js 图像文件的自动解析和生成
 *       
 * view.static.minsize 静态模板引擎复制文件的最小大小
 *      小于最小大小的，直接注入文件内容
 *      大于最小大小的，在staic目录下生成对应外部文件在html下加载
 *      
 * view.static.exts 
 *      view.static.engine支持解析的静态资源扩展名     
 *      
 */

$profile['view']['static']['basedir'] = '{static}';
$profile['view']['static']['public_path'] = '/static/';
$profile['view']['static']['engine'] = true;
$profile['view']['static']['minsize'] = 2048;
$profile['view']['static']['exts'] = ['css', 'js','png', 'jpg', 'gif'];

```


具体参考可见 
-----
-----

[Tiny\MVC/MVC库](https://github.com/tinyphporg/tinyphp-docs/blob/master/docs/lib/mvc.md)
