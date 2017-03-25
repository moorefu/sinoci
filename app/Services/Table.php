<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * 框架组件 - 数据表
 *
 * @package App\Services
 */
class Table extends Eloquent
{

    /**
     * 是否使用 created_at, updated_at
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 拼接 like 搜索
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param mixed $val
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLike($query, $key, $val)
    {
        // like 查询
        return $query->where($key, 'like', "%{$val}%");
    }

    /**
     * 拼接 like raw 搜索
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param mixed $val
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLikeRaw($query, $key, $val)
    {
        // like 拼接查询
        return $query->whereRaw("{$key} like '%{$val}%'");
    }

    /**
     * 拼接 or like 搜索
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param mixed $val
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrLike($query, $key, $val)
    {
        // orLike 查询
        return $query->orWhere($key, 'like', "%{$val}%");
    }

    /**
     * 拼接 or like raw 搜索
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param mixed $val
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrLikeRaw($query, $key, $val)
    {
        // orLike 拼接查询
        return $query->orWhereRaw("{$key} like '%{$val}%'");
    }

    /**
     * 获取相应分页或列表
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $perPage
     * @return mixed
     */
    public function scopeGetList($query, $perPage = null)
    {
        $perPage = $perPage ?: request()->get('limit');
        $query = $query->orderBy($this->primaryKey, 'desc');
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

}
