<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    protected $guarded = [];

    public function site()
    {
        return $this->belongsTo(User::class);
    }
}
