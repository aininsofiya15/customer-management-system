<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the customers table.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->text('address');
            $table->string('phoneNumber');              
            $table->enum('gender', ['Male', 'Female']);
            $table->date('birthday');
            $table->timestamps();                       
        });
    }

    /**
     * Drop the customers table.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
