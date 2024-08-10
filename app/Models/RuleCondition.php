<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleCondition extends Model
{
    use HasFactory;

    protected $fillable = ['rule_id', 'condition_type_id', 'condition_value'];

    protected $casts = [
        'condition_value' => 'json',
    ];

    public $timestamps = false;

    public function rule()
    {
        return $this->belongsTo(RedirectRule::class, 'rule_id');
    }

    public function conditionType()
    {
        return $this->hasOne(ConditionType::class, 'id', 'condition_type_id');
    }
}
