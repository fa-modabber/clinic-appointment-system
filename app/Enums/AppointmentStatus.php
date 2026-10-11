<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'canceled';
    case COMPLETED = 'completed';
    case NO_SHOW = 'no_show';

    public static function doctorPatientStatuses(): array
{
    return [
        self::PENDING->value,
        self::CONFIRMED->value,
        self::COMPLETED->value,
        self::NO_SHOW->value,
    ];
}
}
