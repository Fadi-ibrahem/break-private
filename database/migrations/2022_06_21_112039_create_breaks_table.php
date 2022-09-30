<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breaks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('date')->index();
            $table->boolean('is_approved')->nullable();
            $table->enum('reason', ['wc', 'prayer', 'lunch', 'other', 'coffee', 'smoking']);
            $table->integer('time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('breaks');
    }
}
