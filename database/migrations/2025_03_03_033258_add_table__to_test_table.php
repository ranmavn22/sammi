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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('unit')->nullable();
            $table->string('province_code')->nullable();
            $table->string('province_name')->nullable();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('province_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('district_name')->nullable();
        });

        Schema::create('districts_hn', function (Blueprint $table) {
            $table->id();
            $table->string('unit')->nullable();
            $table->string('ma_dvi_qly')->nullable();
            $table->string('ma_dvi_ctren')->nullable();
            $table->string('district_name')->nullable();
        });

        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string('id_dia_chinh')->nullable();
            $table->string('ma_dvi_qly')->nullable();
            $table->string('ma_dvi_ctren')->nullable();
            $table->string('ma_don_vi_hanh_chinh')->nullable();
            $table->string('ten_don_vi_hanh_chinh')->nullable();
            $table->integer('cap_don_vi')->nullable();
            $table->string('area')->nullable();
            $table->date('date')->nullable();
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test', function (Blueprint $table) {
            //
        });
    }
};
