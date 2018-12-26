<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BomList extends Model
{
    protected $table = 'bom_list';

    public function projectList(){
    	return $this->hasOne('App\Model\Project', 'id', 'projectId');
    }
}
