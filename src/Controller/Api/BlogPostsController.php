<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Model\Entity\BlogPost;
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
				'Users',
				'Thumbnails'
			]);
		});
		$this->Crud->execute();
	}

	public function add()
	{
		$this->Crud->on('beforeSave', function (Event $event) {
			/** @var BlogPost */
			$entity = $event->getSubject()->entity;
			if (empty($entity->author_id)) {
				$user = $this->getUser();
				$entity->author_id = $user->id;
			}
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
