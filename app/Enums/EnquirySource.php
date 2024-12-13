<?php

namespace App\Enums;

enum EnquirySource: string {

    case INTERNAL = "internal";
    case EXTERNAL = "external";
    case DEMOCENTER = "democenter";
    case EXPO = "expo";

    // Human-readable label
    public function label(): string {
        return match ($this) {
            self::INTERNAL => 'Internal/Inside',
            self::EXTERNAL => 'External/Outside',
            self::DEMOCENTER => 'Demo Center',
            self::EXPO => 'Expo Enquiry',
        };
    }

    // Get the array representation of the enum
    public static function toArray(): array {
        return [
            'internal' => self::INTERNAL,
            'external' => self::EXTERNAL,
            'democenter' => self::DEMOCENTER,
            'expo' => self::EXPO,
        ];
    }
}