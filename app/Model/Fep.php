<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fep extends Model
{
    protected $table = "fep";

    public function itemRecord()
    {
        return $this->hasOne('App\Model\Item', 'id', 'productId');
    }
}
