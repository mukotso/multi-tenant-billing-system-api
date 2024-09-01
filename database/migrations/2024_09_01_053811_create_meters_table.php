<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meters', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); // Name or identifier for the meter
            $table->enum('type', ['water', 'gas', 'electricity']); // Type of meter (e.g., water, gas, electricity)
            $table->string('timezone'); // Timezone of the meter
            $table->decimal('current_reading', 10, 2); // Current reading of the meter
            $table->timestamps(); 
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meters');
    }
}
