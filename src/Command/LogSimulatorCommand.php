<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Utility\Text;
use FriendsOfBabba\Core\Command\CommandLog;

/**
 * LogSimulator command.
 */
class LogSimulatorCommand extends Command
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
        $parser->addOption('command', [
            'help' => 'The command to simulate',
            'short' => 'c',
            'default' => 'log',
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
        $command = $args->getOption('command', 'log_simulator');
        $logger = new CommandLog(['name' => 'bin/cake ' . $command]);
        for ($i = 0; $i < 100; $i++) {
            $randomText = Text::uuid();
            $type = rand(0, 1) ? 'info' : 'error';
            $message = ('This is a log message with a random text: ' . $randomText);
            $logger->{$type}($message);
        }
    }
}
