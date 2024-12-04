<?php

namespace Windsor\Phetl\Commands;

use Illuminate\Console\Command;

class PhetlCommand extends Command
{
    public $signature = 'phetl';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
