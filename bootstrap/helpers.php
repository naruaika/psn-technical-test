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

if (! function_exists('strip_spaces')) {
    /**
     * Strip any whitespace.
     *
     * @param  string  $str
     * @return string
     */
    function strip_spaces($str = '')
    {
        return str_replace(' ', '', $str);
    }
}
