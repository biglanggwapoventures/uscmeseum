<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @property string $firstname
 * @property string $lastname
 * @property string $gender
 * @property string $contact_number
 * @property string $email
 * @property string $user_role
 * @property-read string $created_at
 * @property-read string $updated_at
 *
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable;

    const DEFAULT_PASSWORD = 'hellworld';
    const ROLE_STANDARD = 'standard';
    const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'gender',
        'contact_number',
        'email',
        'password',
        'user_role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 
     *
     * @var array
     */
    protected $appends = [
        'full_name'
    ];


    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function isRole($role)
    {
        return strtolower($role) === strtolower($this->user_role);
    }
}
