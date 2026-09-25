<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Jobs\SendNotification;
use App\Jobs\SendPatientSMS;
use App\Notifications\AppointmentCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyPatient
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
        $patient = $event->appointment->patient;

        $patient->notify(
            new AppointmentCreatedNotification(
                $event->appointment
            )
        );
    }
}
