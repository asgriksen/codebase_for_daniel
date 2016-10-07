<?php

namespace App;

use App\Support\Model\Traits\Constants;
use App\Support\Model\Traits\Enums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ReflectionClass;

class Rule extends Model
{
    use Constants,
        Enums,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'interval',
        'layer',
        'name',
        'report_repeated',
        'report_email',
        'strategy'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    const TYPE_HOURLY    = 'HOURLY';
    const TYPE_DAILY     = 'DAILY';
    const TYPE_WEEKLY    = 'WEEKLY';
    const TYPE_MONTHLY   = 'MONTHLY';
    const TYPE_QUARTERLY = 'QUARTERLY';
    const TYPE_YEARLY    = 'YEARLY';
    const TYPE_TOTAL     = 'TOTAL';

    const ACTION_TYPE_RUN   = 'RUN';
    const ACTION_TYPE_PAUSE = 'PAUSE';

    /**
     * Return all of the applications (models) assigned to the rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany( RuleApplication::class );
    }

    /**
     * Return all the rule actions assigned to the rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions()
    {
        return $this->belongsToMany( Action::class, 'rule_actions', 'rule_id', 'action_id');
    }

    /**
     * Return all the conditions assigned to the rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conditions()
    {
        return $this->belongsToMany( Condition::class, 'rule_conditions', 'rule_id', 'condition_id' );
    }

    /**
     * Return the user the rule belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
