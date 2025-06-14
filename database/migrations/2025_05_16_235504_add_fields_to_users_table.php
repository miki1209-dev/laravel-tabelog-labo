<?php

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
		Schema::table('users', function (Blueprint $table) {
			$table->string('postal_code')->nullable()->after('password');
			$table->text('address')->nullable()->after('postal_code');
			$table->string('phone')->nullable()->after('address');
			$table->enum('role', ['free', 'premium'])->default('free')->after('phone');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('postal_code');
			$table->dropColumn('address');
			$table->dropColumn('phone');
			$table->dropColumn('role');
		});
	}
};
