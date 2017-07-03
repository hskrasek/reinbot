<?php

namespace App\Jobs;

use App\Plan;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Plan
     */
    private $plan;

    /**
     * @var string
     */
    private $message;

    /**
     * Create a new job instance.
     *
     * @param \App\Plan $plan
     * @param string    $message
     */
    public function __construct(Plan $plan, $message)
    {
        $this->plan    = $plan;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @param \GuzzleHttp\Client $guzzle
     *
     * @return void
     */
    public function handle(Client $guzzle)
    {
        $guzzle->post(config('slack.webhooks'), [
            'json' => [
                'text' => $this->message,
            ]
        ]);
    }

    public function failed(\Exception $exception)
    {
        \Log::error('plan.reminder.failed', [
            'exception' => get_class($exception),
            'message'   => $exception->getMessage(),
        ]);
    }
}
