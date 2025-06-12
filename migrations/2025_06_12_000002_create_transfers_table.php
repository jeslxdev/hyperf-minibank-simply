<?php

declare(strict_types=1);

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payer_id');
            $table->unsignedBigInteger('payee_id');
            $table->decimal('value', 15, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'notified', 'not_authorized', 'reversed']);
            $table->string('message')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('payer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
}
