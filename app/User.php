<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'group',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function showUsersByGroup($group, $mgnome = false)
    {
        $users = User::where('group', $group)->where('is_master_gnome', $mgnome)->get();
        return $users;
    }
    public function showUserByName($name)
    {
        $user = User::where('name', $name)->first();
        return $user;
    }
    public function hasGroup($group, $isMgnome = false)
    {
        if ($isMgnome && $this->group == $group && $this->is_master_gnome == $isMgnome) return true;
        if ($isMgnome && $this->group == $group && $this->is_master_gnome != $isMgnome) return false;
        if ($this->group == $group) return true;
        else return false;
    }
}
