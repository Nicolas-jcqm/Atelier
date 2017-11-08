<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 08/11/2017
 * Time: 00:38
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = "participant";
    protected $primaryKey = 'id';
    public $timestamps = 'false';
}