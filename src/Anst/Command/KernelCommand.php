<?php


namespace Anst\Command;


class KernelCommand
{
    /**
     * 命令的名字
     * @var string
     */
    protected string $name = '';

    /**
     * 命令的作用
     * @var string
     */
    protected string $description = '';

    /**
     * 接受的参数名
     * 根据值的排序来对应
     * @var array
     */
    protected $params = [];

    private $paramInit = false;

    private $_params = [];//解析的参数

    public static $argv = [];

    public function run($argc, $argv)
    {

        if ($argc > 1) {
            $command = array_map(function ($val) {
                return ucfirst(strtolower($val));
            }, explode(':', ucfirst($argv[1])));

            if (2 > count($command)) {
                static::log('传入参数错误,正确参数为 xxx:xxx');
                exit;
            }
            static::$argv = array_slice($argv, 2);
            $class = __NAMESPACE__ . '\\Commands\\' . implode('\\', $command);
            if (class_exists($class)) {
                $obj = new $class;
                if ($obj instanceof KernelCommand) {
                    if (method_exists($obj, 'handle')) {

                        $obj->handle();

                    } else {
                        static::log($class . '->handle()方法不存在');
                    }
                } else {
                    static::log($class . '类需要继承\Command\Command类');
                }
            } else {
                static::log($argv[1] . ' -> ' . $class . '类不存在');
            }


        } else {
            static::log('空白命令');
        }
    }

    public function handle()
    {
        throw new \Yaf_Exception('请实现handle方法');
    }

    /**
     * 输出方法
     * @param mixed ...$text
     */
    final protected function log(...$text)
    {
        echo implode('', $text), PHP_EOL;
    }

    final protected function get($name, $default = null)
    {
        if (!$this->paramInit) {
            $this->params = array_values($this->params);
            $values = [];
            foreach ($this->params as $k => $name) {
                $values[$name] = KernelCommand::$argv[$k] ?? null;
            }
            $this->_params = &$values;
            $this->paramInit = true;
        }
        return $this->_params[$name] ?? $default;
    }
}