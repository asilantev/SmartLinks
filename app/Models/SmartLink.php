<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartLink extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'default_url', 'expires_at'];
    protected $dates = ['expires_at'];

    public function redirectRules()
    {
        return $this->hasMany(RedirectRule::class);
    }

    public function redirectLogs()
    {
        return $this->hasMany(RedirectLog::class);
    }
}
