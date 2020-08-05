<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GemTypes extends Model
{
    /*
    public static function GetLengthMoreThanFive()
    {
        return static::where(DB::raw('LENGTH(type)'), '>', '5')->get();
    }
    */

    public static function getActiveGemTypes()
    {
        return static::where('active', 'true')->get();
    }
    public static function getId($name)
    {
        return static::where('type', $name)->first();
    }
}
