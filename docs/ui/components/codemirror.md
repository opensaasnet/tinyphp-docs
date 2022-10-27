codemirror
====
一款功能非常强大的可自定义的在线编辑器  
官方文档地址: [https://codemirror.net/docs/](https://codemirror.net/docs/)

## 加载方式
### meta预加载   
 
```html
  <meta name="tinyphp-ui" content="preload=codemirror;" />
```

### JS惰性加载   

```javascript
// 异步加载codemirr库
$.load('codemirror').then(function(codemirror){
    codemirror 包含了Codemirror库所有的组件和类
    也可通过$.tiny.codemirror引用
    
    ...
});
```

## 基于tinyphp-ui的扩展

### $.fn.highlight()

* 基于jquery.fn扩展
> 无需预加载和异步加载即可直接使用.   

```javascript
// 根据class=language-(javascript|php|css|...)加载不同的语言高亮
// 具体支持语言可参考@codemirror/language-data的定义文件，有几十种。
$(...).highlight();

```

### [data-bs-widget="highlight"]

* bootstrap 小部件
> 无需预加载和异步加载即可使用.  

```html
<div class="language-php" data-bs-widget="highlight">
    echo "hello world!";
</div>
```

### code[class="language-*"]

```html
// <code> .language-code方式
<code class="language-php">
    echo "hello world!";
</code>
```


## $.tiny.codemirror 
> $..tiny.codemirror包含所有加载的codemiior对象

```javascript

$.load('codemirror').then(function(codemirror){
    var editview = new codemirror.EditViewor(
        ...
    );
    ...
});

```




