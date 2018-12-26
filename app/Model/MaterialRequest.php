<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    protected $table = 'material_request';

    public function materialDetail(){
    	return $this->hasOne('App\Model\MaterialRequestDetail', 'mrId', 'id');
    }
}
