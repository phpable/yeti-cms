<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Blogs extends Migration {

	/**
	 * Run the migrations.
	 */
	public function up() {

		/**
		 * Create 'yeti_blog_topics' table;
		 */
		Schema::create('yeti_blog_topics', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->string('url', 255);
			$Table->string('title', 255);

			$Table->string('description', 255)->nullable();
		});

		/**
		 * Create 'yeti_blog_posts' table;
		 */
		Schema::create('yeti_blog_posts', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->integer('topic_id')->unsigned()
				->nullable();
			$Table->foreign('topic_id')->references('id')
				->on('yeti_blog_topics')->onDelete('set null')
					->onUpdate('set null');

			$Table->integer('cover_id')->unsigned()
				->nullable();
			$Table->foreign('cover_id')->references('id')
				->on('yeti_main_resources')->onDelete('set null')
					->onUpdate('cascade');

			$Table->string('url', 255);
			$Table->string('title', 255);

			$Table->string('description', 255)->nullable();
			$Table->string('preview', 1024)->nullable();

			$Table->text('body')->nullable();
			$Table->timestamps();
		});


		/**
		 * Create 'yeti_blog_tags' table;
		 */
		Schema::create('yeti_blog_tags', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->string('title', 255);

			$Table->unique(['title', 'project_id']);
		});


		/**
		 * Create 'yeti_blog_tags2posts' table;
		 */
		Schema::create('yeti_blog_tags2posts', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->integer('tag_id')->unsigned()
				->nullable();
			$Table->foreign('tag_id')->references('id')
				->on('yeti_blog_tags')->onDelete('set null')
					->onUpdate('set null');

			$Table->integer('post_id')->unsigned()
				->nullable();
			$Table->foreign('post_id')->references('id')
				->on('yeti_blog_posts')->onDelete('set null')
					->onUpdate('set null');
		});

		/**
		 * Create 'yeti_blog_tags' table;
		 */
		Schema::create('yeti_blog_rating', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->integer('post_id')->unsigned()
				->nullable();
			$Table->foreign('post_id')->references('id')
				->on('yeti_blog_posts')->onDelete('set null')
					->onUpdate('set null');

			$Table->string('rating', 255);
		});

	}

	/**
	 * Reverse the migrations.
	 */
	public function down() {

		/**
		 * Drop 'yeti_blog_tags2posts' table;
		 */
		Schema::drop('yeti_blog_tags2posts');

		/**
		 * Drop 'yeti_blog_tags' table;
		 */
		Schema::drop('yeti_blog_tags');

		/**
		 * Drop 'yeti_blog_rating' table;
		 */
		Schema::drop('yeti_blog_rating');

		/**
		 * Drop 'yeti_blog_posts' table;
		 */
		Schema::drop('yeti_blog_posts');

		/**
		 * Drop 'yeti_blog_topics' table;
		 */
		Schema::drop('yeti_blog_topics');

		/**
		 * Drop 'yeti_blog_topics' table;
		 */
		//Schema::drop('yeti_blog_authors');
	}

}

