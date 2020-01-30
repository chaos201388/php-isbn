<?php

namespace Chaosbot\ISBN;

class ISBN13
{
    private static $instance;

    protected function __construct() {}

    protected function __clone() {}

    public function __wakeup()
    {
        throw new \Exception('__wakeup Method Not Allowed');
    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function validate(string $isbn13): bool
    {
        if (empty($isbn13)) {
            return false;
        }

        if (preg_match('/^[0-9]{13}$/', $isbn13) !== 1) {
            return false;
        }

        if ($this->parseCheckDigit($isbn13) !== $this->calculateCheckDigit($isbn13)) {
            return false;
        }

        return true;
    }

    private function parseCheckDigit(string $isbn13): string
    {
        return substr($isbn13, -1, 1);
    }

    private function calculateCheckDigit(string $isbn13): string
    {
        $numberArray = str_split($isbn13);
        array_pop($numberArray);

        $multiplierArray = [1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3];

        $resultArray = array_map(
            function ($number, $multiplier) {
                return $number * $multiplier;
            },
            $numberArray, $multiplierArray
        );

        $total = array_sum($resultArray);

        $remainder = $total % 10;

        if ($remainder === 0) {
            return (string) 0;
        } else {
            return (string) (10 - $remainder);
        }
    }
}
