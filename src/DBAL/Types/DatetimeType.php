<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2018-05-07
 */

namespace fk\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class DatetimeType extends \Doctrine\DBAL\Types\DateTimeType
{
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTimeInterface) {
            return $value;
        }

        $val = DateTime::createFromFormat($platform->getDateTimeFormatString(), $value);

        if (!$val) $val = date_create($value);

        if (!$val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        return $val;
    }
}