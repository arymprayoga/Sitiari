<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPekerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pekerja', function (Blueprint $table) {
            $table->bigIncrements('id_detail_pekerja');
            $table->unsignedBigInteger('id_pekerja');
            $table->string('nama');
            $table->string('nomorKTP');
            $table->date('ttl');
            $table->string('tel');
            $table->string('alamat');
            $table->string('keahlian');
            $table->string('kondisiKhusus')->nullable();
            $table->string('agama');
            $table->string('fotoKTP');
            $table->string('fotoDiri');
            $table->timestamp('tanggalMasuk');
            $table->timestamp('tanggalKeluar')->nullable();
            $table->timestamps();
            $table->foreign('id_pekerja')->references('id')->on('pekerja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pekerja');
    }
}
