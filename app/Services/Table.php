<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 框架组件 - 数据表
 *
 * @package App\Services
 */
class Table extends Eloquent
{

    /**
     * 使用软删除
     */
    use SoftDeletes;

    /**
     * 应用时间格式
     */
    protected $dates = ['deleted_at'];

    /**
     * 数据表结构
     */
    protected $schema = [];

    /**
     * 构造函数
     */
    public function __construct($attributes = [])
    {
        empty($this->fillable) && $this->fillable = array_keys($this->schema());

        $this->table OR $this->table = snake_case(class_basename($this));

        parent::__construct($attributes);
    }

    /**
     * 插入数据
     */
    public function insert($data = [])
    {
        $defaults = array_pluck($this->schema(), 'default');

        $data += array_combine($this->fillable, $defaults);

        return static::create($data);
    }

    /**
     * 创建数据表
     */
    public function make()
    {
        return table($this->table, function ($table) {
            $table->increments($this->primaryKey);
            $table->nullableTimestamps();
            $table->softDeletes();

            array_walk($this->schema, function ($value, $key) use ($table) {
                $table->{$value['type']}($key)->nullable();
            });
        });
    }

    /**
     * 返回表结构
     */
    protected function schema()
    {
        $this->schema += [];

        return array_except($this->schema, $this->primaryKey);
    }

}
