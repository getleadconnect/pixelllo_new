<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DatabaseService
{
    /**
     * Get the database driver name
     */
    public static function getDriver(): string
    {
        return DB::connection()->getDriverName();
    }

    /**
     * Check if using PostgreSQL
     */
    public static function isPostgreSQL(): bool
    {
        return self::getDriver() === 'pgsql';
    }

    /**
     * Check if using SQLite
     */
    public static function isSQLite(): bool
    {
        return self::getDriver() === 'sqlite';
    }

    /**
     * Get month from date for current database
     */
    public static function getMonthFromDate($column): string
    {
        if (self::isPostgreSQL()) {
            return "EXTRACT(MONTH FROM $column)::text";
        }
        return "strftime('%m', $column)";
    }

    /**
     * Get year from date for current database
     */
    public static function getYearFromDate($column): string
    {
        if (self::isPostgreSQL()) {
            return "EXTRACT(YEAR FROM $column)::text";
        }
        return "strftime('%Y', $column)";
    }

    /**
     * Get current year condition
     */
    public static function getCurrentYearCondition($column): string
    {
        if (self::isPostgreSQL()) {
            return "EXTRACT(YEAR FROM $column) = EXTRACT(YEAR FROM CURRENT_DATE)";
        }
        return "strftime('%Y', $column) = strftime('%Y', 'now')";
    }

    /**
     * Get date format for grouping
     */
    public static function getDateFormat($column, $format): string
    {
        if (self::isPostgreSQL()) {
            switch ($format) {
                case '%Y-%m':
                    return "TO_CHAR($column, 'YYYY-MM')";
                case '%Y-%m-%d':
                    return "TO_CHAR($column, 'YYYY-MM-DD')";
                case '%m':
                    return "TO_CHAR($column, 'MM')";
                case '%Y':
                    return "TO_CHAR($column, 'YYYY')";
                default:
                    return "TO_CHAR($column, 'YYYY-MM-DD')";
            }
        }
        return "strftime('$format', $column)";
    }

    /**
     * Get datetime condition for last N months
     */
    public static function getLastMonthsCondition($column, $months): string
    {
        if (self::isPostgreSQL()) {
            return "$column >= CURRENT_DATE - INTERVAL '$months months'";
        }
        return "$column >= datetime('now', '-$months months')";
    }
}