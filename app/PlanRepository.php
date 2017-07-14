<?php namespace App;

use App\Jobs\SendReminderMessage;
use Carbon\Carbon;

class PlanRepository
{
    const DEFAULT_TIME = '9PM';

    const DEFAULT_TIMEZONE = 'America/Chicago';

    /**
     * Get a Plan by its ID
     *
     * @param int $id
     *
     * @return \App\Plan
     */
    public function getById(int $id): Plan
    {
        return Plan::with('rsvps')->find($id);
    }

    /**
     * Creates a plan belonging to a user, based on command data.
     *
     * @param \App\User $user
     * @param string    $time
     * @param string    $responseUrl
     *
     * @return \App\Plan
     */
    public function createPlan(User $user, string $time, string $responseUrl): Plan
    {
        return tap($user->plans()->create([
            'scheduled_at' => $this->getPlansScheduledTime($user, $time),
            'response_url' => $responseUrl,
        ]), function (Plan $plan) {
            $scheduledAt = clone $plan->scheduled_at;
            dispatch(
                (new SendReminderMessage($plan, __('messages.plans.reminder')))
                    ->delay($scheduledAt)
            );
            dispatch(
                (new SendReminderMessage($plan, __('messages.plans.hour_reminder')))
                    ->delay($scheduledAt->subHour())
            );
        });
    }

    /**
     * Get the next upcoming plan of the day, if there is one.
     *
     * @return \App\Plan
     */
    public function getNextPlan(): ?Plan
    {
        return Plan::whereBetween('scheduled_at', [Carbon::now(), Carbon::now()->endOfDay()])->first();
    }

    /**
     * Get a plan at the scheduled time.
     *
     * @param \App\User $user
     * @param string    $time
     *
     * @return \App\Plan
     */
    public function findPlanAtRequestedTime(User $user, string $time): ?Plan
    {
        return Plan::where('scheduled_at', $this->getPlansScheduledTime($user, $time))->first();
    }

    /**
     * Parses the plans scheduled time, using the default otherwise
     *
     * @param \App\User $user
     * @param string    $text
     *
     * @return \Carbon\Carbon
     */
    protected function getPlansScheduledTime(User $user, string $text): Carbon
    {
        preg_match('/(\d+(?>:\d+)?(?>am|pm|AM|PM)?)/i', $text, $matches);

        // Handle empty text here, which also covers incorrect input data
        if (empty($matches)) {
            $time = Carbon::parse(self::DEFAULT_TIME, self::DEFAULT_TIMEZONE);

            $time->timezone = 'UTC';

            return $time;
        }

        if (!ends_with($matches[1], ['am', 'pm', 'AM', 'PM'])) {
            $matches[1] = $matches[1] . 'pm';
        }

        $time = Carbon::parse($matches[1], $user->timezone);

        $time->timezone = 'UTC';

        return $time;
    }
}
