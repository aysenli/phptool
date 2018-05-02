<?php
/**
 * Created by PhpStorm.
 * User: Aysen
 * Mail: 122195562@qq.com
 * Date: 2018/4/12
 * Time: 17:41
 */

/*关于filesize()返回错误结果，PHP官方有如下说明。

Because PHP's integer type is signed and many platforms use 32bit integers, some filesystem functions may return unexpected results for files which are larger than 2GB.

The size of an integer is platform-dependent, although a maximum value of about two billion is the usual value (that's 32 bits signed). 64-bit platforms usually have a maximum value of about 9E18, except on Windows prior to PHP 7, where it was always 32 bit. PHP does not support unsigned integers. Integer size can be determined using the constant PHP_INT_SIZE, maximum value using the constant PHP_INT_MAX since PHP 4.4.0 and PHP 5.0.5, and minimum value using the constant PHP_INT_MIN since PHP 7.0.0.

The x64 builds of PHP 5 for Windows are experimental, and do not provide 64-bit integer or large file support.
PHP 7 provides full 64-bit support. The x64 builds of PHP 7 support native 64-bit integers, LFS, memory_limit and much more.

因为 PHP 的整数类型是有符号整型而且很多平台使用 32 位整型，对 2GB 以上的文件，一些文件系统函数可能返回无法预期的结果 。

整型数的字长和平台有关，尽管通常最大值是大约二十亿（32 位有符号）。64 位平台下的最大值通常是大约 9E18。PHP 不支持无符号整数。Integer 值的字长可以用常量 PHP_INT_SIZE来表示，自 PHP 4.4.0 和 PHP 5.0.5后，最大值可以用常量 PHP_INT_MAX 来表示。

综上所述，32位PHP无法支持支持2GB以上的文件，获取2GB以上文件的大小会返回无法预知的结果，虽然PHP 5.5 PHP 5.6提供64位程序，但是只是实验性64位支持，不支持64位整型数字，也不支持2GB以上大文件。PHP 7+开始完美支持64位整型数字和大文件。建议使用64位的PHP 7.0.0以上版本，如果使用早期版本，就要使用其他方法变通解决，比如调用系统命令。除了64位支持以外，PHP 7.1开始文件系统还支持utf-8文件名和IO流，使用PHP 7.1+就再也不需要使用iconv()在gbk/utf-8等字符集之间转来转去了，文件操作更方便，代码更顺滑了*/

/**
 * 文件下载封装
 */
class FileDownLoad
{   
    /**
     * 小文件下载
     * User: Aysen
     * Mail: 122195562@qq.com
     * Date: 2018/4/17
     * Time: 10:21
     * @param $down_path 文件绝对路径
     * @param $down_name 文件名,包含后缀
     */
    public function smallFileDownLoad($down_path, $down_name) {
        // 检测文件是否存在、是否可读
        if (file_exists($down_path) && is_readable($down_path)) {
            // 避免下载超时
            set_time_limit(0);
            // 避免中文乱码
            // iconv编码转换存在bug, $down_name = iconv('utf-8', 'gb2312', $down_name);  
            $down_name = mb_convert_encoding($down_name, 'gb2312', 'utf-8');
            // 打开
            $fp = fopen($down_path, 'rb');
            // 文件大小
            $filesize = filesize($down_path);
            // 返回的文件(流形式)
            // 按照字节大小返回
            header("Content-type: application/octet-stream");
            // 返回文件大小
            header("Content-Ranges: bytes");
            header("Content-Length: $filesize");
            // 这里客户端的弹出对话框，对应的文件名
            header("Content-Disposition: attachment; filename=" . $down_name);
            // 这里很重要
            @ob_clean();
            @ob_flush();
            @flush();
            // 读取
            echo fread($fp, $filesize);
            // 关闭
            fclose($fp);
        }
    }

    /**
     * 大文件下载
     * User: Aysen
     * Mail: 122195562@qq.com
     * Date: 2018/4/17
     * Time: 10:22
     * @param $down_path 文件绝对路径
     * @param $down_name 文件名,包含后缀
     */
    public function bigFileDownLoad($down_path, $down_name) {
        // 检测文件是否存在、是否可读
        if (file_exists($down_path) && is_readable($down_path)) {
            // 避免下载超时
            set_time_limit(0);
            // 避免中文乱码
            // iconv编码转换存在bug, $down_name = iconv('utf-8', 'gb2312', $down_name); 
            $down_name = mb_convert_encoding($down_name, 'gb2312', 'utf-8');
            // 打开
            $fp = fopen($down_path, 'rb');
            // 文件大小
            /*$filesize = filesize($down_path);*/
            // 返回的文件(流形式)
            // 按照字节大小返回
            header("Content-type: application/octet-stream");
            // 返回文件大小
            header("Content-Ranges: bytes");
            /*header("Content-Length: $filesize");*/
            // 这里客户端的弹出对话框，对应的文件名
            header("Content-Disposition: attachment; filename=" . $down_name);
            // 读取长度
            $buffer = 4096;
            // 循环读取
            while (!feof($fp)) {
                echo fread($fp, $buffer);
                @ob_flush();
                @flush();
            }
            // 关闭
            fclose($fp);
        }
    }


}


?>
