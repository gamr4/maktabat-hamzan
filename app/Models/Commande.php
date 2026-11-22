<?php

namespace App\Models;

use App\Models\Concerns\HasCode;
use Database\Factories\CommandeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasCode;
    use HasFactory;

    protected static function newFactory()
    {
        return CommandeFactory::new();
    }

    public static string $codePrefix = 'Com';

    protected $primaryKey = 'numero_commande';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'date_commande',
        'condition',
        'code_client',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'code_client');
    }

    public function produits()
    {
        return $this->belongsToMany(
            Produit::class,
            'ligne_commandes',
            'numero_commande',
            'code_produit'
        )->withPivot('quantite');
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        $clientCodes = $user->clients()->pluck('code_client');

        return $query->whereIn('code_client', $clientCodes);
    }

    public function montantBrut(): float
    {
        return (float) $this->produits->sum(fn ($p) => ($p->prix_unitaire ?? 0) * ($p->pivot->quantite ?? 1));
    }

    public function montantRemise(): float
    {
        $remisePourcentage = $this->client?->remise_speciale ?? 0;

        return $this->montantBrut() * ($remisePourcentage / 100);
    }

    public function montantNet(): float
    {
        return $this->montantBrut() - $this->montantRemise();
    }
}
