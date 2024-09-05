<?php

namespace App\Enums;

class PaymentStatus
{

    public const PENDING = 1;
    public const PAID = 2;
    public const FAILED = 3;
    public const REFUNDED = 4;
    public const CANCELLED = 5;
    public const TRANSFER = 6;
    public const CHANAGE = 7;
    public static function getStatuses()
    {
        return [
            self::PENDING => __('statuses.pending'),
            self::PAID => __('statuses.paid'),
            self::FAILED => __('statuses.failed'),
            self::REFUNDED => __('statuses.refunded'),
            self::CANCELLED => __('statuses.cancelled'),
            self::TRANSFER => __('statuses.transfer'),
        ];
    }

    public static function getDescription($status)
    {
        $statuses = self::getStatuses();
        return $statuses[$status] ?? __('statuses.unknown');
    }

    public static function getIdByName($name)
    {
        if (empty($name)) {
            return null;
        }

        $statuses = self::getStatuses();

        if (empty($statuses)) {
            return null;
        }

        foreach ($statuses as $statusId => $statusName) {
            if (is_string($statusName) && strcasecmp($name, $statusName) === 0) {
                return $statusId;
            }
        }

        return null;
    }
}