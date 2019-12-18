<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailUserTable extends Migration
{
    public function up(): void
    {
        Schema::create('mail_user', function (Blueprint $table) {
            createForeignKey($table, 'user_id')
                ->onDelete('cascade');

            createForeignKey($table, 'mail_id')
                ->onDelete('cascade');

            $table->timestamp('acknowledged')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_users');
    }
}
