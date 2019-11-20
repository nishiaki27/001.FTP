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

/**
 * エラーチェッククラス
 *
 * @package Page
 */
class LC_Mdl_PG_MULPAY_CheckError extends SC_CheckError {
    /**
     * 文字数の判定を行う。
     * @param array $value value[0] = 項目名 value[1] = 判定対象文字列  value[2] = 文字数(半角も全角も1文字として数える)
     */
    function LENGTH_CHECK( $value ) {
        if(isset($this->arrErr[$value[1]])) {
            return;
        }
        $this->createParam($value);
        // 文字数の取得
        if( mb_strlen($this->arrParam[$value[1]]) != $value[2] ) {
            $this->arrErr[$value[1]] = "※ " . $value[0] . "は" . $value[2] . "字で入力してください。<br />";
        }
    }

    /**
     * 禁止文字列が含まれるか判定を行う。
     * @param array $value value[0] = 項目名 value[1] = 判定対象文字列
     */
    function PROHIBITED_KIGO_CHECK($value) {
        if(isset($this->arrErr[$value[1]])) {
            return;
        }
        $this->createParam($value);
        $this->arrProhiditedKigo = $GLOBALS['arrProhiditedKigo'];
        foreach ($this->arrProhiditedKigo as $val) {
            if(strstr($this->arrParam[$value[1]], $val)) {
                $this->arrErr[$value[1]] = "※ " . $value[0] . "に" . " ^ ` { | } ~ & < > \" ' は使用できません。<br />";
                break;
            }
        }
    }

    /**
     * 支払期限の判定を行う。
     * @param array $value value[0] = 項目名, value[1] = 支払期限（日）, value[2] = 支払期限（秒）
     */
    function PAYMENT_TERM_CHECK($value) {
        if(isset($this->arrErr[$value[1]])) {
            return;
        }
        $this->createParam($value);
        if (strlen($this->arrParam[$value[1]]) != 0 || strlen($value[2]) != 0) {
            if (strlen($this->arrParam[$value[1]]) == 0 || $this->arrParam[$value[1]] <= 0) {
                if($value[2] < PAYMENT_TERM_MIN) {
                    $this->arrErr[$value[1]] = "※ " . $value[0] . "は" . PAYMENT_TERM_MIN . "秒以上で入力してください。<br />";
                }
            }

            if ($this->arrParam[$value[1]] >= PAYMENT_TERM_MAX) {
                if($value[2] > 0 ) {
                    $this->arrErr[$value[1]] = "※ " . $value[0] . "は" . PAYMENT_TERM_MAX . "日以下で入力してください。<br />";
                }
            }
        }

    }

    /**
     * 半角記号の判定を行う。
     * @param array $value value[0] = 項目名, value[1] = 判定対象文字列
     */
    function HKIGO_CHECK( $value ) {				// 入力文字が英数記号以外ならエラーを返す
        if(isset($this->arrErr[$value[1]])) {
            return;
        }
        $this->createParam($value);
        if( strlen($this->arrParam[$value[1]]) > 0 && EregI("[!-/]|[:-@]|[[-`]|[{-~]", $this->arrParam[$value[1]] ) ) {
            $this->arrErr[$value[1]] = "※ " . $value[0] . "は半角記号は使用できません。<br />";
        }
    }

    /**
     * 最大バイト数制限の判定
     * @param array $value value[0] = 項目名, value[1] = 判定対象文字列, value[2] = 最大バイト数
     */
    function MAX_BYTE_LENGTH_CHECK( $value ) {		// 入力が指定文字数以上ならエラーを返す
        if(isset($this->arrErr[$value[1]])) {
            return;
        }
        $this->createParam($value);
        // バイト数の取得
        $chk_value = mb_convert_encoding($this->arrParam[$value[1]], "eucJP-win", "UTF-8");
        if(strlen($chk_value) > $value[2] ) {
            print_r("$chk_value");
            print_r(":");
            print_r(strlen($chk_value));
            $this->arrErr[$value[1]] = "※ " . $value[0] . "には" . $value[2] . "Byte以内の文字列を設定してください。<br />";
        }
    }

    /**
     * 禁止文字の判定
     * @param array $value value[0] = 項目名, value[1] = 判定対象文字列
     */
    function PROHIBITED_CHAR_CHECK($value) {
        if(isset($this->arrErr[$value[1]])) {
            return;
        }
        $this->createParam($value);
        $chk_val = $this->arrParam[$value[1]];
        for ($i = 0; $i < mb_strlen($chk_val); $i++) {
            $tmp = mb_substr($chk_val, $i , 1);
            if (LC_Mdl_PG_MULPAY_Utils::isProhibitedChar($tmp)) {
                $this->arrErr[$value[1]] = "※ " . $value[0] . "は特殊記号、拡張文字、外字は使用できません。<br />";
            }
        }
    }

}
?>
