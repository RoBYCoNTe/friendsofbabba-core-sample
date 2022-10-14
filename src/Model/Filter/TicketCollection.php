<?php

namespace App\Model\Filter;

use FriendsOfBabba\Core\Model\Filter\BaseCollection;

class TicketCollection extends BaseCollection
{
	public $table = "Tickets";

	public function initialize(): void
	{
		parent::initialize();
		$this->add("q", "Search.Like", [
			"before" => true,
			"after" => true,
			"fieldMode" => "OR",
			"comparison" => "LIKE",
			"wildcardAny" => "*",
			"wildcardOne" => "?",
			"fields" => ['subject', 'content']
		]);
	}
}
