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
            $path = $app->getAppDirectory() . '/../a';
            $commandPath = $app->getAppDirectory() . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'Commands';
            if (!is_dir($commandPath)) mkdir($commandPath);
        } else {
            throw new \Exception('非yaf内调用需要手动将a文件复制到根目录');
        }
        copy(__DIR__ . '/a', $path);
    }
}