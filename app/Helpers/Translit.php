<?php

if (!function_exists('cyrToLat')) {
    function cyrToLat(string $value): string
    {
        // приводимо до нижнього регістру
        $value = mb_strtolower($value);

        // Перекладаємо на трансліт
        $value = str_replace(cyr(), lat(), $value);

        // Замінюємо пробіли на дефіси
        $value = preg_replace('~[^\pL\d]+~u', '-', $value);

        // Видаляємо небажані символи
        $value = preg_replace('~[^-\w]+~', '', $value);

        // Видаляємо подвійні дефіси
        $value = preg_replace('~-+~', '-', $value);

        // Видаляємо дефіси на початку і в кінці
        return trim($value, '-');
    }

    function cyr(): array
    {
        return [
            'щ',
            'ш',
            'ч',
            'ц',
            'ю',
            'я',
            'ж',
            'ъ',
            'ь',
            'а',
            'б',
            'в',
            'г',
            'д',
            'е',
            'ё',
            'з',
            'и',
            'й',
            'к',
            'л',
            'м',
            'н',
            'о',
            'п',
            'р',
            'с',
            'т',
            'у',
            'ф',
            'х',
            'ы',
            'э'
        ];
    }

    function lat(): array
    {
        return [
            'shch',
            'sh',
            'ch',
            'ts',
            'yu',
            'ya',
            'zh',
            '',
            '',
            'a',
            'b',
            'v',
            'g',
            'd',
            'e',
            'e',
            'z',
            'i',
            'y',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'r',
            's',
            't',
            'u',
            'f',
            'h',
            'y',
            'e'
        ];
    }
}
