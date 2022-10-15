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
        Schema::create('settlements', function (Blueprint $table) {
            // Columns
            $table->id();
            $table->string(column: 'name', length: 100);
            $table->integer(column: 'key', unsigned: true);
            $table->string(column: 'zone_type', length: 1);
            $table->tinyInteger(column: 'settlement_type_id', unsigned: true);
            $table->string(column: 'zip_code_id', length: 5);
            $table->timestamps();

            // Relations
            $table->foreign('settlement_type_id')->references('id')->on('settlement_types');
            $table->foreign('zip_code_id')->references('id')->on('zip_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settlements');
    }
};
