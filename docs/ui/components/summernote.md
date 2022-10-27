Summernote
====

Summernote 是一个简单灵活的所见即所得的 HTML 在线编辑器,基于 jQuery 和 Bootstrap 构建,支持快捷键操作,提供大量可定制的选项。   
官方地址: [https://summernote.org](https://summernote.org)   
中文文档地址: [https://www.summernote.cn/](https://www.summernote.cn/)   

## 加载方式
### meta预加载   
 
```html
  <meta name="tinyphp-ui" content="preload=summernote;" />
```

### JS惰性加载   

```javascript
// 异步加载summernote库
$.load('summernote').then(function(summernote){
    summernote 包含了Summernote库所有的组件和类
    也可通过$.fn.summernote()引用
    var summernote = $('#summernote').summernote();
    ...
});
```

## 基于tinyphp-ui的扩展

### $.fn.summernotex()

* jquery.fn扩展
> 无需预加载和异步加载summernote库即可使用

```javascript

//创建一个编辑器
var config = {}
$('.summernote').summernotex(config)

//等效于
$.load('summernote').then((summernote) => {
    $('.summernote').summernote(config);
})
```


## $.tiny.summernote
> $..tiny.codemirror包含所有加载的codemiior对象

```javascript

$.load('summernote').then(function(summernote){
    ...
});

```



