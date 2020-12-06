<?php

namespace App\AWS;

use Illuminate\Support\Facades\Storage;

class UploadToS3
{

    public function __construct()
    {
    }
    public function uploadImageToS3($path, $file)
    {

        $filenamewithextension = $file->getClientOriginalName();
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filenametostore = $path . $filename . '_' . time() . '.' . $extension;
        Storage::disk('s3')->put($filenametostore, 'public');

        return $filenametostore;
    }
}
