# command
适用于yaf的命令运行,可创建自己的需求命令<br/>

## Command功能演示
    需要将项目下的a文件复制到项目根目录
    且注意a文件中的$config变量为正确的yaf config路径

| 命令        |
| ---        |
| php a make:command  指令名:运行口令      |
| php a route:list #仅在安装了anstou/route才有效,否则报错        |
| php a make:module {module_name} #创建模块        |
| php a make:control {module}/{control} #创建控制器,如果模块不存在会顺便创建模块        |

