<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static TaskStatus IN_PROGRESS()
 * @method static TaskStatus DONE()
 */
class TaskStatus extends Enum
{
    private const IN_PROGRESS = 'IN_PROGRESS';
    private const DONE = 'DONE';
}
