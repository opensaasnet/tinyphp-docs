Response 响应实例
====

* Response通过Application的__construct()内初始化。   


### Response的实例获取
#### 为保持Model的无状态模式，Response禁止在Model层内调用。

```php

// 支持参数注入和自动注解
// controller

/**
* @autowired 自动注入
*/
protected Response $response;

/**
* 参数注入
*/
public function __construct(Response $response)
{
  ...
}

/**
* @autowired 自动注入
*/
public function getRes(Response $response) 
{
  ...
}

// 也可通过别名调用
public function getResponseByAlias(ContainerInterface $container)
{
   return $container->get('app.response');
}
```

### Response 的使用
* 在通过视图Viewer处理的情况下，视图渲染后的结果将会通过$response->appendBody加入Response内；
* Response分为两部分,Header, Body;
* Header包含Status code,响应格式，各种Header报文，Cookie,SessionID等。
* Body可为文本流和二进制流。

Response输出body
----

```php
// 通过.分隔子节点
$response->appendBody('hello world!');
$response->output();
// output "Hello world!"

// 终止并输出响应
$response->end('hello world!');
// output "hello world!"

// file
```
* Response输出JSON的方式，分为格式化和非格式化两种,如果是调试模式，将会主动关闭调试模式输出。
* Response支持JSONP回调模式。   

* JSONP模式。
```php
  // /index.php?jsonpCallback="console.log";
  $this->outJsonp(["name": "tinyphp"]);
  // output console.log({"name": "tinyphp"});
```
* 非格式化。
```php
$data = ['name' => 'tinyphp'];
$response->outJson($data);
// output "{"name": "tinyphp"}"
```

* 格式化输出JSON的使用方式
```php

// application/lang/status.php ['0' => 'success %s!'];
$response->outFormatJSON(0, 'tinyphp', ["tinyphp"]);
// output {"status": 0, "msg": "success tinyphp!", "data": ["tinyphp"]};
```

* 格式化输出JSON的profile.php 配置，将会通过status code寻找语言配置文件内的节点输出。

```php
/**
 * Application的响应实例配置
 *
 * response.formatJsonConfigId
 *    response格式化输出JSON 默认指定的语言包配置节点名
 *    status => $this->lang['status'];
 */
$profile['response']['formatJsonConfigId'] = 'status';
```

* 格式化输出JSON的函数实现。   
```php
    /**
     * 输出格式化
     *
     * @param int $status 状态码
     * @param string $param 可替换msg里面的% 最后一个参数如果是数组 则输出data
     * @example $this->response->formatJSON(0, 'msg1', 'msg2', ['aaa', 'aaaa']);
     *          {"status":0,"msg":"msg1msg2","data":["aaa","aaaa"]}
     */
    public function outFormatJSON($status = 0, ...$param)
    {
        ...
    }
}
```

Response输出Header;
----
```php
  // 设置语言编码
  $charset = "utf-8";
  $response->setCharset($charset);
  
  //响应类型；
  $response->setContentType('xml', 'utf-8');
  
  // 响应的HTTP编码
  $response->setStatusCode(404);
  // Http/1.1 404 not Found;
```

