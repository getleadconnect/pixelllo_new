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
        Schema::table('users', function (Blueprint $table) {
            // Rename name column to firstName
            $table->renameColumn('name', 'firstName');

            // Add new columns
            $table->string('lastName')->after('firstName');
            $table->string('phoneNumber')->nullable()->after('email');
            $table->date('dateOfBirth')->nullable()->after('phoneNumber');
            $table->string('avatar')->nullable()->after('dateOfBirth');
            $table->enum('role', ['user', 'admin'])->default('user')->after('avatar');
            $table->integer('bidBalance')->default(0)->after('role');
            $table->json('addresses')->nullable()->after('bidBalance');
            $table->json('paymentMethods')->nullable()->after('addresses');
            $table->string('passwordResetToken')->nullable()->after('paymentMethods');
            $table->timestamp('passwordResetExpires')->nullable()->after('passwordResetToken');
            $table->boolean('active')->default(true)->after('passwordResetExpires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename firstName back to name
            $table->renameColumn('firstName', 'name');

            // Drop the added columns
            $table->dropColumn([
                'lastName',
                'phoneNumber',
                'dateOfBirth',
                'avatar',
                'role',
                'bidBalance',
                'addresses',
                'paymentMethods',
                'passwordResetToken',
                'passwordResetExpires',
                'active'
            ]);
        });
    }
};
