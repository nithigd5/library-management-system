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
        Schema::create('book_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('book_name');
            $table->string('book_author')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->text('description')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_requests');
    }
};
