###### 简单的php类容器管理

Installation
------------
使用composer安装
~~~
composer require jlynnlee/simple-php-container
~~~

~~~
$test = new \Test\Container();
//A::class 注入的类，['param1', 'param2'...] 注入类所需参数
$class = $test::bind(A::class, ['param1', 'param2']);
~~~
