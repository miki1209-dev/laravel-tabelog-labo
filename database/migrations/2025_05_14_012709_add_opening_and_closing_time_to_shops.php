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
		Schema::table('shops', function (Blueprint $table) {
			$table->time('opening_time')->nullable()->after('opening_hours');
			$table->time('closing_time')->nullable()->after('opening_time');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shops', function (Blueprint $table) {
			$table->dropColumn('opening_time');
			$table->dropColumn('closing_time');
		});
	}
};
