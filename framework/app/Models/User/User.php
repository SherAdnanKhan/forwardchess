<?php

namespace App\Models\User;

use App\Models\AbstractModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App\Models\User
 *
 * @property int    id
 * @property string nickname
 * @property string email
 * @property string firstName
 * @property string lastName
 * @property string mobile
 * @property string password
 * @property int    active
 * @property int    subscribed
 * @property int    isAdmin
 * @property int    ab_testing_id
 * @property string lastLogin
 * @property string created_at
 * @property string updated_at
 * @property string email_verified_at
 */
class User extends AbstractModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, MustVerifyEmailContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    use Notifiable, HasApiTokens;

    const SUPER_ADMINS = ['radudalbea@gmail.com', 'mongoosepress@gmail.com'];
    const AB_TESTING_TYPE_FIRST = 1;
    const AB_TESTING_TYPE_SECOND = 2;
    const AB_TESTING_TYPE_THIRD = 3;

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
        'password',
        'firstName',
        'lastName',
        'mobile',
        'nickname',
        'active',
        'subscribed',
        'isAdmin',
        'ab_testing_id',
        'created_at',
        'email_verified_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['lastLogin', 'deleted_at'];

    /**
     * @var array
     */
    protected $casts = [
        'active'     => 'boolean',
        'subscribed' => 'boolean',
        'isAdmin'    => 'boolean',
    ];

    /**
     * @param $value
     *
     * @return string
     */
    public function getLastLoginAttribute($value)
    {
        return $this->formatDate($value, 'Y-m-d H:i');
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        $fullName = trim("{$this->firstName} {$this->lastName}");

        return $fullName ?: $this->email;
    }

    /**
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'notifications.' . $this->id;
    }

    public function validateForPassportPasswordGrant($password)
    {
        return $this->password === md5($password);
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return in_array(strtolower($this->email), self::SUPER_ADMINS);
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname ?: ($this->firstName . ' ' . $this->lastName);
    }
}
