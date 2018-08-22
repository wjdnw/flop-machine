<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/21
 * Time: 16:42
 */

namespace Wjdnw\FlopMachine\Facades;


use Illuminate\Support\Facades\Facade;

class FlopMachineFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'flopmachine';
    }
}