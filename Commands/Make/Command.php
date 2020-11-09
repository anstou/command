<?php


namespace Commands\Make;


use Commands\KernelCommand;

class Command extends KernelCommand
{
    /**
     * 命令的名字
     * @var string
     */
    protected string $name = '创建command命令';

    /**
     * 命令的作用
     * @var string
     */
    protected string $description = '创建一个command命令';

    /**
     * 接受的参数名
     * 根据值的排序来对应
     * @var array
     */
    protected array $params = [
        'command'
    ];

    public function handle()
    {
        $command = explode(':', $this->get('command', ''));
        if (count($command) !== 2) {
            $this->log('command格式错误,正确命令为php a make:command test:test');
            exit;
        }
        $namespace = ucfirst(strtolower($command[0]));
        $app = \Yaf_Application::app();
        if ($app) {

            $dirPath = $app->getAppDirectory() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $namespace;

        } else {
            $dirPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $namespace;
        }
        if (!file_exists($dirPath)) mkdir($dirPath);



        $text = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'command.anst');
        $name = ucfirst(strtolower($command[1]));
        $text = str_replace('{name}', $name, $text);
        $namespace = 'Commands\\' . $namespace;
        $text = str_replace('{namespace}', $namespace, $text);
        $command = explode('\\', $namespace);
        $prefix = strtolower(end($command));
        $text = str_replace('{command}', $prefix . ':' . strtolower($name), $text);

        $filename = $dirPath . DIRECTORY_SEPARATOR . $name . '.php';
        if (file_put_contents($filename, $text)) {
            $this->log('创建成功,运行命令为,' . $prefix . ':' . strtolower($name));
        } else {
            $this->log('命令创建失败');
        }
    }

}