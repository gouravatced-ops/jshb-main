<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait EncryptedRouteKey
{
    /**
     * Get the route key for the model.
     * Returns encrypted ID instead of plain ID
     */
    public function getRouteKey()
    {
        return encryptId($this->getKey());
    }

    /**
     * Retrieve a model by its route key.
     * Decrypts the route key and finds the model by ID
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $decrypted = decryptId($value);
        
        if ($decrypted === null) {
            return null;
        }

        return $this->where($field ?? $this->getRouteKeyName(), $decrypted)->first();
    }

    /**
     * Retrieve many models by their route keys.
     */
    public function resolveManyRouteBindings($values, $field = null)
    {
        $decrypted = [];
        foreach ($values as $value) {
            $dec = decryptId($value);
            if ($dec !== null) {
                $decrypted[] = $dec;
            }
        }

        return $this->whereIn($field ?? $this->getRouteKeyName(), $decrypted)->get();
    }
}
