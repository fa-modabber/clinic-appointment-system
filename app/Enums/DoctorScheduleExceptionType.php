<?php

namespace App\Enums;

enum DoctorScheduleExceptionType: string
{
    case UNAVAILABLE = 'unavailable';
    case CUSTOM_HOURS = 'custom_hours';
}
