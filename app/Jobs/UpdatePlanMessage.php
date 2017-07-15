<?php

namespace App\Jobs;

use App\Plan;
use App\Rsvp;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePlanMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private $payload;

    /**
     * @var \App\Plan
     */
    private $plan;

    public function __construct(array $payload, Plan $plan)
    {
        $this->payload = $payload;
        $this->plan    = $plan;
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
        $originalMessage = array_get($this->payload, 'original_message');
        $originalMessage = $this->updateAttendanceFields($originalMessage);
        $guzzle->post(array_get($this->payload, 'response_url'), [
            'json' => $originalMessage,
        ]);
    }

    private function updateAttendanceFields(array $originalMessage): array
    {
        // Get the original attachment, remove existing fields
        $attachment = array_get($originalMessage, 'attachments.0');
        array_pull($attachment, 'fields');

        // Pull the RSVP list and compile the formatted list of going and not going
        $this->plan->rsvps->groupBy('response')->map(function (\Illuminate\Support\Collection $rsvpGroup) {
            return $rsvpGroup->map(function (Rsvp $rsvp) {
                return "<@{$rsvp->user->slack_user_id}|{$rsvp->user->username}>";
            });
        })->flatMap(function (\Illuminate\Support\Collection $rsvpGroup, $response) use ($attachment) {
            return [($response ? 'Going' : 'Can\'t go') => trim($rsvpGroup->implode(', '), ',')];
        })->each(function ($rsvpList, $response) use (&$attachment) {
            $attachment['fields'][] = [
                'title' => $response,
                'value' => $rsvpList,
                'short' => true,
            ];
        });

        // Update the attachment from the original message with the fields
        array_set($originalMessage, 'attachments.0', $attachment);

        return $originalMessage;
    }
}
