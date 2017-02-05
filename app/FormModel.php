<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class FormModel extends Model
{
      //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tpoly_forms';
    protected $primaryKey="ID";
    protected $guarded = ['ID'];
    public $timestamps = false;
    
     
     
}
