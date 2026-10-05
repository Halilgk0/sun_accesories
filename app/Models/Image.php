<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/**
 * A photograph the atelier uploaded, held in the database because the
 * container's own filesystem is per-instance and discarded.
 */
class Image extends Model
{
    protected $guarded = [];

    /** Keeps the bytes out of anything that dumps or serialises a model. */
    protected $hidden = ['contents'];

    public static function store(UploadedFile $file): self
    {
        return self::create([
            'filename' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'bytes' => $file->getSize(),
            'contents' => base64_encode((string) file_get_contents($file->getRealPath())),
        ]);
    }

    public function decoded(): string
    {
        return (string) base64_decode((string) $this->contents, true);
    }

    /** What a product stores in image_path, and what the img tag asks for. */
    public function path(): string
    {
        return 'gorsel/'.$this->id;
    }
}
