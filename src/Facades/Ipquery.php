<?php

namespace TuUsuario\Ipquery\Facades;

use Illuminate\Support\Facades\Facade;

class Ipquery extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ipquery';
    }
}