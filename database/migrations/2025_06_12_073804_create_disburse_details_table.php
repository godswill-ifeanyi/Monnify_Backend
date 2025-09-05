<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disburse_details', function (Blueprint $table) {
            $table->id(); $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->decimal("total_fee", 10, 2)->default(0.00);
            $table->string('destination_bank_name');
            $table->string('destination_account_number');
            $table->string('destination_bank_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disburse_details');
    }
};
