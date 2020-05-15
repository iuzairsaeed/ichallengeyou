<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title');
            $table->longText('description');
            $table->timestamp('start_time');
            $table->unsignedInteger('duration_days');
            $table->unsignedInteger('duration_hours');
            $table->unsignedInteger('duration_minutes');
            $table->string('file')->default('no-image.png');
            $table->string('location')->nullable();
            $table->unsignedDecimal('amount', 8, 2);
            $table->boolean('is_approved')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('challenges');
    }
}
