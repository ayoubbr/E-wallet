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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->string('status');
            $table->decimal('amount');
            $table->unsignedBigInteger('sender_wallet');
            $table->foreign('sender_wallet')->references('id')->on('wallets')->onDelete('cascade');
            $table->unsignedBigInteger('receiver_wallet');
            $table->foreign('receiver_wallet')->references('id')->on('wallets')->onDelete('cascade');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
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
        Schema::dropIfExists('transfers');
    }
};
