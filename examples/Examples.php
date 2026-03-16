<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace examples;

use examples\Usage\Depending;
use examples\Usage\Ordered;
use examples\Usage\Simple;
use League\CLImate\CLImate;

require __DIR__.'/../vendor/autoload.php';

new Examples()->run();
class Examples
{
    /**
     * @var array<string, string>
     */
    private const array EXAMPLES = [
        'examples/SimpleUsage.php' => 'Simple Fixture',
        'examples/DependingUsage.php' => 'Depending Fixture',
        'examples/OrderedUsage.php' => 'Ordered Fixture',
    ];
    private CLImate $climate;
    private ?string $command;

    public function __construct()
    {
        $this->climate = new CLImate();
        $this->climate->backgroundLightGray()->bold()->underline()->out(' Data fixtures examples ');
        $this->command = $argv[1] ?? null;
    }

    public function run(): void
    {
        if (null === $this->command) {
            $examples = array_values(self::EXAMPLES);
            $examples[] = 'Help';
            $input = $this->climate->radio('What examples do you want to see?', $examples);
            $answer = $input->prompt();
            match ($answer) {
                self::EXAMPLES['examples/SimpleUsage.php'] => Simple::run($this->climate),
                self::EXAMPLES['examples/DependingUsage.php'] => Depending::run($this->climate),
                self::EXAMPLES['examples/OrderedUsage.php'] => Ordered::run($this->climate),
                default => $this->help(),
            };
        }
    }

    private function help(): void
    {
        $this->climate->whisper('Usage: php examples/Examples.php <example-name>');
        $this->climate->lightGray()->underline('Available examples:');
        $this->climate->flank('Available examples:');
        $padding = $this->climate->padding(10);

        foreach (self::EXAMPLES as $example => $name) {
            $padding->label($name)->result($example);
        }
    }
}
