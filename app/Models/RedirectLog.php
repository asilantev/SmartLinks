<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedirectLog extends Model
{
    use HasFactory;

    protected $table = 'redirects_log';

    public $timestamps = false;

    protected $fillable = ['smart_link_id', 'rule_id', 'user_ip', 'user_agent', 'referer', 'redirect_url'];

    public function smartLink()
    {
        return $this->belongsTo(SmartLink::class);
    }

    public function rule()
    {
        return $this->belongsTo(RedirectRule::class);
    }
}
