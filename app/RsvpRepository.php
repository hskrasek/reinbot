<?php namespace App;

class RsvpRepository
{
    /**
     * RSVPs the user to the plan
     *
     * @param \App\User $user
     * @param \App\Plan $plan
     * @param array     $payload
     *
     * @return \App\Rsvp
     */
    public function rsvpUserToPlan(User $user, Plan $plan, array $payload)
    {
        $response = (bool) array_get($payload, 'actions.0.value');

        /** @var \App\Rsvp $rsvp */
        if (!$rsvp = $plan->rsvps()->where('user_id', $user->id)->first()) {
            $rsvp = $plan->rsvps()->create(['user_id' => $user->id]);
        }

        if ($rsvp->response == $response) {
            return false;
        }

        $rsvp->where(['user_id' => $user->id, 'plan_id' => $plan->id])->update(['response' => $response]);

        return Rsvp::where(['user_id' => $user->id, 'plan_id' => $plan->id])->first();
    }
}
