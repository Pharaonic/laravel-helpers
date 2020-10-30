<?php

namespace Pharaonic\Laravel\Helpers\Traits;

/**
 * Custom Attributes For The Model
 */
trait HasCustomAttributes
{
    /**
     * Get Callbacks
     */
    protected $_getAttributes = [];

    /**
     * Set Callbacks
     */
    protected $_setAttributes = [];

    /**
     * isConflict
     */
    public function isConflictTraits()
    {
        $traits = class_uses($this);
        $conflicts = [
            'Astrotomic\Translatable\Translatable'
        ];

        foreach ($conflicts as $conflict) {
            if (in_array($conflict, $traits)) {
                $conflict = explode('\\', $conflict);
                return strtolower($conflict[count($conflict) - 1]);
            }
        }

        return false;
    }

    /**
     * Get Attribute
     */
    public function getAttribute($key)
    {
        if (isset($this->_getAttributes[$key])) return \call_user_func_array([$this, $this->_getAttributes[$key]], [$key]);

        // Conflict
        $isConflict = $this->isConflictTraits();
        if ($isConflict) {
            $result = $this->{'__' . $isConflict . '_getAttribute'}($key);
            if ($result) return $result;
        }

        return parent::getAttribute($key);
    }

    /**
     * Set Attribute
     */
    public function setAttribute($key, $value)
    {
        if (isset($this->_setAttributes[$key])) return \call_user_func_array([$this, $this->_setAttributes[$key]], [$key, $value]);

        // Conflict
        $isConflict = $this->isConflictTraits();
        if ($isConflict) {
            $result = $this->{'__' . $isConflict . '_setAttribute'}($key, $value);
            if ($result) return $result;
        }

        return parent::setAttribute($key, $value);
    }


    /**
     * Add Attribute Getter
     */
    public function addGetterAttribute($key, $callback)
    {
        $this->_getAttributes[$key] = $callback;
    }

    /**
     * Add Attribute Setter
     */
    public function addSetterAttribute($key, $callback)
    {
        $this->_setAttributes[$key] = $callback;
    }


    /**
     *
     *          CONFLICT
     *      - Translatable
     *
     */


    public function __translatable_getAttribute($key)
    {
        [$attribute, $locale] = $this->getAttributeAndLocale($key);

        if ($this->isTranslationAttribute($attribute)) {
            if ($this->getTranslation($locale) === null) {
                return $this->getAttributeValue($attribute);
            }

            // If the given $attribute has a mutator, we push it to $attributes and then call getAttributeValue
            // on it. This way, we can use Eloquent's checking for Mutation, type casting, and
            // Date fields.
            if ($this->hasGetMutator($attribute)) {
                $this->attributes[$attribute] = $this->getAttributeOrFallback($locale, $attribute);

                return $this->getAttributeValue($attribute);
            }

            return $this->getAttributeOrFallback($locale, $attribute);
        }

        return parent::getAttribute($key);
    }

    public function __translatable_setAttribute($key, $value)
    {
        [$attribute, $locale] = $this->getAttributeAndLocale($key);

        if ($this->isTranslationAttribute($attribute)) {
            $this->getTranslationOrNew($locale)->$attribute = $value;

            return $this;
        }

        return parent::setAttribute($key, $value);
    }
}
