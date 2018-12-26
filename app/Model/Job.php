<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job_production';

     /**
     * Get the fep record associated with the job production table.
     */
    public function fepRecord()
    {
        return $this->hasOne('App\Model\Fep', 'id', 'fep');
    }

    public function bomRecord()
    {
        return $this->hasOne('App\Model\BomList', 'id', 'bomId');
    }

    
}
