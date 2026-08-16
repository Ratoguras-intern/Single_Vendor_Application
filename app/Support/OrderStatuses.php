<?php

namespace App\Support;

final class OrderStatuses
{
    public const PENDING = 'pending';
    public const PACKED = 'packed';
    public const SHIPPED = 'shipped';
    public const DELIVERED = 'delivered';
    public const CANCELLED = 'cancelled';

    /**
     * Order of the fulfilment flow (excludes cancelled).
     */
    public const FLOW = [
        self::PENDING,
        self::PACKED,
        self::SHIPPED,
        self::DELIVERED,
    ];

    public static function all(): array
    {
        return [self::PENDING, self::PACKED, self::SHIPPED, self::DELIVERED, self::CANCELLED];
    }

    public static function labels(): array
    {
        return [
            self::PENDING => 'Pending',
            self::PACKED => 'Packed',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
        ];
    }

    public static function label(string $status): string
    {
        return self::labels()[$status] ?? ucfirst($status);
    }

    /**
     * Step index in the fulfilment flow. Returns null for cancelled.
     */
    public static function step(string $status): ?int
    {
        $index = array_search($status, self::FLOW, true);

        return $index === false ? null : $index;
    }

    /**
     * Tailwind badge classes for admin panel.
     */
    public static function badgeClasses(string $status): string
    {
        return match ($status) {
            self::PENDING => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
            self::PACKED => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
            self::SHIPPED => 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
            self::DELIVERED => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
            self::CANCELLED => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
            default => 'bg-gray-50 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400',
        };
    }

    /**
     * Tailwind badge classes for the frontend.
     */
    public static function frontendBadgeClasses(string $status): string
    {
        return match ($status) {
            self::DELIVERED => 'badge-success',
            self::CANCELLED => 'badge-danger',
            self::SHIPPED => 'bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300',
            self::PACKED => 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300',
            default => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-300',
        };
    }

    public static function dotClasses(string $status): string
    {
        return match ($status) {
            self::PENDING => 'bg-amber-500',
            self::PACKED => 'bg-blue-500',
            self::SHIPPED => 'bg-purple-500',
            self::DELIVERED => 'bg-emerald-500',
            self::CANCELLED => 'bg-red-500',
            default => 'bg-gray-400',
        };
    }

    public static function isCancelled(string $status): bool
    {
        return $status === self::CANCELLED;
    }

    public static function isTerminal(string $status): bool
    {
        return in_array($status, [self::DELIVERED, self::CANCELLED], true);
    }

    public static function next(string $status): ?string
    {
        $step = self::step($status);

        if ($step === null) {
            return null;
        }

        return self::FLOW[$step + 1] ?? null;
    }
}
