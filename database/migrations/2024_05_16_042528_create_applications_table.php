<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('description');
            $table->string('reason');
            $table->string('position');
            $table->string('department');
            $table->string('location');
            $table->date('application_date');
            $table->date('date_issued')->nullable();
            $table->date('date_returned')->nullable();
            $table->string('handler')->nullable();
            $table->string('receiver')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->index('user_id');
            $table->index('asset_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
