<?php


namespace App\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Calendar
 * @package Illuminate\Support\Facades
 * @method get
 */

class IcsData extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'IcsData';
    }
}
