<?php

declare(strict_types=1);

namespace App\Controller\Api;

use FriendsOfBabba\Core\Controller\Api\AppController;

/**
 * TicketTypes Controller
 *
 * @property \App\Model\Table\TicketTypesTable $TicketTypes
 */
class TicketTypesController extends AppController
{
	public $paginate = [
		'page' => 1,
		'limit' => 5,
		'maxLimit' => 200
	];
}

