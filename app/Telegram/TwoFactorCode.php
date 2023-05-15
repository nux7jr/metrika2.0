<?php
namespace Telegram;

class TwoFactorCode
{
    static public function sendTelegramCode($user){

        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => 'https://api.telegram.org/bot5831737772:AAH3sRR0fja_iEb1cPcjMbfpcdYwwVj_IqY/sendMessage', // bot Дениса
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => array(
                    'chat_id' => $user->telegram_chat_id,
                    'text' => __('Ваш код: ').$user->two_factor_code.__("\n Срок действия кода — 2 минуты"),
                ),
            )
        );
        curl_exec($ch);
    }
}
