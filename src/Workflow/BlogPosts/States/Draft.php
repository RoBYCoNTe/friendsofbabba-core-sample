<?php

namespace App\Workflow\BlogPosts\States;

use FriendsOfBabba\Core\Workflow\State;
use FriendsOfBabba\Core\Workflow\WorkflowTrait;
use FriendsOfBabba\Core\Model\Entity\Role;

class Draft extends State
{
	use WorkflowTrait;

	const CODE = "draft";

	function __construct()
	{
		parent::__construct(self::CODE, __d('workflow', 'Draft'));

		$this
			->withLabel(__d("workflow", "Draft"))
			->withDescription(__d("workflow", " "))
			->setIsInitial(true)
			->setPermissions([
				"admin" => ['create' => true, 'read' => true, 'edit' => true, 'move' => true],
				"user" => ['create' => true, 'read' => true, 'edit' => true, 'move' => true],
			])
			->setFieldsPermissions([
				"author_id" => [
					"admin" => ['read' => true, 'edit' => true],
					"user" => ['read' => true, 'edit' => true],
				],
				"title" => [
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
