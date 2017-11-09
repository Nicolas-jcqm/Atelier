<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 08/11/2017
 * Time: 00:34
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = "booking";
    protected $primaryKey = 'id';
    public $timestamps = false;
    //pour eviter que l'id soit pris pour un int
    public $incrementing = false;

}