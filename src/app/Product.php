<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use illuminate\database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    // 本の投稿機能
    public function productStore(Int $user_id, Array $data)
    {
        $file_name = $data['product_image']->store('public/product_image');
        
    }
}