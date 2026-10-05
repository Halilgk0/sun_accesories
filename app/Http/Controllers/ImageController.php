<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    /**
     * Serves an uploaded photograph.
     *
     * The bytes come out of the database, so the response carries a long
     * cache lifetime and an ETag: the edge keeps a copy and PHP is asked for
     * it once rather than on every page view. A photograph is replaced by
     * uploading a new one, which gets its own id, so the old one can be
     * treated as permanent.
     */
    public function show(Image $image): Response
    {
        $contents = $image->decoded();

        return response($contents, 200, [
            'Content-Type' => $image->mime,
            'Content-Length' => (string) strlen($contents),
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'ETag' => '"'.md5($contents).'"',
        ]);
    }
}
