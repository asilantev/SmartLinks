<?php

namespace App\Impl;

use App\Interfaces\CommandInterface;

class MacroCommand implements CommandInterface
{
    /** @var CommandInterface[] $commands */
    private $commands;

    public function __construct(CommandInterface ...$commands)
    {
        $this->commands = $commands;
    }

    public function execute(): void
    {
        foreach ($this->commands as $command) {
            $command->execute();
        }
    }
}
