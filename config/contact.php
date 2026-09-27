<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp number
    |--------------------------------------------------------------------------
    |
    | Where enquiries about a piece are sent. Write it in international form
    | without the leading plus, for example 905321112233 — wa.me accepts
    | digits only. Leave it empty and the site falls back to the contact form,
    | so an unset number never leaves a dead button on a product page.
    |
    */

    'whatsapp' => env('CONTACT_WHATSAPP'),

];
