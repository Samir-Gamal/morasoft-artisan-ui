<?php

if (!function_exists('getConvenientName')) {
    /**
     * Get a convenient name
     * 
     * @param  string $name
     * @return string
     */
    function getConvenientName($name): string
    {
        return  implode(
            array_map(
                fn($name) => ucfirst((\Str::singular(strtolower($name)))),
                explode(' ', trim($name))
            )
        );
    }
}
