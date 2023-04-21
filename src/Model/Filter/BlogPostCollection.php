<?php

namespace App\Model\Filter;

use Cake\ORM\Query;
use FriendsOfBabba\Core\Model\Filter\BaseCollection;

class BlogPostCollection extends BaseCollection
{
	public $table = "BlogPosts";

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
		$this->add('start_at', 'Search.Callback', [
			'callback' => function (Query $query, array $args) {
				$query->where([
					$this->table . '.created >=' => $args['start_at'] . ' 00:00:00'
				]);
			}
		]);
		$this->add('end_at', 'Search.Callback', [
			'callback' => function (Query $query, array $args) {
				$query->where([
					$this->table . '.created <=' => $args['end_at'] . ' 23:59:59'
				]);
			}
		]);
		$this->add('day', 'Search.Callback', [
			'callback' => function (Query $query, array $args) {
				$query->where([
					$this->table . '.created >=' => $args['day'] . ' 00:00:00',
					$this->table . '.created <=' => $args['day'] . ' 23:59:59'
				]);
			}
		]);
	}
}