* Rseponse的内容类型ContentType;
```php
    
    /**
     * 扩展名列表
     * 
     * @var array
     */
    protected static $exts = [
        'ai' => 'application/postscript',
        'aif' => 'audio/x-aiff',
        'aifc' => 'audio/x-aiff',
        'aiff' => 'audio/x-aiff',
        'asc' => 'text/plain',
        'au' => 'audio/basic',
        'avi' => 'video/x-msvideo',
        'bcpio' => 'application/x-bcpio',
        'bin' => 'application/octet-stream',
        'c' => 'text/plain',
        'cc' => 'text/plain',
        'ccad' => 'application/clariscad',
        'cdf' => 'application/x-netcdf',
        'class' => 'application/octet-stream',
        'cpio' => 'application/x-cpio',
        'cpt' => 'application/mac-compactpro',
        'csh' => 'application/x-csh',
        'css' => 'text/css',
        'dcr' => 'application/x-director',
        'dir' => 'application/x-director',
        'dms' => 'application/octet-stream',
        'doc' => 'application/msword',
        'drw' => 'application/drafting',
        'dvi' => 'application/x-dvi',
        'dwg' => 'application/acad',
        'dxf' => 'application/dxf',
        'dxr' => 'application/x-director',
        'eps' => 'application/postscript',
        'etx' => 'text/x-setext',
        'exe' => 'application/octet-stream',
        'ez' => 'application/andrew-inset',
        'f' => 'text/plain',
        'f90' => 'text/plain',
        'fli' => 'video/x-fli',
        'gif' => 'image/gif',
        'gtar' => 'application/x-gtar',
        'gz' => 'application/x-gzip',
        'h' => 'text/plain',
        'hdf' => 'application/x-hdf',
        'hh' => 'text/plain',
        'hqx' => 'application/mac-binhex40',
        'htm' => 'text/html',
        'html' => 'text/html',
        'ice' => 'x-conference/x-cooltalk',
        'ief' => 'image/ief',
        'iges' => 'model/iges',
        'igs' => 'model/iges',
        'ips' => 'application/x-ipscript',
        'ipx' => 'application/x-ipix',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'js' => 'application/x-javascript',
        'kar' => 'audio/midi',
        'latex' => 'application/x-latex',
        'lha' => 'application/octet-stream',
        'lsp' => 'application/x-lisp',
        'lzh' => 'application/octet-stream',
        'm' => 'text/plain',
        'man' => 'application/x-troff-man',
        'me' => 'application/x-troff-me',
        'mesh' => 'model/mesh',
        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'mif' => 'application/vnd.mif',
        'mime' => 'www/mime',
        'mov' => 'video/quicktime',
        'movie' => 'video/x-sgi-movie',
        'mp2' => 'audio/mpeg',
        'mp3' => 'audio/mpeg',
        'mpe' => 'video/mpeg',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpga' => 'audio/mpeg',
        'ms' => 'application/x-troff-ms',
        'msh' => 'model/mesh',
        'nc' => 'application/x-netcdf',
        'oda' => 'application/oda',
        'pbm' => 'image/x-portable-bitmap',
        'pdb' => 'chemical/x-pdb',
        'pdf' => 'application/pdf',
        'pgm' => 'image/x-portable-graymap',
        'pgn' => 'application/x-chess-pgn',
        'png' => 'image/png',
        'pnm' => 'image/x-portable-anymap',
        'pot' => 'application/mspowerpoint',
        'ppm' => 'image/x-portable-pixmap',
        'pps' => 'application/mspowerpoint',
        'ppt' => 'application/mspowerpoint',
        'ppz' => 'application/mspowerpoint',
        'pre' => 'application/x-freelance',
        'prt' => 'application/pro_eng',
        'ps' => 'application/postscript',
        'qt' => 'video/quicktime',
        'ra' => 'audio/x-realaudio',
        'ram' => 'audio/x-pn-realaudio',
        'ras' => 'image/cmu-raster',
        'rgb' => 'image/x-rgb',
        'rm' => 'audio/x-pn-realaudio',
        'roff' => 'application/x-troff',
        'rpm' => 'audio/x-pn-realaudio-plugin',
        'rtf' => 'text/rtf',
        'rtx' => 'text/richtext',
        'scm' => 'application/x-lotusscreencam',
        'set' => 'application/set',
        'sgm' => 'text/sgml',
        'sgml' => 'text/sgml',
        'sh' => 'application/x-sh',
        'shar' => 'application/x-shar',
        'silo' => 'model/mesh',
        'sit' => 'application/x-stuffit',
        'skd' => 'application/x-koan',
        'skm' => 'application/x-koan',
        'skp' => 'application/x-koan',
        'skt' => 'application/x-koan',
        'smi' => 'application/smil',
        'smil' => 'application/smil',
        'snd' => 'audio/basic',
        'sol' => 'application/solids',
        'spl' => 'application/x-futuresplash',
        'src' => 'application/x-wais-source',
        'step' => 'application/STEP',
        'stl' => 'application/SLA',
        'stp' => 'application/STEP',
        'sv4cpio' => 'application/x-sv4cpio',
        'sv4crc' => 'application/x-sv4crc',
        'swf' => 'application/x-shockwave-flash',
        't' => 'application/x-troff',
        'tar' => 'application/x-tar',
        'tcl' => 'application/x-tcl',
        'tex' => 'application/x-tex',
        'texi' => 'application/x-texinfo',
        'texinfo' => 'application/x-texinfo',
        'tif' => 'image/tiff',
        'tiff' => 'image/tiff',
        'tr' => 'application/x-troff',
        'tsi' => 'audio/TSP-audio',
        'tsp' => 'application/dsptype',
        'tsv' => 'text/tab-separated-values',
        'txt' => 'text/plain',
        'unv' => 'application/i-deas',
        'ustar' => 'application/x-ustar',
        'vcd' => 'application/x-cdlink',
        'vda' => 'application/vda',
        'viv' => 'video/vnd.vivo',
        'vivo' => 'video/vnd.vivo',
        'vrml' => 'model/vrml',
        'wav' => 'audio/x-wav',
        'wrl' => 'model/vrml',
        'xbm' => 'image/x-xbitmap',
        'xlc' => 'application/vnd.ms-excel',
        'xll' => 'application/vnd.ms-excel',
        'xlm' => 'application/vnd.ms-excel',
        'xls' => 'application/vnd.ms-excel',
        'xlw' => 'application/vnd.ms-excel',
        'xml' => 'text/xml',
        'xpm' => 'image/x-xpixmap',
        'xwd' => 'image/x-xwindowdump',
        'xyz' => 'chemical/x-pdb',
        'zip' => 'application/zip ',
        'apk' => 'application/vnd.android.package-archive'
    ];
```

