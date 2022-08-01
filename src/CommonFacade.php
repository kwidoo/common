<?php

namespace Velia\Common;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Velia\Common\Skeleton\SkeletonClass
 */
class CommonFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'common';
    }
}
