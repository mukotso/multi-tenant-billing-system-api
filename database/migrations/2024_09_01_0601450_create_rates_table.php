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
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('meter_type_id')->constrained('meter_types')->onDelete('cascade');
            $table->string('name')->unique();
            $table->integer('to');
            $table->integer('from');
            $table->double('cost');
            $table->text('note');
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            // $table->index('tenant_id');
        });

        // Rate::create(['name'=>'Normal Rate','to'=>300, 'from' => 100,  'cost' => 0.43, 'note' => 'For normal rate', 'status'=>1, 'tenant_id' =>1]);
        // Rate::create(['name'=>'Business Rate','to'=>100, 'from' => 0,  'cost' => 0.40, 'note' => 'For business rate', 'status'=>1, 'tenant_id' =>1]);
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
