<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 08/11/2017
 * Time: 00:36
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pot extends Model
{
    protected $table = "pot";
    protected $primaryKey = 'id';
    public $timestamps = false;
}