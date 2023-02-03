<?php

namespace App\Model\Filter;

use FriendsOfBabba\Core\Model\Filter\BaseCollection;

class BlogPostTaxonomyCollection extends BaseCollection
{
	public $table = "BlogPostTaxonomies";

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
			"fields" => ['name']
		]);
		$this->value("parent_id");
		$this->add('only_roots', 'Search.Callback', [
			'callback' => function ($query, $args, $filter) {
				$onlyRoots = filter_var($args['only_roots'], FILTER_VALIDATE_BOOLEAN);
				if ($onlyRoots) {
					$query->where(['parent_id IS' => NULL]);
				}
			}
		]);
	}
}
