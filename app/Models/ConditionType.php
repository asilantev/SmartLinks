<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionType extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function conditions()
    {
        return $this->hasMany(RuleCondition::class, 'condition_type_id');
    }
}
