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
require_once(MDL_PG_MULPAY_CLASS_REALDIR . "utils/LC_Mdl_PG_MULPAY_Utils.php");
/**
 * パラメータ管理クラス
 *
 */
class LC_Mdl_PG_MULPAY_FormParam extends SC_FormParam{

    /**
     * エラーチェックを行う。
     *
     * @param boolean $br
     * @param string $keyname
     * @return array エラー情報
     */
    function checkError($br = true, $keyname = "") {
        // 連想配列の取得
        $arrRet = $this->getHashArray($keyname);
        $objErr = new LC_Mdl_PG_MULPAY_CheckError($arrRet);
        $cnt = 0;
        foreach($this->keyname as $val) {
            foreach($this->arrCheck[$cnt] as $func) {
                if (!isset($this->param[$cnt])) $this->param[$cnt] = "";
                switch($func) {
                case 'EXIST_CHECK':
                case 'NUM_CHECK':
                case 'EMAIL_CHECK':
                case 'EMAIL_CHAR_CHECK':
                case 'ALNUM_CHECK':
                case 'GRAPH_CHECK':
                case 'KANA_CHECK':
                case 'URL_CHECK':
                case 'SPTAB_CHECK':
                case 'ZERO_CHECK':
                case 'ALPHA_CHECK':
                case 'ZERO_START':
                case 'FIND_FILE':
                case 'NO_SPTAB':
                case 'DIR_CHECK':
                case 'DOMAIN_CHECK':
                case 'FILE_NAME_CHECK':
                case 'MOBILE_EMAIL_CHECK':
                case 'HKIGO_CHECK':
                case 'PROHIBITED_CHAR_CHECK':
                case 'PROHIBITED_KIGO_CHECK':
                    if(!is_array($this->param[$cnt])) {
                        $objErr->doFunc(array($this->disp_name[$cnt], $val), array($func));
                    } else {
                        $max = count($this->param[$cnt]);
                        for($i = 0; $i < $max; $i++) {
                            $objSubErr = new SC_CheckError($this->param[$cnt]);
                            $objSubErr->doFunc(array($this->disp_name[$cnt], $i), array($func));
                            if(count($objSubErr->arrErr) > 0) {
                                foreach($objSubErr->arrErr as $mess) {
                                    if($mess != "") {
                                        $objErr->arrErr[$val] = $mess;
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'MAX_CHECK':
                case 'MIN_CHECK':
                case 'MAX_LENGTH_CHECK':
                case 'MIN_LENGTH_CHECK':
                case 'MAX_BYTE_LENGTH_CHECK':
                case 'NUM_COUNT_CHECK':
                case 'KIGO_CHECK':
                case 'LENGTH_CHECK':
                case 'PAYMENT_TERM_CHECK':
                    if(!is_array($this->param[$cnt])) {
                        $objErr->doFunc(array($this->disp_name[$cnt], $val, $this->length[$cnt]), array($func));
                    } else {
                        $max = count($this->param[$cnt]);
                        for($i = 0; $i < $max; $i++) {
                            $objSubErr = new SC_CheckError($this->param[$cnt]);
                            $objSubErr->doFunc(array($this->disp_name[$cnt], $i, $this->length[$cnt]), array($func));
                            if(count($objSubErr->arrErr) > 0) {
                                foreach($objSubErr->arrErr as $mess) {
                                    if($mess != "") {
                                        $objErr->arrErr[$val] = $mess;
                                    }
                                }
                            }
                        }
                    }
                    break;
                // 小文字に変換
                case 'CHANGE_LOWER':
                    $this->param[$cnt] = strtolower($this->param[$cnt]);
                    break;
                // ファイルの存在チェック
                case 'FILE_EXISTS':
                    if($this->param[$cnt] != "" && !file_exists($this->check_dir . $this->param[$cnt])) {
                        $objErr->arrErr[$val] = "※ " . $this->disp_name[$cnt] . "のファイルが存在しません。<br>";
                    }
                    break;
                default:
                    $objErr->arrErr[$val] = "※※　エラーチェック形式($func)には対応していません　※※ <br>";
                    break;
                }
            }

            if (isset($objErr->arrErr[$val]) && !$br) {
                $objErr->arrErr[$val] = ereg_replace("<br>$", "", $objErr->arrErr[$val]);
            }
            $cnt++;
        }
        return $objErr->arrErr;
    }

}
?>
