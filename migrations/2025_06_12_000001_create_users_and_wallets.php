<?php

declare(strict_types=1);

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;

class CreateUsersAndWallets extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cria a tabela wallet
        Schema::create('wallet', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('number', 32)->unique(); // número da carteira
            $table->string('type', 32); // tipo de carteira: personal, business, etc
            $table->string('currency', 8)->default('BRL'); // moeda
            $table->boolean('active')->default(true); // status da carteira
            $table->timestamp('last_transaction_at')->nullable(); // última transação
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        // Cria a tabela users
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name');
            $table->string('cpf_cnpj', 20)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('type_person', ['personal', 'business']);
            $table->unsignedBigInteger('wallet_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('wallet_id')->references('id')->on('wallet')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('wallet');
    }
}
