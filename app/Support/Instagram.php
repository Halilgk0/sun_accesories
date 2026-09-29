<?php

namespace App\Support;

/**
 * Points enquiries at the atelier's Instagram inbox.
 *
 * Instagram has no equivalent of wa.me's ?text=: Meta's ig.me links accept
 * only a ?ref= value, which never reaches the composer and is delivered to
 * the Messaging API instead. So the link can open the conversation but cannot
 * write in it, and the enquiry text is put on the visitor's clipboard by the
 * page instead, ready to paste.
 */
class Instagram
{
    /** The username on its own: no @, no URL, no tracking parameters. */
    public static function username(): ?string
    {
        $username = trim((string) config('contact.instagram'));

        // Tolerate a pasted @handle or profile URL rather than quietly
        // producing a link that opens nothing.
        $username = preg_replace('#^.*instagram\.com/#i', '', $username) ?? $username;
        $username = ltrim($username, '@');
        $username = strtok($username, '?/') ?: '';

        return $username === '' ? null : $username;
    }

    /** Null when no account is configured, so callers can fall back. */
    public static function dmLink(): ?string
    {
        $username = self::username();

        // ig.me opens the conversation in the app when it is installed and on
        // instagram.com when it is not.
        return $username === null ? null : 'https://ig.me/m/'.$username;
    }

    public static function profileLink(): ?string
    {
        $username = self::username();

        return $username === null ? null : 'https://www.instagram.com/'.$username;
    }

    /** The handle as it is written on the page. */
    public static function handle(): ?string
    {
        $username = self::username();

        return $username === null ? null : '@'.$username;
    }
}
