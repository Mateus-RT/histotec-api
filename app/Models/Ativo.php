<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ativo extends Model
{
    protected $fillable = [
        'patrimonio',
        'modelo',
        'ano_aquisicao',
        'valor_estimado',
        'status',
        'tipo_item_id',
        'marca_id',
        'setor_id'
    ];

    public function tipoItem(): BelongsTo
    {
        return $this->belongsTo(TipoItem::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function setor(): BelongsTo
    {
        return $this->belongsTo(Setor::class);
    }

    public function manutencoes(): HasMany
    {
        return $this->hasMany(Manutencao::class);
    }
}
