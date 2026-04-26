<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_applications', function (Blueprint $table) {
            $table->id();
            
            // Dados do Formulário
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->unique();
            $table->boolean('owns_motorcycle')->default(false);
            
            // Uploads de Documentos (BI, Carta de Condução, etc)
            $table->string('id_document_path')->nullable();
            $table->string('driver_license_path')->nullable();
            
            // Controlo de Estado
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('rejection_reason')->nullable();
            
            // Auditoria e Rastreabilidade
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            
            // Relacionamento após aprovação (para evitar duplicação e rastrear a origem)
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_applications');
    }
};
