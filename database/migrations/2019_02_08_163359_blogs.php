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

			$Table->string('meta_title', 255)
				->nullable();
			$Table->string('meta_description', 255)
				->nullable();
		});

		/**
		 * Create 'yeti_blog_authors' table;
		 */
		Schema::create('yeti_blog_authors', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->string('url', 90)
				->nullable();

			$Table->string('meta_title', 255)
				->nullable();
			$Table->string('meta_description', 255)
				->nullable();

			$Table->string('name', 90);
			$Table->string('photo', 255);

			$Table->text('info')
				->nullable();
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

			$Table->integer('author_id')->unsigned()
				->nullable();
			$Table->foreign('author_id')->references('id')
				->on('yeti_blog_authors')->onDelete('set null')
					->onUpdate('set null');

			$Table->string('url', 255);
			$Table->string('title', 255);

			$Table->string('meta_title', 255)
				->nullable();
			$Table->string('meta_description', 255)
				->nullable();

			$Table->string('preview', 1024)->nullable();

			$Table->text('body')->nullable();
			$Table->boolean('is_published')->default(0);

			$Table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() {
		/**
		 * Drop 'yeti_blog_posts' table;
		 */
		Schema::drop('yeti_blog_posts');

		/**
		 * Drop 'yeti_blog_authors' table;
		 */
		Schema::drop('yeti_blog_authors');

		/**
		 * Drop 'yeti_blog_topics' table;
		 */
		Schema::drop('yeti_blog_topics');
	}

}

