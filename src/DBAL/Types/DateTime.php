<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2018-05-07
 */

namespace fk\Doctrine\DBAL\Types;

use DateTimeZone;

class DateTime extends \DateTime implements \JsonSerializable
{

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->format('Y-m-d\TH:i:s');
    }

    public static function createFromFormat($format, $time, DateTimeZone $timezone = null)
    {
        $datetime = parent::createFromFormat($format, $time, $timezone);

        $date = new static($datetime->format('Y-m-d H:i:s'), $timezone);
        return $date;
    }
}