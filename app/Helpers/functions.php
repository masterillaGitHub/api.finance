<?php

/*
* Функція схиляння. До прикладу 1 людина, 2 людини, 5 людей
*/
if (!function_exists('numberOfText')) {
    function numberOfText(int $number, array $suffix)
    {
        $keys = [2, 0, 1, 1, 1, 2];
        $mod  = $number % 100;

        if ($mod > 7 && $mod < 20) {
            $suffixKey = 2;
        }
        else {
            $suffixKey = $keys[ min($mod % 10, 5) ];
        }

        return $suffix[$suffixKey];
    }
}

if (!function_exists('jsonEncode')) {
    function jsonEncode(mixed $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value) && empty($value)) {
            $value = new \stdClass();
        }

        /**
         * JSON_UNESCAPED_UNICODE - Не кодувати багатобайтові символи Unicode (за замовчуваннями кодуються як \uXXXX).
         * JSON_UNESCAPED_SLASHES - Не екранувати /.
         * @var string $value
         */
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('dbQueryLog')) {
    function dbQueryLog(callable $callback): void
    {
        \DB::enableQueryLog(); // Enable query log
        call_user_func($callback);
        dd(\DB::getQueryLog()); // Show results of log
    }
}

if (!function_exists('getFate')) {
    /**
     * @throws Exception
     */
    function getFate(int $probability = 50): bool
    {
        return random_int(0,100) < $probability;
    }
}
