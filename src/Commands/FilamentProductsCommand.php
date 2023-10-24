<?php

namespace Apsonex\FilamentProducts\Commands;

use Illuminate\Console\Command;

class FilamentProductsCommand extends Command
{
    public $signature = 'filament-products';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
