<?php

namespace App\utils;

use DateTime;
use App\Exceptions\ValidationException;

class InputValidator {
    /**
     * @throws ValidationException
     */
    public static function required(array $data, array $fields): void {
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                throw new ValidationException("Field `$field` is required.");
            }
        }
    }

    /**
     * @throws ValidationException
     */
    public static function sanitizeString($input): string {
        if (!is_string($input)) {
            throw new ValidationException('Invalid input: expected a string.');
        }

        $sanitized = htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
        if (trim($sanitized) === '') {
            throw new ValidationException('Invalid string input: input is empty or unsafe after sanitization.');
        }

        return $sanitized;
    }

    /**
     * @throws ValidationException
     */
    public static function sanitizeEmail($input): string {
        $sanitized = filter_var($input, FILTER_SANITIZE_EMAIL);
        if ($sanitized === false) {
            throw new ValidationException('Invalid email input.');
        }
        return $sanitized;
    }

    /**
     * @throws ValidationException
     */
    public static function sanitizeSex($input): string {
        $allowedValues = ['male', 'female'];

        $sanitized = filter_var($input, FILTER_SANITIZE_STRING);
        if ($sanitized === false || !in_array(strtolower($sanitized), $allowedValues, true)) {
            throw new ValidationException('Invalid sex input. Allowed values are: male, female.');
        }
        return strtolower($sanitized);
    }

    /**
     * @throws ValidationException
     */
    public static function sanitizeInt($input): int{
        $sanitized = filter_var($input, FILTER_VALIDATE_INT);
        if ($sanitized === false) {
            throw new ValidationException('Invalid integer input.');
        }
        return $sanitized;
    }

    /**
     * @throws ValidationException
     */
    public static function sanitizeDate(string $dateTime, array $formats = ['Y-m-d H:i:s', 'Y-m-d', 'Y-m-d\TH:i']): string {
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $dateTime);
            if ($date && $date->format($format) === $dateTime) {
                return $date->format('Y-m-d');
            }
        }
        throw new ValidationException("Invalid date-time format. Expected formats: " . implode(', ', $formats) . ".");
    }

    /**
     * @throws ValidationException
     */
    public static function sanitizeDateTime(string $dateTime, array $formats = ['Y-m-d H:i:s', 'Y-m-d', 'Y-m-d\TH:i']): string {
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $dateTime);
            if ($date && $date->format($format) === $dateTime) {
                return $date->format('Y-m-d H:i:s');
            }
        }
        throw new ValidationException("Invalid date-time format. Expected formats: " . implode(', ', $formats) . ".");
    }
}
