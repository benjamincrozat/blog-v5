<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('github_id')->index()->after('name');
        });

        User::all()->each(function (User $user) {
            $user->update([
                'github_id' => $user->github_data['id'],
            ]);
        });
    }

    public function down() : void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('github_id');
        });
    }
};
