<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Users\UserUpdatable;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static User create(array $input)
 * @property string $status
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $profile_image
 * @property string $address
 * @property string $phone
 * @property DateTime $last_login
 * @property int $id
 */
class User extends Authenticatable
{
    use HasApiTokens , HasFactory , Notifiable , HasRoles, UserUpdatable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name' ,
        'last_name' ,
        'email' ,
        'password' ,
        'profile_image' ,
        'address' ,
        'phone' ,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password' ,
        'remember_token' ,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime' ,
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_IN_ACTIVE = 'inactive';
    const STATUS_BANNED = 'banned';
    const TYPE_CUSTOMER = 'customer';
    const TYPE_ADMIN = 'admin';

    public function offlineEntries()
    {
        return $this->hasMany(OfflineEntry::class, 'user_id');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
