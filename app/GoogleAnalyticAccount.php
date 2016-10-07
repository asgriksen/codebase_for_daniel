<?php

namespace App;

use App\Support\Google\Analytic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoogleAnalyticAccount extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'google_analytic_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Return a new Google Analytics API conneciton.
     *
     * @return Analytic
     */
    public function accessApi()
    {
        return new Analytic( $this->access_token );
    }
}
