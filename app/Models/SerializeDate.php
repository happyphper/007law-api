<?php

namespace App\Models;

use DateTimeInterface;

trait SerializeDate
{
    public function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
