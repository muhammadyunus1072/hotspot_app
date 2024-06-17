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
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->integer('qty')->default(0)->comment("Quantity");
        });

        Schema::table('_history_transaction_details', function (Blueprint $table) {
            $table->integer('qty')->default(0)->comment("Quantity");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
        Schema::table('_history_transaction_details', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
};
