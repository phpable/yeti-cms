<?php
namespace Yeti\Blog\Model;

use \Yeti\Blog\Model\Post;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;

class Tag2Post extends AModel {
	use TProject;

	/**
	 * @var string
	 */
	protected $table = 'yeti_blog_tags2posts';

	/**
	 * @var bool
	 */
	public $timestamps = false;

}
