<?php
namespace Plugins\Province\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}