<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manutencoes', function (Blueprint $table) {
            $table->id();
            // Relacionamentos (Chaves Estrangeiras)
            $table->foreignId('ativo_id')->constrained('ativos')->onDelete('cascade');
            $table->foreignId('tipo_manutencao_id')->constrained('tipos_manutencao');
            $table->foreignId('user_id')->constrained('users'); // Técnico responsável (tabela nativa do Laravel)

            // Detalhes da Intervenção
            $table->text('descricao_problema');
            $table->text('descricao_solucao');
            $table->decimal('custo_manutencao', 10, 2)->default(0.00); // Peças substituídas ou custo estimado do reparo

            // Controle Temporal
            $table->dateTime('data_manutencao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutencaos');
    }
};
