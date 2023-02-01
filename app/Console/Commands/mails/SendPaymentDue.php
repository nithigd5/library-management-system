<?php

namespace App\Console\Commands\mails;

use App\Mail\PaymentDue;
use App\Models\Purchase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:paymentdue';

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
        $paymentDue = Purchase::with('user' , 'book')->paymentDueBetween()->get();

        $this->info('Total users: ' . $paymentDue->count());

        foreach ($paymentDue as $purchase) {
            Mail::to($purchase->user->email)->send((new PaymentDue($purchase))->onQueue('email'));
        }

        $this->info('Queued to users');

        return Command::SUCCESS;
    }
}
