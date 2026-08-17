<?php

namespace App\Support;

final class ReturnStatuses
{
    public const REQUESTED = 'requested';
    public const PENDING_REVIEW = 'pending_review';
    public const MORE_INFO_REQUIRED = 'more_information_required';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const RETURN_SHIPPED = 'return_shipped';
    public const RECEIVED = 'received';
    public const REFUNDED = 'refunded';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    public const ALL = [
        self::REQUESTED,
        self::PENDING_REVIEW,
        self::MORE_INFO_REQUIRED,
        self::APPROVED,
        self::REJECTED,
        self::RETURN_SHIPPED,
        self::RECEIVED,
        self::REFUNDED,
        self::COMPLETED,
        self::CANCELLED,
    ];

    public const CUSTOMER_VISIBLE = [
        self::REQUESTED,
        self::PENDING_REVIEW,
        self::MORE_INFO_REQUIRED,
        self::APPROVED,
        self::REJECTED,
        self::RETURN_SHIPPED,
        self::RECEIVED,
        self::REFUNDED,
        self::COMPLETED,
        self::CANCELLED,
    ];

    public static function labels(): array
    {
        return [
            self::REQUESTED => 'Requested',
            self::PENDING_REVIEW => 'Pending Review',
            self::MORE_INFO_REQUIRED => 'More Info Required',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::RETURN_SHIPPED => 'Return Shipped',
            self::RECEIVED => 'Received',
            self::REFUNDED => 'Refunded',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        ];
    }

    public static function label(string $status): string
    {
        return self::labels()[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    public static function badgeClasses(string $status): string
    {
        return match ($status) {
            self::REQUESTED => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
            self::PENDING_REVIEW => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
            self::MORE_INFO_REQUIRED => 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
            self::APPROVED => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
            self::REJECTED => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
            self::RETURN_SHIPPED => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400',
            self::RECEIVED => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-400',
            self::REFUNDED => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
            self::COMPLETED => 'bg-gray-50 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400',
            self::CANCELLED => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
            default => 'bg-gray-50 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400',
        };
    }

    public static function frontendBadgeClasses(string $status): string
    {
        return match ($status) {
            self::REQUESTED => 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300',
            self::PENDING_REVIEW => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-300',
            self::MORE_INFO_REQUIRED => 'bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300',
            self::APPROVED => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
            self::REJECTED => 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-300',
            self::RETURN_SHIPPED => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300',
            self::RECEIVED => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-950/40 dark:text-cyan-300',
            self::REFUNDED => 'bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-300',
            self::COMPLETED => 'badge-success',
            self::CANCELLED => 'badge-danger',
            default => 'bg-gray-100 text-gray-700 dark:bg-gray-950/40 dark:text-gray-300',
        };
    }

    public static function isTerminal(string $status): bool
    {
        return in_array($status, [self::COMPLETED, self::REJECTED, self::CANCELLED], true);
    }

    public static function canCancel(string $status): bool
    {
        return in_array($status, [self::REQUESTED, self::PENDING_REVIEW, self::MORE_INFO_REQUIRED], true);
    }

    public static function canApprove(string $status): bool
    {
        return in_array($status, [self::PENDING_REVIEW], true);
    }

    public static function canReject(string $status): bool
    {
        return in_array($status, [self::PENDING_REVIEW], true);
    }

    public static function canRequestMoreInfo(string $status): bool
    {
        return in_array($status, [self::PENDING_REVIEW], true);
    }

    public static function canMarkShipped(string $status): bool
    {
        return $status === self::APPROVED;
    }

    public static function canMarkReceived(string $status): bool
    {
        return $status === self::RETURN_SHIPPED;
    }

    public static function canRefund(string $status): bool
    {
        return $status === self::RECEIVED;
    }

    public static function dotClasses(string $status): string
    {
        return match ($status) {
            self::REQUESTED => 'bg-blue-500',
            self::PENDING_REVIEW => 'bg-amber-500',
            self::MORE_INFO_REQUIRED => 'bg-purple-500',
            self::APPROVED => 'bg-emerald-500',
            self::REJECTED => 'bg-red-500',
            self::RETURN_SHIPPED => 'bg-indigo-500',
            self::RECEIVED => 'bg-cyan-500',
            self::REFUNDED => 'bg-green-500',
            self::COMPLETED => 'bg-gray-500',
            self::CANCELLED => 'bg-red-500',
            default => 'bg-gray-400',
        };
    }

    public static function returnReasons(): array
    {
        return [
            'damaged' => 'Damaged or defective',
            'wrong_item' => 'Wrong item received',
            'not_as_described' => 'Item not as described',
            'missing_parts' => 'Missing parts or accessories',
            'changed_mind' => 'Changed my mind',
            'other' => 'Other',
        ];
    }
}
