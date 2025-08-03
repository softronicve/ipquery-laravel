<?php

namespace Softronic\Ipquery\Facades;

use Illuminate\Support\Facades\Facade;

class Ipquery extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Softronic\Ipquery\Ipquery::class;
    }
}
