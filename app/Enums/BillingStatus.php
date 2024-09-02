<?php

namespace App\Enums;

class BillingStatus
{



    public const UNBILLED = 0;
    public const UNPAID = 1;
    public const PAID = 2;
    public const PARTIALLY_PAID = 3;
    public const REFUNDED = 4;
    public const CANCELLED = 5;
    public const OVERDUE = 6;
    public const PENDING = 7;
    public const PROCESSING = 8;
    public const ON_HOLD = 9;
    public const FAILED = 10;
    public const DRAFT = 11;

    public const CLOSED = 12;


    public static function getStatuses()
    {
        return [
            self::UNBILLED => __('statuses.unbilled'),
            self::DRAFT => __('statuses.draft'),
            self::PENDING => __('statuses.pending'),
            self::PAID => __('statuses.paid'),
            self::OVERDUE => __('statuses.overdue'),
            self::REFUNDED => __('statuses.refunded'),
            self::PARTIALLY_PAID => __('statuses.partially_paid'),
            self::CANCELLED => __('statuses.cancelled'),
            self::FAILED => __('statuses.failed'),
            self::PROCESSING => __('statuses.processing'),
            self::ON_HOLD => __('statuses.on_hold'),
            self::UNPAID => __('statuses.unpaid'),
        ];
    }

    public static function getDescription($status)
    {
        $statuses = self::getStatuses();
        return $statuses[$status] ?? __('statuses.unknown');
    }

    public static function getStatus($status, $statuses)
    {
        $status = strtolower($status); // convert status to lowercase

        if (array_key_exists($status, $statuses)) {
            return $statuses[$status]; // return the value corresponding to the status key
        } else {
            return null; // return null if the status key doesn't exist in the array
        }
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
