<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
    case NO_SHOW = 'no_show';
}
