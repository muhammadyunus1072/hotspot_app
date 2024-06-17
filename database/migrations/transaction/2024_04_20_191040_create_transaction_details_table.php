<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_transaction_details', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('_history_transaction_details');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('transaction_id', 'transaction_details_transaction_id_idx');
            $table->index('product_id', 'transaction_details_product_id_idx');
        }

        $table->bigInteger('transaction_id')->unsigned();
        
        $table->bigInteger('product_id')->unsigned();
        $table->string("product_name");
        $table->text("product_description")->nullable();
        $table->double("product_price");
        $table->double("product_price_before_discount")->nullable();

        $table->bigInteger("product_remarks_id")->nullable()->unsigned();
        $table->string("product_remarks_type")->nullable();

        $table->integer('qty')->default(1)->comment("Quantity");

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
