<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'role_id', 'name', 'email', 'password', 'about', 'image', 'mobile', 'street', 'pincode', 'city', 'state', 'social_links'
	];

	protected $appends = ['is_admin'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_super_admin' => 'boolean'
    ];

    public function business()
    {
        return $this->hasOne('App\Business');
    }

	public function role()
    {
    	return $this->belongsTo('App\Role');
    }

    public function hasRole($role)
    {
    	return !! ($this->role->name == $role);
    }

    public function getRoleNameAttribute()
    {
        return $this->role->name;
    }

	public function getIsAdminAttribute()
    {
    	return !! ($this->hasRole('administrator'));
	}

	public function getIsAdvertiserAttribute()
    {
    	return !! ($this->hasRole('advertiser'));
	}

	public function getIsSubscriberAttribute()
    {
    	return !! ($this->hasRole('subscriber'));
    }

    public function bookmarks()
    {
        return $this->belongsToMany('App\Business', 'bookmarks', 'user_id', 'business_id')->withTimestamps();
    }
}
