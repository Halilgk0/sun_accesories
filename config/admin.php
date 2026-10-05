<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Where the panel lives
    |--------------------------------------------------------------------------
    |
    | The path the catalogue is edited from. It is deliberately unguessable
    | and never linked to from the site, so it stays out of search results and
    | out of a visitor's way. Change it and the old address stops working.
    |
    | Leave it empty and no panel routes are registered at all, which is what
    | should happen anywhere the panel has not been deliberately switched on.
    |
    */

    'path' => env('ADMIN_PATH'),

    /*
    |--------------------------------------------------------------------------
    | The password that opens it
    |--------------------------------------------------------------------------
    |
    | A secret address alone is a weak lock: it lands in browser history, in
    | screenshots, and in the hands of anyone who is shown it once. The
    | password is the part that actually keeps the catalogue safe.
    |
    | Without one the panel refuses to open, so a half-finished setup cannot
    | leave the products exposed.
    |
    */

    'password' => env('ADMIN_PASSWORD'),

];
