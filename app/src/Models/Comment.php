<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 08/11/2017
 * Time: 00:35
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    protected $primaryKey = 'id';
    public $timestamps = false;
}

