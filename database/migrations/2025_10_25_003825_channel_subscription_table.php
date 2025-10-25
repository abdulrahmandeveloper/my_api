<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('channel_subscription', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('subscription_id');
            $table->date('subscribed_at');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('channel_subscription');
    }
};
