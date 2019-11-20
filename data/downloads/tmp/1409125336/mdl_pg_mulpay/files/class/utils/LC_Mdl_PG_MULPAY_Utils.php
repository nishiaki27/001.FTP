<?php
/*
 * This file is part of EC-CUBE PAYMENT MODULE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.net/product/payment/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
class LC_Mdl_PG_MULPAY_Utils {
    /**
     * レスポンスを解析する
     *
     * @param string $string レスポンス
     * @return array 解析結果
     */
    function parse($string) {
        $string = trim($string);

        $arrTmpAnd = explode('&', $string);
        $arrRet = array();

        foreach($arrTmpAnd as $eqString) {
            // $eqString -> CardSeq=2|0|1, DefaultFlag=0|0|0...
            list($key, $val) = explode('=', $eqString);

            // $val -> 2|0|1, 0|0|0, ...
            if (preg_match('/|/', $val)) {
                $arrTmpl = explode('|', $val);
                $max = count($arrTmpl);
                for($i = 0; $i < $max; $i++) {
                    $arrRet[$i][$key] = trim($arrTmpl[$i]);
                }
            // $val -> 2, 0, 1...
            } else {
                $arrRet[0][$key] = trim($val);
            }
        }
        return $arrRet;
    }

    /**
     * 有効なカード情報の数を返す.
     * 
     * @param array $arrCardInfo カード情報
     * @return integer
     */
    function countCard($arrCardInfo) {
        $num = count($arrCardInfo);
        foreach($arrCardInfo as $card) {
            // 削除済みカード
            if ($card['DeleteFlag'] == '1') {
                $num--;
            }
            // CardSeq=&DefaultFlag=&CardName=...
            // こういうデータの時
            if ($card['CardNo'] === '') {
                $num--;
            }
        }
        return $num;
    }
    
    /**
     * 指定日時を指定フォーマットへ変換する。
     * 
     * @param string $date 変換対象日時(yyyyMMddHHmmss)
     * @param string $format 日付フォーマット
     */
    function convertDate($date, $format) {
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        $day = substr($date, 6, 2);
        $hour = substr($date, 8, 2);
        $minute = substr($date, 10, 2);
        $second = substr($date, 12, 2);
        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        return date($format, $timestamp);
    }

    /**
     * カートから一番最初の商品名を取得する。
     *
     * @param integer order_id 受注ID
     * @return string 商品名
     */
    function getFirstProductName($order_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrProduct = $objQuery->select('*', 'dtb_order_detail', 'order_id = ?', array($order_id));
        if (isset($arrProduct[0]['product_name'])) {
            return $arrProduct[0]['product_name'];
        } else {
            return '';
        }
        return $product_name;
    }
    
    /**
     * 禁止文字か判定を行う。
     *
     * @param string $value 判定対象
     * @return boolean 結果
     */
    function isProhibitedChar($value) {
        $check_char = mb_convert_encoding($value, "SJIS-win", "UTF-8");
        if (hexdec('8740') <= hexdec(bin2hex($check_char)) && hexdec('879E') >= hexdec(bin2hex($check_char))) {
            return true;
        }
        if ((hexdec('ED40') <= hexdec(bin2hex($check_char)) && hexdec('ED9E') >= hexdec(bin2hex($check_char))) ||
            (hexdec('ED9F') <= hexdec(bin2hex($check_char)) && hexdec('EDFC') >= hexdec(bin2hex($check_char))) ||
            (hexdec('EE40') <= hexdec(bin2hex($check_char)) && hexdec('EE9E') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FA40') <= hexdec(bin2hex($check_char)) && hexdec('FA9E') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FA9F') <= hexdec(bin2hex($check_char)) && hexdec('FAFC') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FB40') <= hexdec(bin2hex($check_char)) && hexdec('FB9E') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FB9F') <= hexdec(bin2hex($check_char)) && hexdec('FBFC') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FC40') <= hexdec(bin2hex($check_char)) && hexdec('FC4B') >= hexdec(bin2hex($check_char)))){
            return true;
        }
        if ((hexdec('EE9F') <= hexdec(bin2hex($check_char)) && hexdec('EEFC') >= hexdec(bin2hex($check_char))) ||
            (hexdec('F040') <= hexdec(bin2hex($check_char)) && hexdec('F9FC') >= hexdec(bin2hex($check_char)))) {
            return true;
        }
                
        return false;
    }

    /**
     * 禁止文字を全角スペースに置換する。
     *
     * @param string $value 対象文字列
     * @return string 結果
     */
    function convertProhibitedChar($value) {
        $ret = $value;
        for ($i = 0; $i < mb_strlen($value); $i++) {
            $tmp = mb_substr($value, $i , 1);
            if (LC_Mdl_PG_MULPAY_Utils::isProhibitedChar($tmp)) {
               $ret = str_replace($tmp, "　", $value);
            }
        }
        return $ret;
    }

    /**
     * 禁止半角記号を半角スペースに変換する。
     *
     * @param string $value
     * @return string 変換した値
     */
    function convertProhibitedKigo($value) {
        $this->arrProhiditedKigo = $GLOBALS['arrProhiditedKigo'];
            foreach ($this->arrProhiditedKigo as $prohidited_kigo) {
            if(strstr($value, $prohidited_kigo)) {
                $value = str_replace($prohidited_kigo, " ", $value);
            }
        }
        return $value;
    }

    /**
     * 文字列から指定バイト数を切り出す。
     *
     * @param string $value
     * @param integer $len
     * @return string 結果
     */
    function subString($value, $len) {
        $value = mb_convert_encoding($value, "SJIS", "UTF-8");
        for ($i = 1; $i <= mb_strlen($value); $i++) {
            $tmp = mb_substr($value, 0 , $i);
            if (strlen($tmp) <= $len) {
                $ret = mb_convert_encoding($tmp, "UTF-8", "SJIS");
            } else {
                break;
            }
        }
        return $ret;
    }

}
?>
