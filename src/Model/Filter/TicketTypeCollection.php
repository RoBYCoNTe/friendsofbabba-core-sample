<?php

namespace App\Model\Filter;

use FriendsOfBabba\Core\Model\Filter\BaseCollection;

class TicketTypeCollection extends BaseCollection
{
	public $table = "TicketTypes";

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
			"fields" => ['*']
		]);
	}
}
