<?php

namespace App\Http\Helpers\Structures;

class DealStages
{
    /**
     * @var array|array[]
     */
    private static array $stages = [
        '0' =>
            [
                'NEW'       => 'Новая',
                'UC_8F8ZER' => 'Название',
                'WON'       => 'Сделка успешна',
                'LOSE'      => 'Сделка провалена',
                'APOLOGY'   => 'Анализ причины провала',
            ],
        'C36' =>
            [
                'NEW'                   => 'Ожидание',
                'PREPARATION'           => 'Первичный контакт',
                '12'                    => 'Первый недозвон',
                'UC_4K6891'             => 'Недозвон',
                'PREPAYMENT_INVOICE'    => 'Недозвон (более 3-х раз)',
                'EXECUTING'             => 'Первичный отказ',
                'FINAL_INVOICE'         => 'На следующий год',
                '1'                     => 'Дожим на встречу',
                '2'                     => 'Ожидание встречи',
                '3'                     => 'II. Касание | Голосовое',
                '4'                     => 'III. Касание | Материалы',
                '5'                     => 'IV. Касание | Закр. на 2 встречу',
                '6'                     => '0жидание повт.встр.',
                '9'                     => 'На другой месяц | Теплый',
                '7'                     => 'На текущий месяц | Горячие',
                '11'                    => 'Проработка Ипотеки',
                '8'                     => 'Предоплата',
                'WON'                   => 'Сделка успешна',
                'LOSE'                  => 'Сделка провалена',
                'APOLOGY'               => 'Анализ причины провала',
            ],
        'C34' =>
            [
                'NEW'           => 'Ожидание',
                'PREPARATION'   => 'Первичная консультация',
                'EXECUTING'     => 'Подача заявки',
                '4'             => 'Предварительный отказ',
                '5'             => 'Одобрение банком',
                'FINAL_INVOICE' => 'Согласование документов',
                'WON'           => 'Сделка успешна',
                'LOSE'          => 'Сделка провалена',
            ],
        'C32' =>
            [
                'NEW'                   => 'База',
                'PREPARATION'           => 'Квалификация',
                'PREPAYMENT_INVOICE'    => 'Просмотр условий',
                '1'                     => 'Подписание договора',
                'EXECUTING'             => 'Активные партнеры',
                'FINAL_INVOICE'         => 'Ожидание продажи',
                'WON'                   => 'Сделка успешна',
                'LOSE'                  => 'Сделка провалена',
                'APOLOGY'               => 'Анализ причины провала',
            ],
        'C42' =>
            [
                'NEW'       => 'На следующий год',
                'UC_SKDNES' => 'Предварительный отказ',
                'EXECUTING' => 'Отказ',
                'UC_1NCSOG' => 'Встреча назначена',
                'WON'       => 'Сделка успешна',
                'LOSE'      => 'Сделка провалена',
                'APOLOGY'   => 'Анализ причины провала',
            ],
        'C44' =>
            [
                'NEW'               => 'Ожидание',
                'PREPARATION'       => 'Строительство',
                'PREPAYMENT_INVOIC' => 'Акт приёма/передачи',
                'EXECUTING'         => 'Ожидание оплаты',
                'FINAL_INVOICE'     => 'Оплата получена',
                'UC_E0PYL7'         => 'Product support services',
                'WON'               => 'Сделка успешно реализована',
                'LOSE'              => 'Сделка провалена',
                'APOLOGY'           => 'Анализ причины провала',
            ],
        'C46' =>
            [
                'NEW'               => 'Ожидание',
                'PREPARATION'       => 'Первичный контакт',
                'PREPAYMENT_INVOIC' => 'Дожим на встречу',
                'EXECUTING'         => 'Ожидание встречи',
                'UC_Y7SKD4'         => 'Выезд на стройку / Повышение лояльности',
                'UC_A2WWRO'         => 'Дожим на пред. договор',
                'UC_EDOOX9'         => 'Подготовка к ДСП',
                'UC_RCTO73'         => 'Заключение ДСП',
                'UC_VGBRMY'         => 'Ожидание оплаты',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Проверка отказа',
                '1'                 => 'Сделка провалена',
            ],
        'C60' =>
            [
                'NEW'               => 'Партнеры на проработку',
                'PREPARATION'       => 'Предварительная договоренность',
                'PREPAYMENT_INVOIC' => 'Подписание договора / обучение',
                'EXECUTING'         => 'Ожидание клиента / Активные партнеры',
                'UC_JWI4PG'         => 'Проработка клиента',
                'FINAL_INVOICE'     => 'Получение ДС от клиента',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Сделка провалена',
            ],
        'C50' =>
            [
                'UC_0X23YB'         => 'Проверка сделки',
                'NEW'               => 'На следующий год',
                'PREPARATION'       => 'Отказ',
                'PREPAYMENT_INVOIC' => 'Встреча назначена',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Сделка провалена',
                'APOLOGY'           => 'Анализ причины провала',
            ],
        'C52' =>
            [
                'NEW'               => 'Заявка на сотрудничество',
                'PREPARATION'       => 'Заявка на сотрудничество',
                'PREPAYMENT_INVOIC' => 'Агентский договор',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Сделка провалена',
                'APOLOGY'           => 'Анализ причины провала',
            ],
        'C70' =>
            [
                'NEW'               => 'Ожидание',
                'PREPARATION'       => 'Квалификация',
                'PREPAYMENT_INVOIC' => 'Подбор Авто',
                'UC_464X4H'         => 'Договор осмотра',
                'UC_SJQTZH'         => 'Основной договор',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Сделка провалена',
            ],
        'C80' =>
            [
                'NEW'           => 'Ожидание',
                'PREPARATION'   => 'В работе',
                'WON'           => 'Сделка успешна',
                'LOSE'          => 'Сделка провалена',
                'APOLOGY'       => 'Анализ причины провала',
            ],
        'C94' =>
            [
                'NEW'       => 'ХОЛОДНАЯ БАЗА',
                'EXECUTING' => 'Первичный контакт',
                'UC_WPB5B5' => 'ПОТЕНЦИАЛЬНЫЕ ПАРТНЕРЫ',
                'UC_MDFV4R' => 'ВСТРЕЧА',
                'UC_FK7L5G' => 'ДОЖИМ',
                'UC_YOBXK5' => 'ДОГОВОР',
                'UC_767C1L' => 'Оплата счёта',
                'WON'       => 'Сделка успешна',
                'LOSE'      => 'Сделка провалена',
            ],
        'C131' =>
            [
                'NEW'               => 'Неразобранные заявки',
                'UC_P7M5NG'         => 'Недозвон',
                'PREPARATION'       => 'Квалификация',
                'PREPAYMENT_INVOI'  => 'Назначение встречи/выезда',
                'UC_ANFHRF'         => 'Ожидание встречи / выезда',
                'UC_90F6RO'         => 'Ожидание проекта/расчёта',
                'UC_X97DFD'         => 'Дожим на договор',
                'UC_BQW3GK'         => 'Исполнение проекта',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Сделка провалена',
            ],
        'C133' =>
            [
                'NEW'           => 'Недозвон',
                'UC_P9RBK0'     => 'Актуально но позже (Амо)',
                'UC_O4SOYL'     => 'Из АМО',
                'FINAL_INVOICE' => 'Дожим на контакт',
                'UC_ZYZ0JM'     => 'ЛЕТО',
                'UC_1Q2QZ1'     => 'Осень 23',
                'UC_52V599'     => '2024 год',
                'WON'           => 'Сделка успешна',
                'LOSE'          => 'Сделка провалена',
                'APOLOGY'       => 'Анализ причины провала',
            ],
        'C90' =>
            [
                'NEW'               => 'Ожидание',
                'PREPARATION'       => 'Квалификация',
                'PREPAYMENT_INVOIC' => 'Назначение встречи',
                'EXECUTING'         => 'Предварительный расчет',
                'FINAL_INVOICE'     => 'Встреча в офисе',
                'UC_ZYUW5N'         => 'Выезд на замер',
                'UC_M7QAO4'         => 'Подготовка расчета',
                'UC_ZLW2NG'         => 'Поставка / Монтаж',
                'UC_A2Z8G2'         => 'Получение ДС',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Проверка отказа',
                'APOLOGY'           => 'Не реализовано',
            ],
        'C116' =>
            [
                'NEW'               => 'Ожидание обработки',
                'PREPARATION'       => 'Первичный контакт',
                'PREPAYMENT_INVOI'  => 'Дожим на встречу',
                'UC_X35L0B'         => 'Проведение встречи',
                'UC_CLB7A9'         => 'Проведение замера',
                'UC_2PPAH5'         => 'Расчет',
                'EXECUTING'         => 'Дожим на договор',
                'FINAL_INVOICE'     => 'Заключение договора | Предоплата',
                'UC_M1DQYG'         => 'Завершение монтажа | Отгрузка товара',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Проверка отказа',
                '1'                 => 'Закрытая сделка',
            ],
        'C153' =>
            [
                'NEW'           => 'Квалификация',
                'PREPARATION'   => 'Первичный контакт',
                'WON'           => 'Сделка успешна',
                'LOSE'          => 'Сделка провалена',
            ],
        'C92' =>
            [
                'NEW'           => 'Ожидание',
                'PREPARATION'   => 'Квалификация',
                'UC_TPFVF3'     => 'Думает',
                'UC_R4YRTH'     => 'Согласование встречи',
                'UC_7JJF2G'     => 'Ожидание встречи',
                'UC_UL38WK'     => 'Встреча проведена',
                'UC_FUWVU9'     => 'Согласование договора',
                'UC_UPERT2'     => 'Ожидание оплаты',
                'UC_DNWX7F'     => 'Отгрузка',
                'WON'           => 'Успешно реализованно',
                'LOSE'          => 'Закрытая сделка',
                '1'             => 'Отказались',
                '2'             => 'Не дозвонились',
                '3'             => 'Нет возможности связаться',
            ],
        'C108' =>
            [
                'NEW'           => 'Активные партнеры',
                'PREPARATION'   => 'Ждем оплату',
                'UC_G428L3'     => 'Отгрузка',
                '3'             => 'расторжение договора',
                'WON'           => 'Отгрузка прошла',
                'LOSE'          => 'Закрыто',
            ],
        'C129' =>
            [
                'NEW'   => 'Новая',
                'WON'   => 'Сделка успешна',
                'LOSE'  => 'Сделка провалена',
            ],
        'C110' =>
            [
                'NEW'               => 'Новые лиды',
                'PREPARATION'       => 'Взято в работу',
                'PREPAYMENT_INVOI'  => 'Назначен замер',
                'EXECUTING'         => 'выезд замерщика',
                'FINAL_INVOICE'     => 'Производство фильтра',
                '2'                 => 'Установка произведена',
                'WON'               => 'Успешно реализовано',
                'LOSE'              => 'Не реализовано',
            ],
        'C139' =>
            [
                'NEW'           => 'Ожидание',
                'UC_B53T71'     => 'Первичный контакт',
                'PREPARATION'   => 'Назначить встречу',
                'EXECUTING'     => 'Провести встречу',
                'FINAL_INVOICE' => 'Дожать до сделки/заключить договор',
                'UC_FLMML9'     => 'Получить оплату',
                'WON'           => 'Сделка успешна',
                'LOSE'          => 'Сделка провалена',
            ],
        'C135' =>
            [
                'NEW'               => 'Новая',
                'PREPARATION'       => 'Подготовка документов',
                'PREPAYMENT_INVOI'  => 'Cчёт на предоплату',
                'EXECUTING'         => 'В работе',
                'FINAL_INVOICE'     => 'Финальный счёт',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Сделка провалена',
                'APOLOGY'           => 'Анализ причины провала',
            ],
        'C157' =>
            [
                'NEW'           => 'Активировал чат-бот',
                'FINAL_INVOICE' => 'Прошёл анкетирование',
                'UC_UJT6FE'     => 'Записался на диагностику',
                'UC_0TMR75'     => 'Прошёл диагностику',
                'UC_YSVEOZ'     => 'Предоплата',
                'UC_4NXKZG'     => 'Оплата',
                'UC_L8FUQY'     => 'Обучение',
                'WON'           => 'Сделка успешна',
                'LOSE'          => 'Сделка провалена',
                'APOLOGY'       => 'Анализ причины провала',
            ],
        'C161' =>
            [
                'NEW'       => 'Запись на вебинар',
                'UC_VDC1ZG' => 'Вебинар',
                'UC_TPYU4M' => 'Этап 3',
                'WON'       => 'Сделка успешна',
                'LOSE'      => 'Сделка провалена',
                'APOLOGY'   => 'Анализ причины провала',
            ],
        'C167' =>
            [
                'NEW'               => 'Предзапись',
                'PREPARATION'       => 'диагностика',
                'PREPAYMENT_INVOI'  => 'предоплата',
                'EXECUTING'         => 'оплата',
                'FINAL_INVOICE'     => 'обучение',
                'WON'               => 'Сделка успешна',
                'LOSE'              => 'Сделка провалена',
                'APOLOGY'           => 'Анализ причины провала',
            ],
    ];
    private static array $directions = [
        '0'     => 'Общая',
        'C36'   => 'Строительство | Готовые решения',
        'C34'   => 'Строительство | Проработка ипотеки',
        'C32'   => 'Строительство | Партнеры',
        'C42'   => 'Первичный отказ',
        'C44'   => 'Строительство',
        'C46'   => 'Воронка продаж',
        'C60'   => 'Партнерский канал',
        'C50'   => 'Отказная воронка',
        'C52'   => 'Реферальная программа',
        'C70'   => 'Тиксан Авто (b2c)',
        'C80'   => 'СТП',
        'C94'   => 'СТП развитие',
        'C131'  => 'Отопить.РФ | МСК',
        'C133'  => 'Отопить.РФ | Холодные звонки (МСК)',
        'C90'   => 'Отопить.РФ | ОП 52-ой квартал',
        'C116'  => 'Отопить.рф | Авиаторов',
        'C153'  => 'Отопить.РФ | Регионы',
        'C92'   => 'NF | Развитие',
        'C108'  => 'NF | Дилеры',
        'C129'  => 'NF | Заявки дилеров',
        'C110'  => 'NF | Розница',
        'C139'  => 'NF | Партнерская сеть',
        'C135'  => 'КОД ЛИДЕРА',
        'C157'  => 'Бизнес Академия',
        'C161'  => 'Бизнес Академия: Вебинары',
        'C167'  => 'Лёгкий бизнес',
    ];

    /**
     * @param string|null $key
     * @param string $code
     * @return string
     */
    public static function getStageName(?string $key, string $code): string{
        if ($key !== null && ((!isset(self::$stages[$key])) || !isset(self::$stages[$key][$code])) && !isset(self::$stages['0'][$code])){
            return $code;
        }
        if (!isset(self::$stages[$key][$code]) && isset(self::$stages['0'][$code])){
            return self::$stages['0'][$code];
        }
        return self::$stages[$key ?? '0'][$code];
    }

    /**
     * @param string|null $key
     * @return array|mixed|string[]
     */
    public static function getStagesByDirection(?string $key){
        if ($key !== null && !isset(self::$stages[$key])){
            return self::$stages['0'];
        }
        return self::$stages[$key ?? '0'];
    }

    /**
     * @return array|array[]
     */
    public static function getAllStages(): array{
        return self::$stages;
    }

    public static function getDirectionName(?string $direction): string{
        if ($direction === null){
            return $direction;
        }
        if (!isset(self::$directions[$direction])){
            return $direction;
        }
        return self::$directions[$direction];
    }
}
