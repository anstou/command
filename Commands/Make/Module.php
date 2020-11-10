<?php


namespace Commands\Make;


use Commands\KernelCommand;

class Module extends KernelCommand
{
    /**
     * 命令的名字
     * @var string
     */
    protected string $name = '创建模块文件夹';

    /**
     * 运行命令
     * @var string
     */
    protected $command = 'make:module';

    /**
     * 命令的作用
     * @var string
     */
    protected string $description = '创建一个yaf模型文件夹,一般在application/modules下';

    /**
     * 接受的参数名
     * 根据值的排序来对应 如果get获取数组中不存在的将会报错
     * @var array
     */
    protected array $params = [
        'module'
    ];


    public function handle()
    {
        $module = ucfirst(strtolower($this->get('module', '')));
        if (!preg_match("/^[a-z|A-Z]*$/", $module)) throw new \Exception('$module需要为纯英文');

        if (empty($module)) throw new \Exception('$module不能为空');
        $modulesPath = \Yaf_Application::app()->getAppDirectory() . DIRECTORY_SEPARATOR . 'modules';
        if (!is_dir($modulesPath)) mkdir($modulesPath, 0775, true);
        $modulePath = $modulesPath . DIRECTORY_SEPARATOR . $module;
        if (is_dir($modulePath)) {
            $this->log('模块已存在');
        } else {
            if (mkdir($modulePath, 0775, true)) {
                mkdir($modulePath . DIRECTORY_SEPARATOR . 'controllers', 0775, true);
                mkdir($modulePath . DIRECTORY_SEPARATOR . 'viers', 0775, true);
                $this->log($module . '模块创建成功,如需要被加载,需要在配置文件ini中[application.modules]字段加入该模块名');
            } else {
                $this->log($module . '模块创建失败,无法写入');
            }
        }
    }

}