<?php

namespace App\Console\Commands;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckRoom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:room';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check room user every day';

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
     * @return int
     */
    public function handle()
    {
        $currentDate = Carbon::today();
        $rooms = Room::whereNotNull('used_until')->get();

        if ($rooms) {
            foreach ($rooms as $key => $room) {
                if ($room->used_until < $currentDate) {
                    $room->used_by = null;
                    $room->used_until = null;
                    $room->save();
                }
            }
        }
    }
}
