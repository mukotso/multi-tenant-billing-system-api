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
        Schema::create('consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meter_id')->constrained('meters')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->date('consumption_date');
            $table->decimal('total_consumption', 8, 2);
            $table->double('rate', 8, 2)->default(0);
            $table->enum('status', ['pending', 'billed'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
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
        Schema::dropIfExists('consumptions');
    }
};

