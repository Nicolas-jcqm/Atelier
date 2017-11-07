<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
  protected $table = "creator";
  protected $primaryKey = "id";

  public $timestamps = false;
  public $incrementing = false;
}