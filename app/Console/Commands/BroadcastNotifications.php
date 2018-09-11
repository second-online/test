<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\BroadcastStarting;
use App\Broadcast;

class BroadcastNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $broadcasts = Broadcast::all();

        foreach ($broadcasts as $broadcast) {
            broadcast(new BroadcastStarting($broadcast->toArray()));
            break;
        }
    }
}
