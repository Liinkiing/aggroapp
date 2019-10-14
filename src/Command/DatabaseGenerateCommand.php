<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatabaseGenerateCommand extends Command
{
    protected static $defaultName = 'app:database:generate';

    /**
     * @var SymfonyStyle
     */
    private $io;

    protected function configure(): void
    {
        $this
            ->setDescription('Regenerate database from start')
        ;
    }

    protected function getCommands(): array
    {
        return [
            'doctrine:database:drop' => ['--force' => true, '--no-interaction' => true],
            'doctrine:database:create' => ['--no-interaction' => true],
            'doctrine:migrations:migrate' => ['--no-interaction' => true],
            'hautelook:fixtures:load' => ['--no-interaction' => true]
        ];
    }

    protected function launchCommand(string $commandName, array $arguments, OutputInterface $output): int
    {
        if ($this->io) {
            $this->io->newLine();
            $this->io->section("Running $commandName");
        }
        $command = $this->getApplication()->find($commandName);

        $input = new ArrayInput($arguments);
        $input->setInteractive(false);

        return $command->run($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        foreach ($this->getCommands() as $commandName => $arguments) {
            $this->launchCommand($commandName, $arguments, $output);
        }

        $this->io->success('Successfully reinitialized database!');
    }
}
