<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/21
 * Time: 16:41
 */

namespace Wjdnw\FlopMachine;

use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Illuminate\Config\Repository;
use Wjdnw\FlopMachine\Games\Base;

class FlopMachineIndex
{
    /**
     * @var SessionManager
     */
    protected $session;
    /**
     * @var Repository
     */
    protected $config;
    /**
     * Packagetest constructor.
     * @param SessionManager $session
     * @param Repository $config
     */
    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }
    /**
     * @param string $msg
     * @return string
     */
    public function test_rtn($msg = '', array $param)
    {
//        dd($this->config);
        $config_arr = $this->config->get('flopMachineConfig.options');

        $base = new Base( $this->config, $param );
        $base->initPoker();

        return [$msg.' <strong>from your custom develop package!</strong>>', $config_arr];
    }
}