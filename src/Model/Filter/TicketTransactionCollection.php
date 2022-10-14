<?php

namespace App\Model\Filter;

use FriendsOfBabba\Core\Model\Filter\BaseCollection;

class TicketTransactionCollection extends BaseCollection
{
	public $table = "TicketTransactions";

	public function initialize(): void
	{
		parent::initialize();
	}
}
