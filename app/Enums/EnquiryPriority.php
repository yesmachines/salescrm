<?php

namespace App\Enums;

enum EnquiryPriority: string {

    case LOW = "low";
    case MEDIUM = "medium";
    case HIGH = "high";
    case SOS = "sos";

    // Human-readable label
    public function label(): string {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::SOS => 'SOS',
        };
    }

    // Get the array representation of the enum
    public static function toArray(): array {
        return [
            'low' => self::LOW,
            'medium' => self::MEDIUM,
            'high' => self::HIGH,
            'sos' => self::SOS,
        ];
    }

    // Static method to return the list of key-label pairs
    public static function toKeyLabelArray(): array {
        return array_map(fn($case) => [
            'key' => $case->value,
            'label' => $case->label(),
                ], self::cases());
    }
}
