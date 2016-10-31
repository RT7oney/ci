##控制器的统一注册管理
SocoCtr.php

* 其他控制器的命名需全部为小写

因为我们使用了[CI文档插件](http://ym1623.github.io/codeigniter_apidoc/codeigniter_apidoc/setup/setup.html)该插件要求扫描到的控制器文件全部命名为小写，希望各位开发者注意

* 其他控制器在调用之前需要继承该类

该类在初始化的时候会：

	1.载入soco配置
	2.载入公共封装的辅助方法
	3.对所有入参和出参都进行日志记录
	4.验证系统级参数签名
* 请在业务控制器中严格使用方法<font style="color:red">$this->__Response()</font>对请求进行响应

##模型的统一注册管理
SocoMod.php

* 其他模型在调用之前需要继承该模型

该类目前所包含的方法有：

	1.对其他模型的表注册
	2.增、删、改、查的数据库方法封装

##钩子和配置

* 钩子

为了统一管理控制器和模型，所有开发者编写的控制以及模型请在config/soco\_controllers.php 和 config/soco\_models.php下进行注册，这里使用CI框架里面的钩子类预埋在所有控制器调用之前对模型和控制器文件夹进行扫描，如果没有注册的控制器或者模型会报错

* 配置

soco.php为soco平台的配置文件，会在SocoCtr.php中自动加载，您可以设置您需要的配置在该文件内


##接口调用规则：

每调用soco平台下接口，请在每个调用的uri后面加入参数timestamp(时间戳)和sign(签名)

##签名算法：

首先将所需要请求的post参数全部使用字典序排序，并且使用&key=value的形式进行字符串拼接得到字符串tmp\_string，然后把soco平台接口调用token(开发者请联系管理员索取)和当前时间戳timestamp和tmp\_string进行字符串拼接然后进行sha1加密得到签名sign