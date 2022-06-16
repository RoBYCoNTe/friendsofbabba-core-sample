<?php

namespace App\Command\DataMigration;

use Cake\Console\ConsoleIo;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use FriendsOfBabba\Core\Command\DataMigration\AbstractDataMigration;

class RemoteToLocalDataMigration extends AbstractDataMigration
{
	use LocatorAwareTrait;

	public function getMappers(): array
	{
		return [
			'id' => 'id',
			'name' => 'name'
		];
	}

	public function sync(ConsoleIo $io, ?int $limit = NULL, ?int $offset = NULL): void
	{
		$results = ConnectionManager::get("default")
			->execute("SELECT * FROM remote_records")
			->fetchAll('assoc');
		$table = $this->fetchTable("LocalRecords");

		$io->info(count($results) . " remote_records found");
		$io->hr();

		$this->getEntities($table, $results, [], function ($entity) use ($io, $table) {
			$io->out("|__ Processing {$entity->id}");
			$result = $table->findOrCreate(['id' => $entity->id], function ($newEntity) use ($entity) {
				$newEntity->id = $entity->id;
			});
			if ($result) {
				$io->success("|__ {$entity->id} saved :-D");
			} else {
				$io->warning("|__ {$entity->id}, not saved: " . json_encode($entity->getErrors()));
			}
		});
	}
}
