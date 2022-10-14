<?php

namespace App\Workflow\Tickets\States;

use FriendsOfBabba\Core\Workflow\State;
use FriendsOfBabba\Core\Workflow\WorkflowTrait;
use FriendsOfBabba\Core\Model\Entity\Role;

class Working extends State
{
	use WorkflowTrait;

	const CODE = "working";

	function __construct()
	{
		parent::__construct(self::CODE, __d('workflow', 'Working'));

		$this
			->withLabel(__d("workflow", "Working"))
			->withDescription(__d("workflow", " "))
			->setIsInitial(false)
			->setPermissions([
				"admin" => ['create' => true, 'read' => true, 'edit' => true, 'move' => true],
				"user" => ['create' => true, 'read' => true, 'edit' => true, 'move' => true],
			])
			->setFieldsPermissions([
				"user_id" => [
					"admin" => ['read' => true, 'edit' => true],
					"user" => ['read' => true, 'edit' => true],
				],
				"ticket_type_id" => [
					"admin" => ['read' => true, 'edit' => true],
					"user" => ['read' => true, 'edit' => true],
				],
				"subject" => [
					"admin" => ['read' => true, 'edit' => true],
					"user" => ['read' => true, 'edit' => true],
				],
				"content" => [
					"admin" => ['read' => true, 'edit' => true],
					"user" => ['read' => true, 'edit' => true],
				],
			]);
	}
}
