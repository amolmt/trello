<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Board extends Model
{
    protected $guarded = [];


    public function cards()
    {
        return $this->hasMany(Card::class,'board_id','id')->where('status', 1);
    }


}
