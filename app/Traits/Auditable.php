<?php

namespace App\Traits;

use App\Observers\GenericObserver;

trait Auditable
{
    public static function bootAuditable() {
        static::observe(GenericObserver::class);
    }
}
