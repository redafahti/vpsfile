<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->text('group_name');
            $table->text('permission');
            $table->text('created_by');
            $table->timestamps();
        });

        DB::table('groups')->insert([
            [
            'group_name' => 'Administrateur', 
            'permission' => 'a:20:{i:0;s:14:"view.customers";i:1;s:13:"add.customers";i:2;s:14:"edit.customers";i:3;s:16:"delete.customers";i:4;s:14:"view.suppliers";i:5;s:13:"add.suppliers";i:6;s:14:"edit.suppliers";i:7;s:16:"delete.suppliers";i:8;s:19:"view.productsfamily";i:9;s:18:"add.productsfamily";i:10;s:19:"edit.productsfamily";i:11;s:21:"delete.productsfamily";i:12;s:15:"view.categories";i:13;s:14:"add.categories";i:14;s:15:"edit.categories";i:15;s:17:"delete.categories";i:16;s:13:"view.products";i:17;s:12:"add.products";i:18;s:13:"edit.products";i:19;s:15:"delete.products";}',
            'created_by' => 'sys',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
};
