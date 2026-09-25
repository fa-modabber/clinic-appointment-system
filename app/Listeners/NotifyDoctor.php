<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Jobs\SendDoctorSMS;
use App\Jobs\SendNotification;
use App\Notifications\AppointmentCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyDoctor
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentCreated $event): void
    {
        $doctor = $event->appointment->doctor;

        $doctor->notify(
            new AppointmentCreatedNotification(
                $event->appointment
            )
        );
    }
}
