<?php

namespace App\Models;

use App\AWS\UploadToS3;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price','user_id'
    ];
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function storeProduct($obj)
    {
        $product = Product::create(
            [
            'name' => $obj->name,
            'price' => $obj->price,
            'user_id' => $obj->user()->id,

            ]
        );
        foreach ($obj->file('image') as $image) {
            $name = $image->getClientOriginalName();
            $img = Image::create(
                [
                'url' => $name,
                ]
            );
            $image->move(public_path() . '/storage/files', $name);
            $uploadToS3 = new UploadToS3();
            $uploadToS3->uploadImageToS3("products/", $image);
            $product->images()->save($img);
        }


        return $product;
    }

    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    public function getBuyableDescription($options = null)
    {
        return $this->name;
    }

    public function getBuyablePrice($options = null)
    {
        return $this->price;
    }
}
