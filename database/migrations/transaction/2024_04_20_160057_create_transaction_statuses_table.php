<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_statuses', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_transaction_statuses', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_statuses');
        Schema::dropIfExists('_history_transaction_statuses');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('transaction_id', 'transaction_statuses_transaction_id_idx');
            $table->index('name', 'transaction_statuses_name_idx');
        }

        $table->bigInteger('transaction_id')->unsigned();
        $table->string('name');
        $table->text('description')->nullable();

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
