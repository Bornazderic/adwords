<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exports extends Model
{
    protected $table = 'exports';

    protected $guarded = [];

    public function export()
    {
        return $this->belongsTo(User::class);
    }

}
