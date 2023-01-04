<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class LocalRecordsFixture extends TestFixture
{
	public function init(): void
	{
		$this->records = [
			[
				'field_1' => 'TestDynamic',
				'field_2' => 0,
				'field_3' => 10.2,
				'created' => '2019-01-01 00:00:00',
				'modified' => '2019-01-01 00:00:00',
			]
		];
		parent::init();
	}
}
