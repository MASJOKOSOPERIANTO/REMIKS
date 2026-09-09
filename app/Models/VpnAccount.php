<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VpnAccount extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'user_id',
        'server_id',
        'vpn_type',
        'username',
        'password',
        'vpn_ip',
        'to_port',
        'dst_port',
        'status',
        'auto_renew',
        'started_at',
        'expires_at',
    ];

    /**
     * Casting tipe data.
     */
    protected function casts(): array
    {
        return [
            'to_port' => 'integer',
            'dst_port' => 'integer',
            'auto_renew' => 'boolean',
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke customer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke server MikroTik.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
