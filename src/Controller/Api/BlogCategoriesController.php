<?php

declare(strict_types=1);

namespace App\Controller\Api;

use FriendsOfBabba\Core\Controller\Api\AppController;

/**
 * BlogCategories Controller
 *
 * @property \App\Model\Table\BlogCategoriesTable $BlogCategories
 */
class BlogCategoriesController extends AppController
{
	public $paginate = [
		'page' => 1,
		'limit' => 5,
		'maxLimit' => 200
	];
}

