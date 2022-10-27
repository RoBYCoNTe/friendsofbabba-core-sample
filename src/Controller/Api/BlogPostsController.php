<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\ORM\Query;
use FriendsOfBabba\Core\Controller\Api\AppController;

/**
 * BlogPosts Controller
 *
 * @property \App\Model\Table\BlogPostsTable $BlogPosts
 */
class BlogPostsController extends AppController
{
	public $paginate = [
		'page' => 1,
		'limit' => 5,
		'maxLimit' => 200
	];

	public function index()
	{
		$this->Crud->on('beforePaginate', function (Event $event) {
			/** @var Query */
			$query = $event->getSubject()->query;
			$query = $query->contain([
				'Authors.UserProfiles',
				'BlogCategories',
				'Users'
			]);
		});
		$this->Crud->execute();
	}

	public function view()
	{
		$this->Crud->on('beforeFind', function (Event $event) {
			/** @var Query */
			$query = $event->getSubject()->query;
			$query = $query->contain([
				'Authors.UserProfiles',
				'BlogCategories',
				'Media',
				'Thumbnails',
				'Users',
			]);
		});
		$this->Crud->execute();
	}
}
