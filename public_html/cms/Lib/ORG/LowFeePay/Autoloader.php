<?php

spl_autoload_register("Autoloader::autoload");
final class Autoloader
{
    private static $autoloadPathArray = array(
//        "juhepay"
    );

    public static function autoload($className)
    {

        foreach (self::$autoloadPathArray as $path) {
            $file = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'ORG'.DIRECTORY_SEPARATOR.'LowFeePay'.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$className.".php";
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
            if (is_file($file)) {
                include_once $file;
                break;
            }
        }
    }
    
    public static function addAutoloadPath($path)
    {
        array_push(self::$autoloadPathArray, $path);
    }
}
