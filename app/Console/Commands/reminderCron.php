<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:cron';

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
        \Log::info('Reminder running');

        /*
           Write your database logic we bellow:
           Item::create(['name'=>'hello new']);
        */

        return \App::call('App\Http\Controllers\ReminderController@checkDeadline');
    }
}
