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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('book_id')->constrained('books');
            $table->enum('status', ['open', 'closed']);
            $table->float('price');
            $table->enum('payment_status', ['completed', 'pending', 'half-paid']);
            $table->dateTime('payment_due_date');
            $table->float('pending_amount');
            $table->boolean('for_rent');
            $table->dateTime('book_return_date')->nullable();
            $table->dateTime('book_issued_date');
            $table->enum('purchase_mode', ['online', 'offline']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
};
