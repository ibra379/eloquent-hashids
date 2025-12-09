<?php

// config for DialloIbrahima/EloquentHashids

return [
    /*
    |--------------------------------------------------------------------------
    | Hashid Length
    |--------------------------------------------------------------------------
    |
    | The minimum length of the generated hashids. The actual length may be
    | longer depending on the encoded value, but will never be shorter.
    |
    */
    'length' => 16,

    /*
    |--------------------------------------------------------------------------
    | Hashid Alphabet
    |--------------------------------------------------------------------------
    |
    | The alphabet used to generate hashids. Must contain at least 16 unique
    | characters. The default includes lowercase, uppercase letters and digits.
    |
    */
    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',

    /*
    |--------------------------------------------------------------------------
    | Salt
    |--------------------------------------------------------------------------
    |
    | The salt used to generate unique hashids. By default, this uses your
    | application key. You can override this with a custom salt.
    |
    | WARNING: Changing this will invalidate all existing hashids!
    |
    */
    'salt' => env('HASHID_SALT', env('APP_KEY', '')),

    /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | An optional prefix to prepend to all hashids. This can help identify
    | the type of resource the hashid represents.
    |
    */
    'prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Suffix
    |--------------------------------------------------------------------------
    |
    | An optional suffix to append to all hashids.
    |
    */
    'suffix' => '',

    /*
    |--------------------------------------------------------------------------
    | Separator
    |--------------------------------------------------------------------------
    |
    | The separator used between prefix/suffix and the hashid.
    |
    */
    'separator' => '-',
];
