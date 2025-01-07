<?php

namespace App\Enums;

enum EnquiryMode: string {

    case EMAIL = "email";
    case WHATSAPP = "whatsapp";
    case PHONE = "phone";

    // Human-readable label
    public function label(): string {
        return match ($this) {
            self::EMAIL => 'Email',
            self::WHATSAPP => 'WhatsApp',
            self::PHONE => 'Phone Call',
        };
    }

    // Get the array representation of the enum
    public static function toArray(): array {
        return [
            'email' => self::EMAIL,
            'whatsapp' => self::WHATSAPP,
            'phone' => self::PHONE,
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
