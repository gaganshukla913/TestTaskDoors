<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable;
    // use SoftDeletes;
    use HasRoles;
    use HasFactory;
    use Billable;
    
    protected $table = 'users';
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    protected $dates = [
        'deleted_at'
    ];

    protected $attributes = [ 
        'menuroles' => 'user',
    ];

    public function company()
    {
       return $this->hasOne(Company::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

}