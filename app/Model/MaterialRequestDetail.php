<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MaterialRequestDetail extends Model
{
    protected $table = 'material_request_detail';

    public function materialRequestItem(){
    	return $this->hasOne('App\Model\Item', 'id', 'productId');
    }
}
