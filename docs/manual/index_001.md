入口文件
====

1.0 Hello world!
----

> 一个完整的Tiny框架应用，将包括三个部分:   
* 入口文件(demo/public/index.php);   
* 应用程序集合/application(demo/application/);   
* 框架的标准库集合(Tiny Framework For PHP，下文将简称为Tiny)(src/Tiny)    


1.1 通用程序目录结构
----

```
application/   // 应用程序集合
public/       // 公共入口
    index.php   // 入口文件
    static/     // 公共静态资源
        js/
        css/
        img/
        font/
runtime/       // 运行时文件夹
    cache/     // 缓存
    view/     // 视图
    pid/     // 守护进程所需的pid存放目录
    log/    // 日志文件
vendor/     // composer存放目录
    tinyphporg/
        tinyphp-framework
tools/   // 工具存放目录
```
1.2 入口文件实例
----
```php
// 项目根路径 该常量必须设置
define('TINY_ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// composer autoload
define('TINY_COMPOSER_FILE', TINY_ROOT_PATH . 'vendor/autoload.php');
require_once TINY_COMPOSER_FILE;

/* 
 * APPLICATION_PATH 该常量必须设置
*  Application run 自动识别web/console/rpc模式
*/
define('APPLICATION_PATH', dirname(__DIR__) . '/application/');
\Tiny\Tiny::createApplication(APPLICATION_PATH, APPLICATION_PATH . 'config/profile.php')->run();
```

#### 1.2.1 设置运行时环境参数  
```php
...
    include_once TINY_COMPOSER_FILE;
}

/* 设置是否设置运行时环境参数 */
\Tiny\Tiny::setENV([
]);
 
/* 设置application主目录的常量; 该常量必须设置 
*  Application run 自动识别web/console模式
*  Profile.php 为应用配置文件
*  ->run() Application运行
*/
define('APPLICATION_PATH', dirname(__DIR__) . '/application/');
\Tiny\Tiny::createApplication(APPLICATION_PATH, APPLICATION_PATH . 'config/profile.php')->run();
```


#### 1.2.2 必须的常量
<b>APPLICATION_PATH</b> 定义为application程序集的文件夹路径，必须设置;

#### 1.2.3 参考标准库
> [Tiny\Runtime:运行时标准库](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/lib/runtime.md)  
> [Tiny\MVC:MVC库](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/lib/mvc.md)  


### 1.3 在不同运行环境下的入口文件使用

#### 1.3.1 Web环境
* 动态路由  
> 参数c为控制器名称,缺省为main,参数c和缺省控制器可在profile.php中的controller节点修改   
> 参数a为动作名称，缺省为index,参数a和缺省动作名可在profile.php中的action节点修改   
> Main控制器 即为调用 application/controllers/web/Main下的控制器类   
> 具体配置可参考: [Proptrites/应用配置:  demo/application/config/profile.php](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/profile-003.md)   
```shell
#以上部署在正确可运行的环境
curl "http://localhost/index.php?c=main&a=index"
```

* 伪静态路由   
> 需在profile.php内 router.enable  = TRUE;   
> 并配置对应域名下的router.rules为router.pathinfo   
> 具体配置可参考: [Proptrites/应用配置:  demo/application/config/profile.php](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/profile-003.md)    



#### 1.3.2 Console
* 命令行模式
```php
#缺省设置控制器和动作
php demo/public/index.php /main/index

#长参输入
php demo/public/index.php --c=main --a=index

#短参输入 
php demo/public/index.php -c main -a index -h -x=2
```


* 服务端服务  
> 示例仅支持CentOS下的service/systemctl方式，除示例外也可自行编写脚本
```shell
cp tools/tiny-daemon.sh /etc/init.d/
chkconfig --level 345 tiny-daemon on
#修改tiny-daemon.sh文件夹中的SERVICE_INDEX_FILE 为正确的入口文件地址
service tiny-daemon start
service tiny-daemon stop
````
* --id 为profile.php的daemon.policys对应配置节点,缺省为daemon.id配置的默认节点 
* -d --daemon=start/stop 控制守护进程开启/关闭
> 具体配置可参考: [Proptrites/应用配置:  demo/application/config/profile.php](https://github.com/tinyphporg/tinyphp/blob/master/docs/manual/profile-003.md) 
```php
#缺省默认配置
php demo/public/index.php -d

#开启
php demo/public/index.php --id=tinyphp-daemon -d

#关闭
php demo/public/index.php --id=tinyphp-daemon -d stop

```
### 1.4 入口文件在Nginx .conf里的设置

#### 1.4.1 Nginx 不存在的访问全部指向index.php
```
location /
{
    try_files $uri $uri /index.php$is_args$args;
}

location ~ .*\.php(/.*)?
{
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_index index.php;
    
    #支持PHP中$_SERVER[pathinfo]显示,不影响框架路由正常工作
    fastcgi_split_path_info ^(.+\.php)(/.+)$; 
    fastcgi_param PATH_INFO $fastcgi_path_info; 
    fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;    
    
    include fastcgi.conf;
}

```


#### 1.4.3 静态文件的前端缓存配置
```
location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
{
    expires      30d;
}

location ~ .*\.(js|css)?$
{
    expires      12h;
}
```
