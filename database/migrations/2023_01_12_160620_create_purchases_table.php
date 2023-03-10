<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases' , function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('book_id')->constrained('books');
            $table->float('price');
            $table->boolean('for_rent');
            $table->float('pending_amount');
            $table->timestamp('payment_due')->nullable();
            $table->timestamp('book_return_due')->nullable();
            $table->timestamp('book_issued_at')->nullable();
            $table->timestamp('book_returned_at')->nullable();
            $table->enum('mode' , ['online' , 'offline']);
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
