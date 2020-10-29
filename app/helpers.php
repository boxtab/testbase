<?php

use Illuminate\Support\Str;

if (!function_exists('getUniqueReferralLink')) {

    /**
     * Возвращает уникальную реферальную ссылку с использованием электроного адреса пользователя.
     *
     * @param string $email
     * @return string
     */
    function getUniqueReferralLink(string $email) : string
    {
        return date('YmdHis') . '-' .
            str_replace('@', '_', $email) . '-' .
            Str::random(10);
    }
}
