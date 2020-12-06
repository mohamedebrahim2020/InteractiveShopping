<?php

namespace App\Models;

use App\AWS\UploadToS3;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price',
    ];
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function storeProduct($name, $price, $images)
    {
        $product = Product::create([
            'name' => $name,
            'price' => $price,

        ]);
        foreach ($images as $image) {
            $name = $image->getClientOriginalName();
            $img = Image::create([
                'url' => $name,
            ]);
            $image->move(public_path() . '/storage/files', $name);
            $uploadToS3 = new UploadToS3();
            $uploadToS3->uploadImageToS3("products/", $image);
            $product->images()->save($img);
        }

        return $product;
    }
}
