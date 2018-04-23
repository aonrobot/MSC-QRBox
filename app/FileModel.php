<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileModel extends Model
{
    use SoftDeletes;

    protected $table = 'file';

    public $timestamps = true;

    protected $dates = ['deleted_at'];
}
