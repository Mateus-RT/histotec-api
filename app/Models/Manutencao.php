<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Manutencao extends Model
{
    protected $fillable = [
        'ativo_id', 'tipo_manutencao_id', 'user_id',
        'descricao_problema', 'descricao_solucao', 'custo_manutencao', 'data_manutencao'
    ];

    public function ativo(): BelongsTo
    {
        return $this->belongsTo(Ativo::class);
    }

    public function tipoManutencao(): BelongsTo
    {
        return $this->belongsTo(TipoManutencao::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
