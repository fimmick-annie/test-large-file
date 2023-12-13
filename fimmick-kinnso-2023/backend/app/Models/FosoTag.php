<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FosoTag extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'foso_tags';




}
