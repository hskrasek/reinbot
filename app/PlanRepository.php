<?php namespace App;

use Carbon\Carbon;

class PlanRepository
{
    const DEFAULT_TIME = '9PM';

    const DEFAULT_TIMEZONE = 'America/Chicago';

    /**
     * Creates a plan belonging to a user, based on command data.
     *
     * @param \App\User $user
     * @param array     $data
     *
     * @return \App\Plan
     */
    public function createPlan(User $user, $data)
    {
        return $user->plans()->create([
            'scheduled_at' => $this->getPlansScheduledTime($user, $data['text']),
        ]);
    }

    /**
     * Parses the plans scheduled time, using the default otherwise
     *
     * @param \App\User $user
     * @param string    $text
     *
     * @return \Carbon\Carbon
     */
    protected function getPlansScheduledTime(User $user, $text)
    {
        preg_match('/at (\d(am|pm|AM|PM)?)/', $text, $matches);

        // Handle empty text here, which also covers incorrect input data
        if (empty($matches)) {
            return Carbon::parse(self::DEFAULT_TIME, self::DEFAULT_TIMEZONE);
        }

        if (!ends_with($matches[1], ['am', 'pm', 'AM', 'PM'])) {
            $matches[1] = $matches[1] . 'pm';
        }

        return Carbon::parse($matches[1], $user->timezone);
    }
}
