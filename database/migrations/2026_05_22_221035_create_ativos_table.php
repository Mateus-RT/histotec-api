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
        Schema::create('ativos', function (Blueprint $table) {
            $table->id();
            $table->string('patrimonio', 50)->nullable()->unique();
            $table->string('modelo', 100);
            $table->unsignedSmallInteger('ano_aquisicao')->nullable();
            $table->decimal('valor_estimado', 10, 2)->nullable();
            $table->enum('status', ['ativo', 'manutencao', 'reserva', 'sucateado'])->default('ativo');

            // Chaves Estrangeiras das tabelas divididas
            $table->foreignId('tipo_item_id')->constrained('tipos_item')->onDelete('restrict');
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('restrict');
            $table->foreignId('setor_id')->constrained('setores')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ativos');
    }
};
