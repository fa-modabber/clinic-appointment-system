<?php

namespace App\Enums;

use Carbon\Carbon;

enum Week: int
{
    case SUNDAY = "sunday";
    case MONDAY = "monday";
    case TUESDAY = "tuesday";
    case WEDNESDAY = "wednesday";
    case THURSDAY = "thursday";
    case FRIDAY = "friday";
    case SATURDAY = "saturday";

    public static function fromCarbon(Carbon $date): self
    {
        return match ($date->dayOfWeek) {
            Carbon::SUNDAY => self::SUNDAY,
            Carbon::MONDAY => self::MONDAY,
            Carbon::TUESDAY => self::TUESDAY,
            Carbon::WEDNESDAY => self::WEDNESDAY,
            Carbon::THURSDAY => self::THURSDAY,
            Carbon::FRIDAY => self::FRIDAY,
            Carbon::SATURDAY => self::SATURDAY,
        };
    }

    public function mysqlValue(): int
    {
        return match ($this) {
            self::SUNDAY => 1,
            self::MONDAY => 2,
            self::TUESDAY => 3,
            self::WEDNESDAY => 4,
            self::THURSDAY => 5,
            self::FRIDAY => 6,
            self::SATURDAY => 7,
        };
    }
}
