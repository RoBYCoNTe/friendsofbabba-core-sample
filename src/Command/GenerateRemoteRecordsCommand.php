<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Utility\Text;

/**
 * GenerateRemoteRecords command.
 */
class GenerateRemoteRecordsCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->addOption('records', [
            'help' => 'The number of records to generate',
            'short' => 'r',
            'default' => 10,
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $table = $this->fetchTable('RemoteRecords');
        $recordsToGenerate = $args->getOption('records');
        for ($i = 0; $i < $recordsToGenerate; $i++) {
            $setNull = rand(0, 1);
            $remoteRecord = $table->newEntity([
                'name' => Text::uuid(),
                'field_1' => Text::uuid(),
                'field_2' => $setNull == 1 ? NULL : mt_rand(1, 100),
                'field_3' => mt_rand() / mt_getrandmax(),
            ]);
            $table->save($remoteRecord);
        }
        $io->success('Generated ' . $recordsToGenerate . ' records.');
    }
}
