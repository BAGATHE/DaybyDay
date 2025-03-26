<?php

namespace App\Services\Product;

use App\Models\Product;

use \Ramsey\Uuid\Uuid;

class ProductService
{
    public function findOrCreateProduct($name,$price):Product{
        $product = Product::where('name','like','%'.$name.'%')->first();
        if ($product) {
            return $product;
        }
        $product = Product::create([
            'external_id'=>Uuid::uuid4()->toString(),
            'name' =>$name,
            'description'=>$name,
            'default_type'=>'days',
            'number'=>10000,
            'price'=>$price,
        ]);
        return $product;
    }

}