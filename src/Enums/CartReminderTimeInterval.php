<?php

namespace App\Enums;

enum CartReminderTimeInterval: int
{
    case FIRST_ATTEMPT = 6;
    case SECOND_ATTEMPT = 12;
    case THIRD_ATTEMPT = 24;
    case DELETE = 4;
}
