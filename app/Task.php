<?php

namespace App;

use App\Support\Model\Traits\Enums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use Enums,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'progress',
        'result_error',
        'result_success',
        'result_total',
        'status',
        'type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    const TYPE_AD_CREATE = 'AD_CREATE';
    const TYPE_AD_UPDATE = 'AD_UPDATE';
    const TYPE_AD_REMOVE = 'AD_REMOVE';

    const STATUS_CREATED  = 'CREATED';
    const STATUS_PROGRESS = 'PROGRESS';
    const STATUS_FINISHED = 'FINISHED';

    /**
     * Return the user account the task belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }

    /**
     * Return the ad the task belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function ad()
    {
        return $this->belongsTo( Ad::class );
    }
}
