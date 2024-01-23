<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE_NEW=1;
    const TYPE_PREORDER=2;
    const TYPE_SECONDHAND=3;
    const TYPE_FLAW=4;

    public function brand()
    {
        return $this->hasOne(Brand::class);
    }

    public function createProduct($request)
    {
        $creation = $this->create($request);
        return successResponse($creation);
    }
}
