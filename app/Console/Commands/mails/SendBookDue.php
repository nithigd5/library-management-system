<?php

namespace App\Console\Commands\mails;

use App\Mail\BookDue;
use App\Models\Purchase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBookDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:bookdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds all the users who has book due by tomorrow.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $overDueBooks = Purchase::with('user' , 'book')->bookDueBetween()->get();

        $this->info('Total users: ' . $overDueBooks->count());

        foreach ($overDueBooks as $purchase) {
            Mail::to($purchase->user->email)->send((new BookDue($purchase))->onQueue('email'));
        }

        $this->info('Queued to users');

        return Command::SUCCESS;
    }
}
