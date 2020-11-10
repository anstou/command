<?php


namespace Commands\Make;


use Commands\KernelCommand;

class Control extends KernelCommand
{
    /**
     * 命令的名字
     * @var string
     */
    protected string $name = '创建控制器';

    /**
     * 运行命令
     * @var string
     */
    protected string $command = 'make:controller {module}/{controller}';

    /**
     * 命令的作用
     * @var string
     */
    protected string $description = '如果指定的模块不存在,则会创建该模块,并发出通知';

    /**
     * 接受的参数名
     * 根据值的排序来对应 如果get获取数组中不存在的将会报错
     * @var array
     */
    protected array $params = [
        'moduleController',//要创建控制器的模块名/控制器
    ];


    public function handle()
    {
        $moduleController = explode('/', $this->get('moduleController'));
        $module = ucfirst(strtolower($moduleController[0] ?? ''));
        $controller = ucfirst(strtolower($moduleController[1]));

        if (!preg_match("/^[a-z|A-Z]*$/", $module) || empty($module)) throw new \Exception('module需要为纯英文');
        if (!preg_match("/^[a-z|A-Z]*$/", $controller) || empty($controller)) throw new \Exception('controller需要为纯英文');

        $app = \Yaf_Application::app();
        $modulePath = \Yaf_Application::app()->getAppDirectory() . 'modules' . DIRECTORY_SEPARATOR . $module;
        if (!is_dir($modulePath)) {
            $argv = ['a', 'make:module', $module];
            $argc = count($argv);
            (new \Commands\KernelCommand())->run($argc, $argv);
        }

        $controllerPath = $modulePath . DIRECTORY_SEPARATOR . 'controllers';

        $text = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'controller.anst');

        $name_suffix = boolval(ini_get('yaf.name_suffix'));
        $controllerName = $name_suffix ? $controller . 'Controller' : 'Controller' . $controller;

        $text = str_replace('{name}', $controllerName, $text);

        $filename = $controllerPath . DIRECTORY_SEPARATOR . $controller . '.php';
        if (file_exists($filename)) {
            $this->log('创建失败,控制器已存在:' . realpath($filename));
        } else {
            file_put_contents($controllerPath . DIRECTORY_SEPARATOR . $controller . '.php', $text);
            $this->log('创建成功:' . realpath($filename));
        }

    }

}