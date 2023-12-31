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
        Schema::table('users', function (Blueprint $table) {
            //
      
                $table->string('password_reset_token')->nullable();
                $table->timestamp('password_reset_token_created_at')->nullable();
            });
        }
    
        public function down()
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['password_reset_token', 'password_reset_token_created_at']);
            });
        }
    
    
};
