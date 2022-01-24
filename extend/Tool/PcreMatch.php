<?php


namespace Tool;


class PcreMatch
{
    /*private static $pcre_img = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';*/
    private static $pcre_img = '/<img.*?src="(.*?)".*?\/?>/i';
    private static $pcre_href = '/<a[^>]*>|<\/.*?a>/i';
    private static $pcre_base64 = '/data\:image\/[a-z]+;base64,/i';
    /*
     * 正则匹配text中图片
     *  入参：
     *      content:text内容
     *  返回值：
     *      错误：
     *          false;
     *      正确：
     *          dataArray:图片列表 array
     * */
    public static function pregImg($content)
    {
        if(empty($content))
        {
            return false;
        }
        preg_match_all(self::$pcre_img, $content,$match);
        if(empty($match[1]))
        {
            return "";
        }
        return $match[1];
    }
    /*
     * 匹配去除a href标签。只留里面的img标签
     * */
    public static function pregHref($content)
    {
        $content = htmlspecialchars_decode($content);
        $content = preg_replace(self::$pcre_href, "", $content);
        return $content;
    }
    /*
     * 匹配base64图片，返回;base64,后面部分
     * */
    public static function pregBase64($content)
    {
        if(empty($content))
        {
            return false;
        }

        $content = htmlspecialchars_decode($content);
        $content = preg_replace(self::$pcre_base64, '',$content);
        if(empty($content))
        {
            return "";
        }
        return $content;
    }
}