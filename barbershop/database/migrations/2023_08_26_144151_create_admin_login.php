<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_login', function (Blueprint $table) {
            $table->id();
            $table->text('firstname');
            $table->text('lastname');
            $table->text('email');
            $table->text('password');
            $table->text('phone');
            $table->text('created_by');
            $table->timestamps();
        });

        DB::table('admin_login')->insert([
            [
            'firstname' => 'Admin', 
            'lastname' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('51243687'),
            'phone' => '0123456789',
            'created_by' => 'sys',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_login');
    }
};
