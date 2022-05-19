<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    public function getDate()
    {
        return Carbon::parse($this->created_at)->locale('tr_TR')->isoFormat('lll');
    }

    public function getVisitors()
    {
        return $this->hasMany('App\Models\Visitor', 'link_id', 'id')->get();
    }

    public function getTodayCount()
    {
        return $this->getVisitors()->whereBetween('tarih', [Carbon::today(), now()])->count();
    }

    public function getMonthCount()
    {
        return $this->getVisitors()->whereBetween('tarih', [Carbon::today()->startOfMonth(), now()])->count();
    }

    public function visitorCount()
    {
        return $this->hasMany('App\Models\Visitor', 'link_id', 'id')->count();
    }

    public function getUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'owner')->withTrashed();
    }

}
