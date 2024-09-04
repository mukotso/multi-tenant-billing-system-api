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
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('meter_type_id')->constrained('meter_types')->onDelete('cascade');
            $table->string('timezone'); // Timezone of the meter
            $table->decimal('previous_reading', 10, 2); //previous reading
            $table->decimal('current_reading', 10, 2); // Current reading of the meter
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
        Schema::dropIfExists('meters');
    }

    
}
