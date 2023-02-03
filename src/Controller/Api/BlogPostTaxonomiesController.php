<?php

declare(strict_types=1);

namespace App\Controller\Api;

use FriendsOfBabba\Core\Controller\Api\AppController;

/**
 * BlogPostTaxonomies Controller
 *
 * @property \App\Model\Table\BlogPostTaxonomiesTable $BlogPostTaxonomies
 */
class BlogPostTaxonomiesController extends AppController
{
	public $paginate = [
		'page' => 1,
		'limit' => 5,
		'maxLimit' => 200
	];
}
