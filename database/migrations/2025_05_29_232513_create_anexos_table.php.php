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
            // Sua migração para a tabela 'anexos'
            Schema::create('anexos', function (Blueprint $table) {
                $table->id('id_anexo'); // Se o ID é 'id_anexo'
                $table->foreignId('id_chamado')->constrained('chamados')->onDelete('cascade'); // Relacionamento com 'chamados'
                $table->string('nome_arquivo'); // Nome original do arquivo
                $table->string('file_path'); // Caminho onde o arquivo será armazenado no servidor (EX: 'public/uploads/anexos/arquivo.jpg')
                $table->string('mime_type')->nullable(); // Opcional: tipo MIME do arquivo (image/jpeg, application/pdf, etc.)
                $table->unsignedBigInteger('size')->nullable(); // Opcional: tamanho do arquivo em bytes
                $table->timestamps(); // Adiciona created_at e updated_at (data_upload será created_at)
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anexos');
    }
};
