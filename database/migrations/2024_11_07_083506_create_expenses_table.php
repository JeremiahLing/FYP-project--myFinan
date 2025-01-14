<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->nullable()->after('id');
            $table->string('item_name');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->time('time')->nullable();
            $table->date('date')->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->timestamps();            
            $table->unsignedBigInteger('user_id'); // Add the user_id column
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Set foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}

