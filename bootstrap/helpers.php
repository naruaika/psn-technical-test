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
