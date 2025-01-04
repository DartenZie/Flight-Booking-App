<?php

namespace App\utils;

use DateTime;
use App\Exceptions\ValidationException;

class InputValidator {

    /**
     * Validates that the required fields are present and not empty in the provided data array.
     *
     * @param array $data The associative array containing the data to validate.
     * @param array $fields An array of field names that are required.
     * @throws ValidationException If any of the required fields are missing or empty.
     */
    public static function required(array $data, array $fields): void {
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                throw new ValidationException("Field `$field` is required.");
            }
        }
    }

    /**
     * Sanitizes a given string by removing HTML and PHP tags and escaping special characters.
     *
     * @param mixed $input The input to be sanitized; must be a valid string.
     * @return string A sanitized and safe string.
     * @throws ValidationException If the input is not a string or becomes invalid after sanitization.
     */
    public static function sanitizeString(mixed $input): string {
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
     * Sanitizes the given email input by removing any illegal characters.
     *
     * @param mixed $input The input to be sanitized, expected to be an email address.
     * @return string The sanitized email address.
     * @throws ValidationException If the input is not a valid email after sanitization.
     */
    public static function sanitizeEmail(mixed $input): string {
        $sanitized = filter_var($input, FILTER_SANITIZE_EMAIL);
        if ($sanitized === false) {
            throw new ValidationException('Invalid email input.');
        }
        return $sanitized;
    }

    /**
     * Sanitizes and validates the input for sex, ensuring it is either 'male' or 'female'.
     *
     * @param mixed $input The input value to be sanitized and validated.
     * @return string The sanitized and validated input value in lowercase format.
     * @throws ValidationException If the input is invalid or not one of the allowed values.
     */
    public static function sanitizeSex(mixed $input): string {
        $allowedValues = ['male', 'female'];

        $sanitized = filter_var($input, FILTER_SANITIZE_STRING);
        if ($sanitized === false || !in_array(strtolower($sanitized), $allowedValues, true)) {
            throw new ValidationException('Invalid sex input. Allowed values are: male, female.');
        }
        return strtolower($sanitized);
    }

    /**
     * Sanitizes the input to ensure it is a valid integer.
     *
     * @param mixed $input The input value to be sanitized.
     * @return int The sanitized integer value.
     * @throws ValidationException If the input is not a valid integer.
     */
    public static function sanitizeInt(mixed $input): int{
        $sanitized = filter_var($input, FILTER_VALIDATE_INT);
        if ($sanitized === false) {
            throw new ValidationException('Invalid integer input.');
        }
        return $sanitized;
    }

    /**
     * Sanitizes a boolean input by converting it to an integer value.
     *
     * @param mixed $input The input value to be sanitized. Expected to be of type boolean.
     * @return int Returns 1 if the input is true, or 0 if the input is false.
     * @throws ValidationException If the input is not a boolean value.
     */
    public static function sanitizeBool(mixed $input): int {
        if (!is_bool($input)) {
            throw new ValidationException('Invalid input: expected a boolean value.');
        }
        return $input === true ? 1 : 0;
    }

    /**
     * Sanitizes and validates a given date-time string against allowed formats.
     * If the input matches one of the specified formats, it normalizes the date-time
     * to the format 'Y-m-d'. Throws an exception otherwise.
     *
     * @param string $dateTime The date-time string to sanitize.
     * @param array $formats An array of allowed date-time formats for validation.
     * @return string The sanitized and normalized date in 'Y-m-d' format.
     * @throws ValidationException If the date-time string does not match any allowed formats.
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
     * Sanitizes a date-time string by validating it against a list of allowed formats
     * and returning it in a standard 'Y-m-d H:i:s' format.
     *
     * @param string $dateTime The date-time string to sanitize.
     * @param array $formats An array of acceptable date-time formats to validate against. Defaults to ['Y-m-d H:i:s', 'Y-m-d', 'Y-m-d\TH:i'].
     * @return string The sanitized date-time in 'Y-m-d H:i:s' format.
     * @throws ValidationException If the input does not match any of the allowed formats.
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
