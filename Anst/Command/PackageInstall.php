<?php


namespace Anst\Command;

class PackageInstall
{
    public static function install($a)
    {
        $app = \Yaf_Application::app();
        if ($a->isDevMode() || is_null($app)) {
            //非yaf内
            $path = __DIR__ . '/../../a';
        } else if ($app) {
            $path = \Yaf_Application::app()->getAppDirectory() . '/../a';
        } else {
            throw new \Exception('非yaf内调用需要手动将a文件复制到根目录');
        }
        copy(__DIR__ . '/a', $path);
    }
}