* Response的Http响应码
```php
    /**
     * http状态码
     *
     * @var array
     */
    protected static $status = [
        100 => "HTTP/1.1 100 Continue",
        101 => "HTTP/1.1 101 Switching Protocols",
        200 => "HTTP/1.1 200 OK",
        201 => "HTTP/1.1 201 Created",
        202 => "HTTP/1.1 202 Accepted",
        203 => "HTTP/1.1 203 Non-Authoritative Information",
        204 => "HTTP/1.1 204 No Content",
        205 => "HTTP/1.1 205 Reset Content",
        206 => "HTTP/1.1 206 Partial Content",
        300 => "HTTP/1.1 300 Multiple Choices",
        301 => "HTTP/1.1 301 Moved Permanently",
        302 => "HTTP/1.1 302 Found",
        303 => "HTTP/1.1 303 See Other",
        304 => "HTTP/1.1 304 Not Modified",
        305 => "HTTP/1.1 305 Use Proxy",
        307 => "HTTP/1.1 307 Temporary Redirect",
        400 => "HTTP/1.1 400 Bad Request",
        401 => "HTTP/1.1 401 Unauthorized",
        402 => "HTTP/1.1 402 Payment Required",
        403 => "HTTP/1.1 403 Forbidden",
        404 => "HTTP/1.1 404 Not Found",
        405 => "HTTP/1.1 405 Method Not Allowed",
        406 => "HTTP/1.1 406 Not Acceptable",
        407 => "HTTP/1.1 407 Proxy Authentication Required",
        408 => "HTTP/1.1 408 Request Time-out",
        409 => "HTTP/1.1 409 Conflict",
        410 => "HTTP/1.1 410 Gone",
        411 => "HTTP/1.1 411 Length Required",
        412 => "HTTP/1.1 412 Precondition Failed",
        413 => "HTTP/1.1 413 Request Entity Too Large",
        414 => "HTTP/1.1 414 Request-URI Too Large",
        415 => "HTTP/1.1 415 Unsupported Media Type",
        416 => "HTTP/1.1 416 Requested range not satisfiable",
        417 => "HTTP/1.1 417 Expectation Failed",
        500 => "HTTP/1.1 500 Internal Server Error",
        501 => "HTTP/1.1 501 Not Implemented",
        502 => "HTTP/1.1 502 Bad Gateway",
        503 => "HTTP/1.1 503 Service Unavailable",
        504 => "HTTP/1.1 504 Gateway Time-out"
    ];
```
### 可参考标准库 
[MVC/MVC:Tiny\MVC\Response](https://github.com/tinyphporg/tinyphp-dcos/blob/master/docs/manual/lib/mvc.md)
