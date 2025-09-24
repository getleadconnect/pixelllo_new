<?php

/**
 * Polyfill for mbstring functions when the extension is not available
 */

if (!function_exists('mb_strimwidth')) {
    /**
     * Get truncated string with specified width
     *
     * @param string $string The string being decoded
     * @param int $start The start position offset
     * @param int $width The width of the desired trim
     * @param string $trim_marker A string that is added to the end of string when string is truncated
     * @param string|null $encoding The encoding parameter
     * @return string The truncated string
     */
    function mb_strimwidth($string, $start, $width, $trim_marker = '', $encoding = null) {
        if ($encoding === null) {
            $encoding = 'UTF-8';
        }

        // For ASCII strings, use simple substr
        if (preg_match('/^[\x00-\x7F]*$/', $string)) {
            $str = substr($string, $start);
            if (strlen($str) > $width) {
                return substr($str, 0, $width - strlen($trim_marker)) . $trim_marker;
            }
            return $str;
        }

        // For UTF-8 strings, handle multibyte characters
        $str = substr($string, $start);
        $current_width = 0;
        $result = '';
        $chars = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $char) {
            $char_width = strlen($char) == 1 ? 1 : 2; // Simple approximation
            if ($current_width + $char_width > $width) {
                return $result . $trim_marker;
            }
            $result .= $char;
            $current_width += $char_width;
        }

        return $result;
    }
}

if (!function_exists('mb_strlen')) {
    /**
     * Get string length
     *
     * @param string $string The string being checked for length
     * @param string|null $encoding The encoding parameter
     * @return int The number of characters in string
     */
    function mb_strlen($string, $encoding = null) {
        if ($encoding === null || $encoding === 'UTF-8') {
            return strlen(utf8_decode($string));
        }
        return strlen($string);
    }
}

if (!function_exists('mb_substr')) {
    /**
     * Get part of string
     *
     * @param string $string The string to extract the substring from
     * @param int $start Position to start extraction
     * @param int|null $length Maximum number of characters to extract
     * @param string|null $encoding The encoding parameter
     * @return string The extracted substring
     */
    function mb_substr($string, $start, $length = null, $encoding = null) {
        if ($encoding === null || $encoding === 'UTF-8') {
            // Use regex for UTF-8 substring
            preg_match_all('/./u', $string, $chars);
            $chars = $chars[0];

            if ($length === null) {
                return implode('', array_slice($chars, $start));
            }

            return implode('', array_slice($chars, $start, $length));
        }

        if ($length === null) {
            return substr($string, $start);
        }
        return substr($string, $start, $length);
    }
}

if (!function_exists('mb_strwidth')) {
    /**
     * Return width of string
     *
     * @param string $string The string being decoded
     * @param string|null $encoding The encoding parameter
     * @return int The width of string
     */
    function mb_strwidth($string, $encoding = null) {
        if ($encoding === null) {
            $encoding = 'UTF-8';
        }

        $width = 0;
        $chars = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $char) {
            // Simple approximation: ASCII = 1, other = 2
            $width += strlen($char) == 1 ? 1 : 2;
        }

        return $width;
    }
}