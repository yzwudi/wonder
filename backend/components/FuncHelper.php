<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/12/1
 * Time: 20:47
 */
namespace backend\components;


use Yii;

class FuncHelper
{
    //将HTML table 转化为数组
    public static function getArrayFromHtmlTable($table){
        $table = preg_replace("'<table[^>]*?>'si","",$table);
        $table = preg_replace("'<tr[^>]*?>'si","",$table);
        $table = preg_replace("'<td[^>]*?>'si","",$table);
        $table = str_replace("</tr>","{tr}",$table);
        $table = str_replace("</td>","{td}",$table);
        //去掉 HTML 标记
        $table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table);
        //去掉空白字符
        $table = preg_replace("'([rn])[s]+'","",$table);
        $table = str_replace(" ","",$table);
        $table = str_replace(" ","",$table);
        $table = explode('{tr}', $table);
        array_pop($table);
        foreach ($table as $key=>$tr) {
            $td = explode('{td}', $tr);
            array_pop($td);
            $td_array[] = $td;
        }
        return $td_array;
    }

    //根据基金号获取基金名称
    public static function getFundNameById($id){
        $url = 'http://fundsuggest.eastmoney.com/FundSearch/api/FundSearchAPI.ashx?m=1&key='.$id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output,true)['Datas'];
        if(empty($output)){
            return '';
        }
        foreach($output as $val){
            if($val['_id'] == $id && $val['CATEGORYDESC'] == '基金'){
                return $val['NAME'];
            }
        }
    }

    //获取毫秒数
    public static function getMicroTime($len = 16) {
        list($usec, $sec) = explode(" ", microtime());
        return substr($sec . ((float)$usec * 10e8), 0, $len);
    }

    public static function getFundValueAndName($id){
        $microtime = FuncHelper::getMicroTime(13);
        $url = 'http://hq.sinajs.cn/?_='.$microtime.'/&list=f_'.$id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        if(preg_match('/\"(.+)\"/', iconv('GB2312', 'UTF-8', $output), $info)){
            $info = explode(',', $info[1]);
            return $info[1];
        }
        return '';
    }
}