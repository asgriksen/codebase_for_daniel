<?php

namespace App;

use App\Support\Model\Traits\Enums;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable,
        CanResetPassword,
        Enums,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'name',
        'online_check_at',
        'password',
        'status',
        'type',
        'verify_token',
        'verify_token_expiry'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'online_check_at',
        'verify_token_expiry'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verify_token',
        'verify_token_expiry'
    ];

    /*
     * The types of users.
     */
    const TYPE_ADMIN = 'ADMIN';
    const TYPE_USER  = 'USER';

    /**
     * Return all of the user's accounts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany( Account::class );
    }

    /**
     * Return all carts operated by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany( Cart::class );
    }

    /**
     * Return the user campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function campaigns()
    {
        return $this->hasManyThrough( Campaign::class, Account::class )->whereIsSelected(true);
    }

    /**
     * Return the users Etsy request token record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function etsy_request_token()
    {
        return $this->hasOne( EtsyRequestToken::class )->latest();
    }

    /**
     * Return a unique verify token.
     *
     * @return string
     */
    public function generateUniqueVerifyToken()
    {
        foreach( range(0,20) as $attempt )
        {
            // Generate a random 60 character token.
            //
            $token = Str::random(60);

            // If the token is not assigned then we will use it.
            //
            if( ! static::whereVerifyToken($token)->exists() )
            {
                return $token;
            }
        }
    }

    /**
     * Return the users Google Access token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function google_access_token()
    {
        return $this->hasOne( GoogleAccessToken::class );
    }

    /**
     * Return all of the user's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany( Notification::class );
    }

    /**
     * Return all of the user's preferences.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preferences()
    {
        return $this->hasMany( UserPreference::class );
    }

    /**
     * Return all of the user's products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
    public function products()
    {
        return $this->hasManyThrough( Product::class, Cart::class );
    }

    /**
     * Return all of the user's categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
    public function cartCategories()
    {
        return $this->hasManyThrough( CartCategory::class, Cart::class );
    }

    /**
     * Return all of the users role applications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function ruleApplications()
    {
        return $this->hasManyThrough( RuleApplication::class, Rule::class );
    }

    /**
     * Return all of the user's rules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rules()
    {
        return $this->hasMany( Rule::class );
    }

    /**
     * Return all of the user's selected accounts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function selected_accounts()
    {
        return $this->accounts()->isSelected(true);
    }

    /**
     * Automatically hash passwords.
     *
     * @param $password
     */
    public function setPasswordAttribute( $password )
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Return all of the user's tasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany( Task::class );
    }

    /**
     * Return utm codes created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function utm_codes()
    {
        return $this->hasMany( UtmCode::class );
    }
}