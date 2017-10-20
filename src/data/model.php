<?php
/**
 * Created by phpcmx/orm.
 * date: {$date}
 * time: {$time}
 */

namespace {$namespace};

use phpcmx\orm\entity\TableEntity;

/**
 * Class {className}
 *
 * @package {namespace}
 *
 * @property int $id
 * @property string $name
 */
class {className} extends TableEntity{

    /**
     * 数据库连接名称（别名）
     *
     * @return string
     */
    public function dbAliaName(): string
    {
        return '{dbAliaName}';
    }


    /**
     * 字段列表（带描述）
     *
     * @return array
     */
    public function attribute(): array
    {
        return [
            'id' => 'ID',
            'name' => '名字',
        ];
    }

    /**
     * 数据库结构描述
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'primaryKey' => 'id',
            'field' => [
                'id' => [
                    'type' => '',
                    'param' => [],
                    'default' => null,
                    'extra' => 4,
                ],
                'name' => [

                ],
            ],
        ];
    }
}