<?php

namespace App\Workflow\Tickets\States;

use FriendsOfBabba\Core\Workflow\State;
use FriendsOfBabba\Core\Workflow\WorkflowTrait;

class Closed extends State
{
	use WorkflowTrait;

	const CODE = "closed";

	function __construct()
	{
		parent::__construct(self::CODE, __d('workflow', 'Closed'));

		$this
			->withLabel(__d("workflow", "Closed"))
			->withDescription(__d("workflow", " "))
			->setIsInitial(false)
			->setPermissions([
				"admin" => ['create' => true, 'read' => true, 'edit' => true, 'move' => true],
				"user" => ['create' => true, 'read' => true, 'edit' => true, 'move' => true],
			])
			->setFieldsPermissions([
				"user_id" => [
					"admin" => ['read' => true, 'edit' => false],
					"user" => ['read' => true, 'edit' => false],
				],
				"ticket_type_id" => [
					"admin" => ['read' => true, 'edit' => false],
					"user" => ['read' => true, 'edit' => false],
				],
				"subject" => [
					"admin" => ['read' => true, 'edit' => false],
					"user" => ['read' => true, 'edit' => false],
				],
				"content" => [
					"admin" => ['read' => true, 'edit' => false],
					"user" => ['read' => true, 'edit' => false],
				],
				"media" => [
					"admin" => ["read" => true, "edit" => false],
					"user" => ["read" => true, "edit" => false],
				]
			]);
	}
}
