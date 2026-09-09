<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Server extends Model

{
    protected $fillable = [
        'name',
        'host',
        'api_port',
        'api_user',
        'api_password',
        'status',
    ];
    public function vpnAccounts(): HasMany
{
    return $this->hasMany(VpnAccount::class);
}
}
