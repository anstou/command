<?php


namespace Commands\Make;


use Commands\KernelCommand;

class Action extends KernelCommand
{
    /**
     * 命令的名字
     * @var string
     */
    protected string $name = '创建控制器动作';

    /**
     * 运行命令
     * @var string
     */
    protected string $command = 'make:action {module}/{controller} {action}';

    /**
     * 命令的作用
     * @var string
     */
    protected string $description = '创建对应模块控制器的动作';

    /**
     * 接受的参数名
     * 根据值的排序来对应 如果get获取数组中不存在的将会报错
     * @var array
     */
    protected array $params = [
        'moduleController',
        'action'
    ];


    public function handle()
    {
        $moduleController = explode('/', $this->get('moduleController'));
        $module = ucfirst(strtolower($moduleController[0] ?? ''));
        $controller = ucfirst(strtolower($moduleController[1]));
        $action = strtolower($this->get('action'));

        if (!preg_match("/^[a-z|A-Z]*$/", $module) || empty($module)) throw new \Exception('module需要为纯英文');
        if (!preg_match("/^[a-z|A-Z]*$/", $controller) || empty($controller)) throw new \Exception('controller需要为纯英文');
        if (!preg_match("/^[a-z|A-Z]*$/", $action) || empty($action)) throw new \Exception('action需要为纯英文');

        $filename = realpath(\Yaf_Application::app()->getAppDirectory() . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controller . '.php');
        if ($filename) {
            $text = file_get_contents($filename);
            if (preg_match("/function[\s]+{$action}Action/i", $text)) throw new \Exception("function {$action}Action,已存在");
            $lastNum = strrpos($text, '}');
            if ($lastNum === false) throw new \Exception('没有找到结尾符号"}"');
            $left = substr($text, 0, $lastNum);
            $right = substr($text, $lastNum, strlen($text));
            $fnc = <<<FNC

    public function {$action}Action()
    {

        return false;
    }

FNC;

            if (file_put_contents($filename, $left . $fnc . $right)) {
                chmod($filename, 0777);
                $this->log($action . 'Action,创建成功');
            } else {
                $this->log($action . 'Action,创建失败');
            }
        } else {
            $this->log('控制器不存在');
        }
    }

}