<?php


namespace Anst\Command;

class PackageInstall
{
    public static function install($a)
    {
        $library = realpath(__DIR__ . '/../../../../..' . DIRECTORY_SEPARATOR . 'application/library');
        if (!is_dir($library)) {
            throw new \Exception($library . '目录不存在,请确定是否为yaf项目!');
        }
        $path = realpath(__DIR__ . '/../../../../../a');
        $commandPath = realpath(__DIR__ . '/../../../../..' . DIRECTORY_SEPARATOR . 'application/library' . DIRECTORY_SEPARATOR . 'Commands');
        if (!is_dir($commandPath)) mkdir($commandPath);
        copy(__DIR__ . '/a', $path);
    }
}