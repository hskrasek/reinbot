<?php namespace App\Commands\Bot\Handlers;

use App\Plan;
use App\Rsvp;
use Illuminate\Support\Collection;
use Mpociot\BotMan\BotMan;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentField;

class Debug
{
    public function handle(BotMan $bot)
    {
        $plans = Plan::orderByDesc('scheduled_at')->with(['user', 'rsvps'])->withCount(['rsvps'])->limit(5)->get();
        $bot->sendRequest('chat.postMessage', [
            'channel'     => $bot->getMessage()->getChannel(),
            'text'        => "Here is your debug information",
            'attachments' => $plans->map(function (Plan $plan) {
                return Attachment::create()
                                 ->setColor('#5d5956')
                                 ->setTitle("Plan $plan->id")
                                 ->setText(
                                     "Scheduled for <!date^{$plan->scheduled_at->timestamp}^{date_pretty} at {time}|{$plan->scheduled_at->toCookieString()}>"
                                 )
                                 ->setAuthorName($plan->user->username)
                                 ->setTimestamp($plan->created_at)
                                 ->addField(AttachmentField::create('Total RSVPs', $plan->rsvps_count)
                                                           ->doNotDisplaySideBySide())
                                 ->addFields($this->getAttendanceFields($plan))
                                 ->setFooter('Created via ' . $plan->message_ts ? 'bot user.' : 'slash command.')
                                 ->toArray();
            })->toJson(),
        ]);
    }

    private function getAttendanceFields($plan): array
    {
        // Pull the RSVP list and compile the formatted list of going and not going
        return $plan->rsvps->groupBy('response')->map(function (Collection $rsvpGroup) {
            return $rsvpGroup->map(function (Rsvp $rsvp) {
                return "<@{$rsvp->user->slack_user_id}|{$rsvp->user->username}>";
            });
        })->reverse()->flatMap(function (Collection $rsvpGroup, $response) {
            return [($response ? 'Going' : 'Can\'t go') => trim($rsvpGroup->implode(', '), ',')];
        })->map(function ($rsvpList, $response) {
            return AttachmentField::create($response, $rsvpList)->displaySideBySide();
        });
    }
}
