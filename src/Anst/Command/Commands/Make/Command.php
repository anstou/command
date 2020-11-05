<?php


namespace Anst\Command\Commands\Make;


use Anst\Command\KernelCommand;

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
    protected $params = [
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
        $dirPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $namespace;
        if (!file_exists($dirPath)) mkdir($dirPath);


        $text = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'command.anst');
        $name = ucfirst(strtolower($command[1]));
        $text = str_replace('{name}', $name, $text);
        $text = str_replace('{namespace}', $namespace, $text);
        $text = str_replace('{command}', strtolower($namespace) . ':' . strtolower($name), $text);
        file_put_contents($dirPath . DIRECTORY_SEPARATOR . $name . '.php', $text);
    }

}