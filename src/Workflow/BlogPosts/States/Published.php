<?php

namespace App\Workflow\BlogPosts\States;

use FriendsOfBabba\Core\Workflow\State;
use FriendsOfBabba\Core\Workflow\WorkflowTrait;
use FriendsOfBabba\Core\Model\Entity\Role;

class Published extends State
{
	use WorkflowTrait;

	const CODE = "published";

	function __construct()
	{
		parent::__construct(self::CODE, __d('workflow', 'Published'));

		$this
			->withLabel(__d("workflow", "Published"))
			->withDescription(__d("workflow", "The blog post has been published and is visible to all users. Unpublish it if you don't want to show it anymore."))
			->setIsInitial(false)
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
