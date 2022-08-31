<?php

namespace App\Model\Table\Extender;

use FriendsOfBabba\Core\Model\Table\BaseTable;
use FriendsOfBabba\Core\Model\Entity\User;
use FriendsOfBabba\Core\Model\Crud\Badge;
use FriendsOfBabba\Core\Model\Table\BaseTableExtender;

class UsersTableExtender extends BaseTableExtender
{
	public function getBadge(BaseTable $baseTable, User $user): ?Badge
	{
		// Example usage of extender for core library.
		// Returns a badge containing only active users.
		$count = $baseTable->find()->where(['Users.status' => 'active'])->count();

		return Badge::primary($count);
	}
}
