<?php

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
        $sanitized = filter_var($input, FILTER_SANITIZE_STRING);
        if ($sanitized === false) {
            throw new ValidationException('Invalid string input.');
        }
        return $sanitized;
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
    public static function sanitizeDateTime(string $dateTime, array $formats = ['Y-m-d H:i:s', 'Y-m-d']): string {
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $dateTime);
            if ($date && $date->format($format) === $dateTime) {
                return $date->format('Y-m-d H:i:s');
            }
        }
        throw new ValidationException("Invalid date-time format. Expected formats: " . implode(', ', $formats) . ".");
    }
}
