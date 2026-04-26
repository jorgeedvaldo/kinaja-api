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
        Schema::create('restaurant_applications', function (Blueprint $table) {
            $table->id();
            
            // Dados do Formulário
            $table->string('name'); // Nome do proprietário ou responsável
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('nif');
            
            // Uploads de Documentos (Alvará comercial, etc)
            $table->string('business_license_path')->nullable();
            
            // Controlo de Estado
            $table->string('status')->default('pending');
            $table->text('rejection_reason')->nullable();
            
            // Auditoria
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            
            // Relacionamentos após aprovação
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->cascadeOnDelete();
            
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
        Schema::dropIfExists('restaurant_applications');
    }
};
