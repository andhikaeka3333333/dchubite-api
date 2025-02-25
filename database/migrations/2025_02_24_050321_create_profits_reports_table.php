<?php

// database/migrations/2025_02_24_000003_create_profits_reports_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('profits_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->unique();
            $table->bigInteger('total_revenue'); // Total pendapatan
            $table->bigInteger('total_cost'); // Total modal
            $table->bigInteger('total_profit'); // Total keuntungan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profits_reports');
    }
};

