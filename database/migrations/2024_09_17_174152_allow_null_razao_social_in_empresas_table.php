<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('empresas', function (Blueprint $table) {
        $table->string('razao_social')->nullable()->change();
    });
}

public function down()
{
    Schema::table('empresas', function (Blueprint $table) {
        $table->string('razao_social')->nullable(false)->change();
    });
}
};
