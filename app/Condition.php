<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condition extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'conditions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comparison_options',
        'name'
    ];

    /*
     * List of comparison options.
     *
     * @see http://php.net/manual/en/language.operators.comparison.php
     */
    const COMPARISON_OPTIONS =  [
        '>',    // Greater Than
        '>=',   // Greater Than or Equal To
        '<',    // Less Than
        '<=',   // Less Than or Equal To
        '==',   // Equal
        '===',  // Identical
        '!=',   // Not Equal
        '!=='   // Not Identical
    ];

    /**
     * Return all the rules the condition belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany( Rule::class );
    }

    /**
     * Take the comparison option values and convert them to an array.
     *
     * @param $comparison_options
     *
     * @return array
     */
    public function getComparisonOptionsAttribute( $comparison_options )
    {
        return explode(',', $comparison_options);
    }

    /**
     * Auto-set the comparison option value(s).
     *
     * @param $comparison_options
     */
    public function setComparisonOptionsAttribute( $comparison_options )
    {
        if( is_array($comparison_options) )
        {
            $set = implode(',', $comparison_options);
        }

        $this->attributes['comparison_options'] = $set;
    }
}
