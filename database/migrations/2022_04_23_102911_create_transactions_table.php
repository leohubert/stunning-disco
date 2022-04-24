<?php

use App\Models\Company;
use App\Models\Employee;
use App\Models\Product;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->morphs('buyer');

            $table->integer('nb_products');
            $table->foreignIdFor(Product::class);

            $table->foreignIdFor(Company::class);
            $table->foreignIdFor(Employee::class, 'responsible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
