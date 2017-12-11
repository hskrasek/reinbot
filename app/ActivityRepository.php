<?php namespace App;

class ActivityRepository
{
    public function getActivityForQuest(array $activity): ?Activity
    {
        return tap(
            Activity::byBungieId(array_get($activity, 'activityHash'))->first(),
            function (?Activity $manifestActivity) use ($activity): ?Activity {
                if (null === $manifestActivity) {
                    return null;
                }

                $manifestActivity->modifiers = Modifier::whereBungieIdIn(array_get(
                    $activity,
                    'modifierHashes',
                    []
                ))->get();

                return $manifestActivity;
            }
        );
    }
}
