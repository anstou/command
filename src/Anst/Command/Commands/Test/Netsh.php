<?php


namespace Command\Commands\Test;


use Command\KernelCommand;

class Netsh extends KernelCommand
{

    protected $name = '列出windows中所有转发的';

    const NETSH_SHOW_ALL = 'netsh interface portproxy show all';

    //ip+端口监听转发
    const NETSH_ADD_IPV4_ADDRESS = 'netsh interface portproxy add v4tov4 listenaddress={localaddress} listenport={listenport} connectaddress={destaddress} connectport={connectport}';

    //监听端口全部转发
    const NETSH_ADD_IPV4_PORT = 'netsh interface portproxy add v4tov4 listenport={listenport} connectaddress={connectaddress} connectport={connectport}  protocol=tcp';

    const NETSH_DELETE_IPV4 = 'netsh interface portproxy delete v4tov4 listenaddress={localaddress} listenport={listenport}';

    const NETSH_DELETE_ALL = 'netsh interface portproxy reset';

    public function handle()
    {
        $a = shell_exec(static::NETSH_SHOW_ALL);
        $arr = explode("\n", $a);
        if (count($arr) > 5) {
            $all = [];
            $arr = array_slice($arr, 5);
            foreach ($arr as $str) {
                if (!empty($str)) $all[] = preg_replace("/\s+/", '|', $str);
            }
            $this->log(...$all);
        } else {
            $this->log('无转发端口');
        }
    }
}