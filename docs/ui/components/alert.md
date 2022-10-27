sweetalert2
====
SweetAlert2 是一个漂亮、响应式、可定制、易用的(WAI-ARIA) JavaScript 弹窗(弹出框)
官方文档地址: [https://sweetalert2.github.io/](https://sweetalert2.github.io/)

## 加载方式
### meta预加载   
 
```html
  <meta name="tinyphp-ui" content="preload=alert;" />
```

### JS惰性加载   

```javascript
// 异步加载select2库
$.load('alert').then(function(alert){
    alert 包含了Swal2对象
    也可通过$.tiny.swal2引用
    ...
    // 默认使用方式
    $('#demo').alert()
});
```

## 基于tinyphp-ui的扩展

### $.fn.selectx()

* 基于jquery.fn扩展
> 无需预加载和异步加载即可直接使用. 但获取对象需要通过.then()

```javascript
$('#select-demo').select2x();

// 等效于
$.load('select2').then((select2) => {
    $('#select-demo').select2();
})

```

### [data-bs-widget="select2"]

* bootstrap小部件
> 无需预加载和异步加载即可使用.  

```html
<select data-bs-widget="select2">
<option value="1">1</option>
</select>

```


## $.tiny.select2
> $..tiny.select2包含加载的Select2对象

```javascript

$.load('select2').then(function(select2){
    var selectE = new select2($('#select-demo'), {})
        ...
    );
    ...
});
```