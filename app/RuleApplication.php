<?php

namespace App;

use App\Support\Model\Traits\Constants;
use Illuminate\Database\Eloquent\Model;

class RuleApplication extends Model
{
    use Constants;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rule_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'layer_id',
        'layer_type',
        'rule_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Return the rule that the rule application belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rule()
    {
        return $this->belongsTo( Rule::class );
    }

    /**
     * Get all of the owning control model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function layer()
    {
        return $this->morphTo();
    }
}
