<?php

namespace App\Services;

use Illuminate\Pagination\BootstrapThreePresenter;

/**
 * 框架组件 - 列表分页
 *
 * @package App\Services
 */
class Pagination extends BootstrapThreePresenter
{

    /**
     * 渲染分页
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        // 单页时返回空
        if (empty($this->hasPages())) {
            return '';
        }

        // 分页模板所需数据
        $data = [
            'current' => $this->currentPage(),
            'default' => parent::render(),
            'query' => array_except(request()->get(), 'page'),
            'total' => $this->lastPage()
        ];

        // 渲染模板
        return view(config('pagination.view'), compact('data'));
    }

    /**
     * 上一页按钮内容
     *
     * @param string $text
     * @return string
     */
    protected function getPreviousButton($text = null)
    {
        // 上一页自定义
        return parent::getPreviousButton($text ?: config('pagination.prev'));
    }

    /**
     * 下一页按钮内容
     *
     * @param string $text
     * @return string
     */
    protected function getNextButton($text = null)
    {
        // 下一页自定义
        return parent::getNextButton($text ?: config('pagination.next'));
    }

    /**
     * 生成分页链接
     *
     * @return string[]
     */
    protected function getLinks()
    {
        // 两端个数
        $sideNum = config('pagination.side');

        // 获取总页数和当前页
        $last = $this->lastPage();
        $current = $this->currentPage();

        // 取得分页开始和结束
        $start = $current - $sideNum;
        $end = $current + $sideNum;

        // 处理分页结尾
        if ($start > $last - $sideNum * 2) {
            $start = $last - $sideNum * 2;
            $end = $last;
        }

        // 处理分页开头
        if ($start < 1) {
            $start = 1;
            $end = $last > $sideNum * 2 ? $sideNum * 2 + 1 : $last;
        }

        // 获取分页链接
        $slider = $this->paginator->getUrlRange($start, $end);

        // 返回分页主干
        return $this->getUrlLinks($slider);
    }

}
