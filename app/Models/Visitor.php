<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visitor extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function getDate(){
      return Carbon::parse($this->tarih)->locale('tr_TR')->isoFormat('lll');
    }
}
