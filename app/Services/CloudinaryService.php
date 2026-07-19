<?php

namespace App\Services;
use Cloudinary\Cloudinary;

class CloudinaryService
{
       protected Cloudinary $cloudinary;

       public function __construct()
    {
        $this->cloudinary = new Cloudinary(
            config('cloudinary.url')
        );
    }

    /**
     * Upload an image to Cloudinary.
     *
     * @param string $filePath
     * @param array $options
     * @return array
     */
    public function upload(string $filePath, array $options = []): array
{
    $result = $this->cloudinary
        ->uploadApi()
        ->upload($filePath, $options);

    return [
        'public_id' => $result['public_id'],
        'url' => $result['secure_url'],
        'width' => $result['width'],
        'height' => $result['height'],
        'format' => $result['format'],
    ];
}
//Delete
public function delete(string $publicId): array
{
    $result = $this->cloudinary
        ->uploadApi()
        ->destroy($publicId);

    return [
        'public_id' => $publicId,
        'result' => $result,
    ];
}
}