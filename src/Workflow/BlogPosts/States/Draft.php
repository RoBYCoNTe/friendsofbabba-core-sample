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

		$defaultPerms = [
			'admin' => ['read' => true, 'edit' => true],
			'user' => ['read' => true, 'edit' => true],
		];
		$this
			->withLabel(__d("workflow", "Draft"))
			->withDescription(__d("workflow", "No one can see this blog post. Publish it to make it visible to all users."))
			->setIsInitial(true)
			->setPermissions([
				"admin" => ['create' => true, 'read' => true, 'edit' => true, 'move' => true],
				"user" => ['create' => true, 'read' => true, 'edit' => true, 'move' => true],
			])
			->setFieldsPermissions([
				"author_id" => $defaultPerms,
				"slug" => $defaultPerms,
				"title" => $defaultPerms,
				"content" => $defaultPerms,
				"blog_categories" => $defaultPerms,
				"blog_post_comments" => $defaultPerms,
				"media" => $defaultPerms,
				"thumbnail" => $defaultPerms,
				"users" => $defaultPerms,
				"published" => $defaultPerms,
			]);
	}
}
