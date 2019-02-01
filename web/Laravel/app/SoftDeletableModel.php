<?php
/**
 * Created by IntelliJ IDEA.
 * User: EVA-HieuNV
 * Date: 12/14/2017
 * Time: 3:11 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoftDeletableModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}