<?php

namespace App\Models;

use App\Models\Concerns\HasCode;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasCode;
    use HasFactory;

    protected static function newFactory()
    {
        return ClientFactory::new();
    }

    public static string $codePrefix = 'Cli';

    protected $table = 'clients';

    protected $primaryKey = 'code_client';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nom_client',
        'rue_client',
        'code_postal_client',
        'ville_client',
        'type_client',
        'remise_speciale',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'remise_speciale' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'code_client');
    }
}
