<?php

use App\Models\Rate;
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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('to');
            $table->integer('from');
            $table->double('cost');
            $table->text('note');
            $table->tinyInteger('status')->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->index('business_id');
        });

        Rate::create(['name'=>'Normal Rate','to'=>300, 'from' => 100,  'cost' => 0.43, 'note' => 'For normal rate', 'status'=>1, 'business_id' =>1]);
        Rate::create(['name'=>'Business Rate','to'=>100, 'from' => 0,  'cost' => 0.40, 'note' => 'For business rate', 'status'=>1, 'business_id' =>1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
};
