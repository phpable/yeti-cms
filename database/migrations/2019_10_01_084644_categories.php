<?php
use \Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\Migrations\Migration;

class Categories extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		Schema::table('yeti_blog_posts', function(Blueprint $Table) {
			$Table->text('groups')
				->nullable()->after('body');
		});
	}

	/**
	 * @return void
	 */
	public function down() {

		/**
		 * Drop related column;
		 */
		Schema::table('yeti_blog_posts', function(Blueprint $Table) {
			$Table->dropColumn('groups');
		});
	}
}
