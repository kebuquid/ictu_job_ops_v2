<?php

namespace App\Enums;

enum SectionEnums: int
{
    case MIS = 1;
    case NICM = 2;
    case ICTRAM = 3;

    public function label(): string
    {
        return match($this) {
            self::MIS => 'MIS',
            self::NICM => 'NICM',
            self::ICTRAM => 'ICTRAM'
        };
    }

    public function color(): string
    {
        return match($this) {
            self::MIS => 'blue',
            self::NICM => 'green',
            self::ICTRAM => 'yellow',
        };
    }
}