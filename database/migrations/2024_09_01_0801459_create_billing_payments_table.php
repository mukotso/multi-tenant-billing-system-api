<?php

use App\Enums\PaymentStatus;
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
        Schema::create('billing_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('billing_id')->constrained('billings')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->date('date');
            $table->tinyInteger('status')->default(PaymentStatus::PAID);
            $table->string('notes', 255)->nullable();
            $table->string('payment_ref')->nullable();


            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

        
          $table->index('billing_id');
          $table->index('tenant_id');
          $table->index('payment_method_id','payment_method_id_index');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_payments');
    }
};