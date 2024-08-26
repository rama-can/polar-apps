<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ramacan:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all optimization commands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('optimize:clear');
        $this->call('config:cache');
        $this->call('event:cache');
        $this->call('route:cache');
        $this->call('view:cache');

        $this->info('All optimization commands were executed successfully!');

        return 0;
    }
}
