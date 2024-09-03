<?php

use App\Models\MeterType;
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
        Schema::create('meter_types', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('format');
            $table->tinyInteger('status')->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
        MeterType::create(['format'=>'company format1','code'=>222,'status'=>1]);
        MeterType::create(['format'=>'company format2','code'=>333,'status'=>1]);
        MeterType::create(['format'=>'company format3','code'=>444,'status'=>1]);
        MeterType::create(['format'=>'company format4','code'=>555,'status'=>1]);
        MeterType::create(['format'=>'company format5','code'=>666,'status'=>1]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meter_types');
    }
};