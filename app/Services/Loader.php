<?php

namespace App\Services;

use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Formatter\Crunched;
use Leafo\ScssPhp\Server;

/**
 * 框架组件 - 资源加载
 *
 * @package App\Services
 */
class Loader
{

    /**
     * 静态资源加载
     *
     * @param string $name
     * @return void
     */
    public function assets($name)
    {
        // 解码资源名
        $name = rawurldecode($name);

        // 资源类型
        $type = head(explode('/', $name));

        // 编译插件
        method_exists($this, $type) OR $type = 'raw';

        // 加载资源
        call_user_func([$this, $type], $name);

        // 结束程序
        exit;
    }

    /**
     * 原始资源加载
     *
     * @param string $name
     * @return \CI_Output
     */
    public function raw($name, $prefix = false)
    {
        // 资源路径
        $prefix OR $name = APPPATH . 'resources/assets/' . $name;

        // 文件是否存在
        file_exists($name) OR show_404();

        // 加载输出类库
        $output = load_class('Output', 'core');

        // 设置响应类型
        $output->set_content_type(pathinfo($name, PATHINFO_EXTENSION));

        // 输出响应
        $output->set_output(file_get_contents($name))->_display();
    }

    /**
     * scss 文件编译
     *
     * @param string $name
     * @return void
     */
    public function scss($name)
    {
        // 优化文件名
        $name && $_GET['p'] = $name;

        // scss 编译器
        $scss = new Compiler;
        $scss->setFormatter(Crunched::class);
        $scss->addImportPath(APPPATH . 'resources/assets/scss/');

        // scss 服务启动
        $server = new Server(APPPATH . 'resources/assets', config('cache_path'), $scss);
        $server->serve();
    }

    /**
     * 上传资源加载
     *
     * @param string $name
     * @return \CI_Output
     */
    public function upload($name)
    {
        // 资源路径
        $name = dirname(config('upload.upload_path')) . '/' . $name;

        // 缩略图
        if (noFile($name) && str_contains($name, '/thumb/')) {

            // 匹配信息
            $path = substr($name, strlen(config('upload.upload_path')));
            preg_match('/(.*\/)thumb\/([^_]+)_(\d+)x(\d+)(.+)/', $path, $matches);
            list(, $path, $file, $width, $height, $ext) = $matches;

            // 原图信息
            $source = config('upload.upload_path') . $path . $file . $ext;
            list($sourceWidth, $sourceHeight) = getimagesize($source);
            $useWidth = $sourceHeight * $width > $sourceWidth * $height;

            // 加载编辑库
            $image = load_class('Image_lib');

            // 配置信息
            $image->initialize([
                'create_thumb' => true,
                'height' => $height,
                'maintain_ratio' => true,
                'master_dim' => $useWidth ? 'width' : 'height',
                'new_image' => config('upload.upload_path') . $path . 'thumb',
                'quality' => '100%',
                'source_image' => $source,
                'thumb_marker' => '_' . $width . 'x' . $height,
                'width' => $width
            ]);

            // 生成缩略图
            $image->resize();

            // 填充裁剪
            if ($sourceHeight * $width != $sourceWidth * $height) {
                list($sourceWidth, $sourceHeight) = getimagesize($name);
                $image->clear();
                $image->initialize([
                    'height' => $height,
                    'maintain_ratio' => false,
                    'quality' => '100%',
                    'source_image' => $name,
                    'width' => $width,
                    'x_axis' => ($sourceWidth - $width) / 2,
                    'y_axis' => ($sourceHeight - $height) / 2,
                ]);
                $image->crop();
            }
        }

        // 加载资源
        $this->raw($name, true);
    }

}
