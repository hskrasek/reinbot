<?php namespace App;

class RsvpRepository
{
    public function getRsvpForUser(User $user, Plan $plan): Rsvp
    {
        /** @var \App\Rsvp $rsvp */
        if (!$rsvp = $plan->rsvps()->where('user_id', $user->id)->first()) {
            $rsvp = $plan->rsvps()->make(['user_id' => $user->id]);
        }

        return $rsvp;
    }

    /**
     * RSVPs the user to the plan
     *
     * @param \App\Rsvp $rsvp
     * @param bool      $response
     *
     * @return \App\Rsvp
     */
    public function rsvpUserToPlan(Rsvp $rsvp, bool $response): Rsvp
    {
        $rsvp->response = $response;
        $rsvp->save();

        return $rsvp;
    }
}
