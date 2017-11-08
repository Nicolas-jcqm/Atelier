<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 07/11/2017
 * Time: 11:01
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{

    protected $table = 'list';
    protected $primaryKey = 'id';
    public $timestamps = false;

}