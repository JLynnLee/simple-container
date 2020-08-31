###### 简单的php类容器管理

Installation
------------
*composer require simple-container*


#####$test = new \Test\Container();
*//A::class 注入的类，['param1', 'param2'...] 注入类所需参数*


#####
$class = $test::bind(A::class, ['param1', 'param2']);
