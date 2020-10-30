<?php

namespace Pharaonic\Laravel\Helpers\Traits;

use Illuminate\Support\Facades\Hash;

/**
 * Convert Model id to UUID
 */
trait HasHashedPassword
{
    /**
     * Set uuid key on creating
     *
     * @return void
     */
    public static function bootHasHashedPassword()
    {
        self::creating(function ($model) {
            $model->password = Hash::make($model->password);
        });

        self::updating(function ($model) {
            if ($model->isDirty('password')) {
                $model->password = Hash::make($model->password);
            }
        });
    }
}
