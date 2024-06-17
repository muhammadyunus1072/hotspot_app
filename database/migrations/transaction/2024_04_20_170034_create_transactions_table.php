<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_transactions', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('_history_transactions');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        
        $table->string('number');
        $table->bigInteger('user_id')->unsigned();
        $table->bigInteger('last_status_id')->unsigned()->nullable();
        $table->string('proof_of_payment')->nullable();
        
        $table->bigInteger('payment_method_id')->nullable()->unsigned();
        $table->string('payment_method_name')->nullable();
        $table->text('payment_method_description')->nullable();

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
