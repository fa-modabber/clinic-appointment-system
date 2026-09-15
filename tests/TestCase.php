<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    //
}


// Patient can book appointment
// Doctor cannot have overlapping appointments
// Inactive schedule doesn't generate slots
// Unavailable exception removes slots
// Custom-hours exception creates slots
// Patient cannot book unavailable slot
// Doctor cannot modify schedule with conflicting appointment
