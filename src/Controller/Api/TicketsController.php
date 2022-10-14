<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Event\Event;
use FriendsOfBabba\Core\Controller\Api\AppController;

/**
 * Tickets Controller
 *
 * @property \App\Model\Table\TicketsTable $Tickets
 */
class TicketsController extends AppController
{
	public $paginate = [
		'page' => 1,
		'limit' => 5,
		'maxLimit' => 200
	];

	public function index()
	{
		$this->Crud->on('beforePaginate', function (Event $event) {
			$event->getSubject()->query->contain([
				'Users', 'TicketTypes'
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
				'Media'
			]);
		});
		$this->Crud->execute();
	}
}
