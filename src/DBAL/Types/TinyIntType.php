<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TinyIntType extends Type
{

    public const NAME = 'tinyint';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'TINYINT ' . substr($platform->getIntegerTypeDeclarationSQL($fieldDeclaration), 3);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return parent::convertToDatabaseValue($value, $platform); // TODO: Change the autogenerated stub
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : (int)parent::convertToPHPValue($value, $platform);
    }

    public function getBindingType()
    {
        return \PDO::PARAM_INT;
    }

    public function getName()
    {
        return self::NAME;
    }
}