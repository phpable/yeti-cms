<?php
use \Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\Migrations\Migration;

class Main extends Migration {

	/**
	 * Run the migrations.
	 */
	public function up() {

		/**
		 * Create 'yeti_main_sessions' table;
		 */
		Schema::create('yeti_main_sessions', function (Blueprint $table) {
			$table->string('id')->unique();
			$table->text('payload');
			$table->integer('last_activity');
		});

		/**
		 * Create 'yeti_main_users' table;
		 */
		Schema::create('yeti_main_users', function (Blueprint $Table) {
			$Table->increments('id');
			$Table->string('name');
			$Table->string('email')->unique();

			$Table->string('password', 60);
			$Table->rememberToken();

			$Table->timestamps();
		});

		/**
		 * Create 'yeti_main_modules' table;
		 */
		Schema::create('yeti_main_modules', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->string('title', 64);
			$Table->string('description', 512);

			$Table->string('maintainer', 32);
			$Table->string('name', 32);

			$Table->string('route', 255)->nullable();

			$Table->enum('status', ['ACTIVE', 'INACTIVE', 'OUTDATED', 'CORRUPTED'])
				->default('INACTIVE');

			$Table->unique(['maintainer', 'name']);
		});

		/**
		 * Create 'yeti_main_projects' table;
		 */
		Schema::create('yeti_main_projects', function (Blueprint $Table) {
			$Table->increments('id');
			$Table->string('name', 32)->unique();
			$Table->string('title', 90);
			$Table->string('url', 255);
			$Table->text('storage')->nullable();

			$Table->timestamp('builded_at');
			$Table->timestamp('deployed_at');

			$Table->timestamps();
		});

		/**
		 * Create 'yeti_main_sources' table;
		 */
		Schema::create('yeti_main_templates', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->enum('type', ['html', 'css', 'js'])
				->default('html');

			$Table->string('owner_type', 64);
			$Table->integer('owner_id')->unsigned();

			$Table->string('name', 64);

			$Table->text('source')->nullable();
			$Table->string('hash', 32)->nullable();

			$Table->unique(['name', 'type', 'owner_type', 'owner_id', 'project_id'], 'originality');
		});

		/**
		 * Create 'yeti_main_layouts' table;
		 */
		Schema::create('yeti_main_layouts', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->string('name', 32);

			$Table->unique(['project_id','name']);

		});

		/**
		 * Create 'yeti_main_externals' table;
		 */
		Schema::create('yeti_main_externals', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('cascade')
					->onUpdate('cascade');

			$Table->integer('layout_id')->unsigned()
				->nullable();
			$Table->foreign('layout_id')->references('id')
				->on('yeti_main_layouts')->onDelete('cascade')
					->onUpdate('cascade');

			$Table->enum('type', ['script', 'style', 'canonical']);
			$Table->text('link');
		});

		/**
		 * Create 'yeti_main_metas' table;
		 */
		Schema::create('yeti_main_metas', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('cascade')
					->onUpdate('cascade');

			$Table->integer('layout_id')->unsigned()
				->nullable();
			$Table->foreign('layout_id')->references('id')
				->on('yeti_main_layouts')->onDelete('cascade')
					->onUpdate('cascade');

			$Table->enum('type', ['name', 'http-equiv']);
			$Table->string('property', 255);
			$Table->text('content');
		});

		/**
		 * Create 'yeti_main_pages' table;
		 */
		Schema::create('yeti_main_pages', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->integer('layout_id')->unsigned()
				->nullable();
			$Table->foreign('layout_id')->references('id')
				->on('yeti_main_layouts')->onDelete('set null')
					->onUpdate('set null');

			$Table->integer('template_id')->unsigned()
				->nullable();
			$Table->foreign('template_id')->references('id')
				->on('yeti_main_templates')->onDelete('set null')
					->onUpdate('set null');

			$Table->enum('builder', ['standard', 'populate', 'paginate',
				'scale', 'archive'])->default('standard');

			$Table->text('arguments')->nullable();

			$Table->string('name', 32);

			$Table->string('url', 255)->nullable();
			$Table->text('config')->nullable();

			$Table->enum('mode', ['guest', 'auth', 'regular'])
				->default('regular');

			$Table->boolean('in_sitemap')->default(0);
			$Table->boolean('is_hidden')->default(0);
		});

		/**
		 * Create 'yeti_main_snippets' table;
		 */
		Schema::create('yeti_main_snippets', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->string('name', 32);
			$Table->string('params', 512)->nullable();

			$Table->unique(['project_id', 'name']);
		});

		/**
		 * Create 'yeti_main_resources' table;
		 */
		Schema::create('yeti_main_resources', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('set null')
					->onUpdate('set null');

			$Table->string('name', 64)->nullable();
			$Table->string('type', 32)->nullable();

			$Table->enum('category', ['script', 'style', 'media']);
			$Table->string('path', 255)->nullable();

			$Table->unique(['project_id', 'category', 'name']);
		});

		/**
		 * Create 'yeti_main_strings' table;
		 */
		Schema::create('yeti_main_constants', function (Blueprint $Table) {
			$Table->increments('id');

			$Table->integer('project_id')->unsigned()
				->nullable();
			$Table->foreign('project_id')->references('id')
				->on('yeti_main_projects')->onDelete('cascade')
					->onUpdate('cascade');

			$Table->string('name', 32);
			$Table->string('value', 255);

			$Table->unique(['project_id','name']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() {

		/**
		 * Drop 'yeti_main_snippets' table;
		 */
		Schema::drop('yeti_main_snippets');

		/**
		 * Drop 'yeti_main_pages' table;
		 */
		Schema::drop('yeti_main_pages');

		/**
		 * Drop 'yeti_main_externals' table;
		 */
		Schema::drop('yeti_main_externals');

		/**
		 * Drop 'yeti_main_metas' table;
		 */
		Schema::drop('yeti_main_metas');

		/**
		 * Drop 'yeti_main_layouts' table;
		 */
		Schema::drop('yeti_main_layouts');

		/**
		 * Drop 'yeti_main_templates' table;
		 */
		Schema::drop('yeti_main_templates');

		/**
		 * Drop 'yeti_main_constants' table;
		 */
		Schema::drop('yeti_main_constants');

		/**
		 * Drop 'yeti_main_resources' table;
		 */
		Schema::drop('yeti_main_resources');

		/**
		 * Drop 'yeti_main_modules' table;
		 */
		Schema::drop('yeti_main_modules');

		/**
		 * Drop 'yeti_main_projects' table;
		 */
		Schema::drop('yeti_main_projects');

		/**
		 * Drop 'yeti_main_users' table;
		 */
		Schema::drop('yeti_main_users');

		/**
		 * Drop 'yeti_main_sessions' table;
		 */
		Schema::drop('yeti_main_sessions');
	}

}
