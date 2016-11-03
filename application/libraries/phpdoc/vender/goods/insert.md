# insert

##  URL
    http://127.0.0.1/soco/back-end/index.php/goods/insert

##  支持格式
    json

##  HTTP请求方式
    post

##  是否需要登录
    false

##  请求参数
| 字段                     |   必选            |   类型及范围    | 说明                               |
|:-------------------------|:----------------- |:----------------|:-----------------------------------|
|amount|true|integer|商品总的数量|
|price|true|integer|选购商品的数量|
|name|true|string|商品名称|
|extra|false|array|商品购买时的附加条件（如颜色，型号等）|


##  请求示例
```json
{
    
}
```

##  注意事项
    

##  返回结果
```json
{
    code : 300100,
	msg : 插入新商品成功,
	data : 数据,
}
```

##  返回字段说明
| 字段                     |   类型           | 说明                               |
|:-------------------------|:-----------------|:-----------------------------------|
|code|integer|接口返回码|
|msg|string|接口返回信息|
|data|array|接口返回数据|


### 系统信息
- method_attribute：public
- fileName：/www/code/soco/back-end/application/controllers/goods.php
- startLine：31
- endLine：54
- sys_info：该方法占用22行, 代码块优化的比较好!
