<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasFactory;
    public function linkCount(){
      return $this->hasMany('App\Models\Link','owner','id')->count();
    }
    public function getCreatedDate(){
      return Carbon::parse($this->created_at)->locale('tr_TR')->isoFormat('lll');
    }
}
