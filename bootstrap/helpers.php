<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('asset_storage')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function asset_storage($path = '')
    {
        if (! empty($path)) {
            return asset(Storage::url($path));
        }
        return $path;
    }
}

if (! function_exists('normalise_phone_number')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function normalise_phone_number($phone_number = '')
    {
        return preg_replace("/[^0-9\+]/", '', $phone_number);
    }
}
