<?php
/**
 * 简单的依赖注入demo
 */
namespace Demo;


use Exception;
use ReflectionException;

class Container {

    protected static $container;

    /**
     * @param $className
     * @param $vars
     * @return mixed
     * @throws ReflectionException
     * @throws Exception
     * @author: jialin
     */
    public static function bind($className, $vars = []) {

        if (isset(self::$container[$className])) {
            return self::$container[$className];
        }

        //回调函数直接执行并返回结果
        if ($className instanceof \Closure) {
            return $className();
        }

        //获取反射类
        $reflector = new \ReflectionClass($className);
        //获取其构造函数对象
        $constructor = $reflector->getConstructor();

        //无构造函数直接返回
        if (is_null($constructor)) {
            self::$container[$className] = new $className;
            return self::$container[$className];
        }

        //获取构造函数的参数
        $params = $constructor->getParameters();
        $dependencies  = [];

        foreach ($params as $param) {
            $class = $param->getClass();
            //参数是类则递归
            if ($class) {
                $dependencies[] = self::bind($class->getName());
            } else {
                //有默认值且无传参
                if ($param->isDefaultValueAvailable() && !isset($vars[$param->getPosition()])) {
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    //对应参数位置
                    if (isset($vars[$param->getPosition()])) {
                        $dependencies[] = $vars[$param->getPosition()];
                    }	 
		}
            }
        }

        self::$container[$className] = $reflector->newInstanceArgs($dependencies);
        return self::$container[$className];
    }

    /**
     * @param $className
     * @param $vars
     * @return array|mixed
     * @throws ReflectionException
     * @author: jialin
     */
    public static function make($className, $vars = []) {
        return self::bind($className, $vars);
    }

    public static function getContainers() {
        return self::$container;
    }

    public static function remove($className) {
        unset(self::$container[$className]);
        return self::class;
    }
}
