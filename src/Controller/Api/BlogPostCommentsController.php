<?php

declare(strict_types=1);

namespace App\Controller\Api;

use FriendsOfBabba\Core\Controller\Api\AppController;

/**
 * BlogPostComments Controller
 *
 * @property \App\Model\Table\BlogPostCommentsTable $BlogPostComments
 */
class BlogPostCommentsController extends AppController
{
	public $paginate = [
		'page' => 1,
		'limit' => 5,
		'maxLimit' => 200
	];
}

