<?php

namespace Pharaonic\Laravel\Helpers\Traits;

use Illuminate\Support\Str;

/**
 * Convert Model id to UUID
 */
trait HasUuidKey
{
    /**
     * Set uuid key on creating
     *
     * @return void
     */
    public static function bootHasUuidKey()
    {
        self::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * Append the key name to $fillable attribute
     *
     * @return void
     */
    public function initializeHasUuidKey()
    {
        $this->incrementing = false;
        $this->keyType = 'string';

        $this->fillable[] = $this->getKeyName();
    }
}
