<?php namespace App\Models;

// extends
use Illuminate\Database\Eloquent\Model;

// includes
use Carbon\Carbon;

class Organization extends Model
{
    protected $table = 'organizations';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [];
    public $timestamps = true;

    /***************************************************************************************
     ** SCOPES
     ***************************************************************************************/

    public function scopebySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /***************************************************************************************
     ** RELATIONS
     ***************************************************************************************/

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'organizations_users', 'organization_id', 'user_id');
    }

    /***************************************************************************************
     ** GENERAL
     ***************************************************************************************/

    public function addUser(User $user, string $role = null)
    {
        $this->users()->attach($user->id, [
            'unq_key' => 'unq_' . $this->id . '_' . $user->id,
            'role' => $role,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function hasUser(User $user)
    {
        if ($this->users()->where('id', $user->id)->exists()) {
            return true;
        }
        return false;
    }
}