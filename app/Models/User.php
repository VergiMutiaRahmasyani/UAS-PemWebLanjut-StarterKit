<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Berita;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get all beritas for the user.
     */
    public function berita()
    {
        return $this->hasMany(Berita::class);
    }
    
    // Tidak perlu mengoverride method roles() dan permissions()
    // karena sudah disediakan oleh trait HasRoles
}
