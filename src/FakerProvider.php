<?php

namespace Ensi\TestFactories;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use Faker\Provider\Base;

class FakerProvider extends Base
{
    // null - без изменений, false - всегда будет дефолтное значение, true - всегда будет сгенерированное значение
    public static ?bool $optionalAlways = null;
    public static array $optionalDataset = [null, true, false];

    /**
     * @param null|bool $always
     * @param null $default
     *
     * @return static
     */
    public function nullable(?bool $always = null, $default = null)
    {
        $weight = 0.5;
        if (!is_null($always)) {
            $weight = $always ? 100 : 0;
        } elseif (!is_null(static::$optionalAlways)) {
            $weight = static::$optionalAlways ? 100 : 0;
        }

        return parent::optional($weight, $default);
    }

    /**
     * Сгенерировать массив значений. Совместим с $optionalAlways
     *
     * @param callable $f колбэк, возвращающий значения для массива
     * @param int $min минимальное кол-во в массиве
     * @param int $max максимальное кол-во в массиве
     *
     * @return array
     */
    public function randomList(callable $f, int $min = 0, int $max = 10): array
    {
        $result = [];
        if (!is_null(static::$optionalAlways)) {
            if (static::$optionalAlways) {
                $min = max(1, $min);
            } elseif (!$min) {
                return [];
            }
        }
        $count = $this->generator->numberBetween($min, $max);
        for ($i = 0; $i < $count; $i++) {
            $result[] = $f();
        }

        return $result;
    }

    /**
     * Вернуть фейкером конкретное значение
     *
     * @param $value
     *
     * @return mixed
     */
    public function exactly($value): mixed
    {
        return $value;
    }

    /**
     * Сгенерировать дату Carbon
     * @param DateTime|int|string $max
     * @param string|null $timezone
     * @return Carbon
     */
    public function carbon(DateTime|int|string $max = 'now', ?string $timezone = null): Carbon
    {
        return new Carbon($this->generator->dateTime($max, $timezone));
    }

    /**
     * Сгенерировать дату CarbonImmutable
     * @param DateTime|int|string $max
     * @param string|null $timezone
     * @return CarbonImmutable
     */
    public function carbonImmutable(DateTime|int|string $max = 'now', ?string $timezone = null): CarbonImmutable
    {
        return new CarbonImmutable($this->generator->dateTime($max, $timezone));
    }

    /**
     * Сгенерировать дату, которая будет больше чем заданная
     *
     * @param null|DateTime $dateFrom Минимальная дата. Если не задана, сгенерируется любая дата
     * @param null|string $format Формат даты для ответа. Если формат не задан, вернётся объект
     *
     * @return DateTime|string
     */
    public function dateMore(?DateTime $dateFrom = null, ?string $format = null): DateTime|string
    {
        if ($dateFrom) {
            $date = $this->generator->dateTimeBetween($dateFrom);
        } else {
            $date = $this->generator->dateTime();
        }

        if ($format) {
            return $date->format($format);
        } else {
            return $date;
        }
    }

    /**
     * Сгенерировать ID сущности
     */
    public function modelId(): int
    {
        return $this->generator->numberBetween(1);
    }

    /**
     * Получить value случайного значения
     */
    public function randomEnum(array $cases): mixed
    {
        return $this->generator->randomElement($cases)->value;
    }
}
