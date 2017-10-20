<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/13
 * Time: 17:15
 */

namespace phpcmx\orm\entity\std;

/**
 * 数据库字段类型
 * Class TableType
 *
 * @package phpcmx\orm\entity\std
 */
class TableType
{
    private function __construct(){}

    const TINYINT = 'tinyint';
    const SMALLINT = 'smallint';
    const MEDIUMINT = 'mediumint';
    const INT = 'int';
    const BIGINT = 'bigint';
    const FLOAT = 'float';
    const DOUBLE = 'double';
    const DECIMAL = 'decimal';

    const CHAR = 'char';
    const VARCHAR = 'varchar';
    const TINYTEXT = 'tinytext';
    const TEXT = 'text';
    const BLOB = 'blob';
    const MEDIUMTEXT = 'mediumtext';
    const LONGTEXT = 'longtext';
    const ENUM = 'enum';
    const SET = 'set';

    const DATE = 'date';
    const DATETIME = 'datetime';
    const TIMESTAMP = 'timestamp';
    const TIME = 'time';
    const YEAR = 'year';
}