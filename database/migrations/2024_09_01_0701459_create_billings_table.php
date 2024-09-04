<?php

use App\Enums\BillingStatus;
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

        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meter_id')->constrained('meters')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->bigInteger('usage')->default(0);
            $table->decimal('amount_paid', 18, 2)->nullable()->default(NULL);
            $table->decimal('subtotal', 18, 2)->nullable()->default(NULL);
            $table->decimal('discount_percent', 5, 2)->nullable()->default(NULL);
            $table->decimal('tax_amount', 18, 2)->nullable()->default(NULL);
            $table->decimal('grand_total', 18, 2)->nullable()->default(NULL);
            $table->unsignedSmallInteger('bill_month')->default(0);;
            $table->unsignedSmallInteger('bill_year')->default(0);;
            $table->date('bill_date')->nullable(); // Add the bill_date column
            $table->tinyInteger('status')->default(BillingStatus::UNBILLED);
            $table->dateTime('transaction_date')->nullable();
            $table->string('notes', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();


              // Add indexes and unique constraintss
              $table->index('bill_date');
              $table->index(['bill_month', 'bill_year']);
              $table->unique(['bill_month', 'bill_year', 'tenant_id']);
              $table->index('transaction_date');
              $table->index('meter_id');
              $table->index('status');
             
        });
    }





    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billings');
    }
};
