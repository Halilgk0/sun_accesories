<?php

namespace App\Support;

/**
 * Builds wa.me links for enquiries.
 *
 * A wa.me link opens WhatsApp with the message already typed into the input
 * box and waits there: nothing is sent until the visitor presses send
 * themselves, which is the point — the shop never sends on their behalf.
 */
class WhatsApp
{
    /** The number as wa.me wants it: digits only, no plus, no spaces. */
    public static function number(): ?string
    {
        $digits = preg_replace('/\D/', '', (string) config('contact.whatsapp')) ?? '';

        return $digits === '' ? null : $digits;
    }

    /** Null when no number is configured, so callers can fall back. */
    public static function link(string $message): ?string
    {
        $number = self::number();

        if ($number === null) {
            return null;
        }

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
