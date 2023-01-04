<?php

namespace App\Test\TestCase\Model;

use Cake\TestSuite\TestCase;

class LocalRecordsTest extends TestCase
{
	protected $fixtures = [
		'app.LocalRecords'
	];

	public function testCount()
	{
		$records = $this->getTableLocator()->get('LocalRecords');
		$this->assertEquals(1, $records
			->find()
			->count());
	}
}
