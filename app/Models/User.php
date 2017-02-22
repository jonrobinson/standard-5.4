<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];
    protected $hidden = [ 'id', 'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'email_confirmed' => 'boolean',
    ];

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->token = 'usr_' . str_random(24);
        });
    }

    /***************************************************************************************
     ** SCOPES
     ***************************************************************************************/

    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    public function scopeByToken($query, string $token)
    {
        return $query->where('token', $token);
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public function updateMe(array $data)
    {
        if (array_get($data, 'first_name') && array_get($data, 'last_name')) {
            $this->first_name = $data['first_name'];
            $this->last_name = $data['last_name'];
        }
        if (array_get($data, 'email')) {
            $this->email = $data['email'];
        }
        $this->save();
    }

    public function updatePassword(string $password)
    {
        $this->password = Hash::make($password);
        $this->save();
    }

    public function setEmailConfirmed()
    {
        $this->email_confirmed = true;
        $this->save();
    }

}
