<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2007 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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

// {{{ requires
require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");
require_once(realpath(dirname( __FILE__)) . "/../include.php");

/**
 * Googleショッピングモジュールのページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Mdl_Gshopping_Config.php 679 2011-04-08 11:22:23Z nanasess $
 */
class LC_Page_Mdl_Gshopping_Config extends LC_Page_Admin_Ex {

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage  = MODULE_REALDIR . MDL_GSHOPPING_CODE . "/templates/config.tpl";
        $this->tpl_subtitle  = "Googleショッピング対応モジュール";
        $this->arrMdlWorkFlg = array(1 => "稼働",
                                     0 => "停止");
        $this->arrTrFlg = array(1 => "適用",
                                0 => "未適用");
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objFormParam = new SC_FormParam_Ex();
        $this->initParam($objFormParam);
        $objFormParam->setParam($this->getConfig());
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        switch($this->getMode()) {
            case 'edit':
                // エラーチェック
                $this->arrErr = $objFormParam->checkError();
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $this->setConfig($objFormParam);
                   if (copy( MODULE_REALDIR . MDL_GSHOPPING_CODE . "/gsfeed_default.php",  GSHOPPING_FEED_DIR)) {
                       $this->tpl_onload = "alert('登録完了しました。Google Merchant Centerから ". GSHOPPING_FEED_URL ." を登録してください。'); window.close();";
                   } else {
                       $this->tpl_onload = "alert('モジュール書き込みが失敗しました。 " . GSHOPPING_FEED_DIR ." への書き込み権限を確認してください。');";
                   }
                }
            break;
        }
        $this->arrForm = $objFormParam->getFormParamList();
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * フォームパラメータの初期化.
     *
     * @return void
     */
    function initParam(&$objFormParam) {
        $objFormParam->addParam("動作フラグ", "work_flg", INT_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("トラッキングパラメータ", "tr_flg", INT_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("接頭辞", "gs_prefix", STEXT_LEN, "a", array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
    }

    /**
     * 設定を保存
     */
    function setConfig(&$objFormParam) {
        $arrConfig = $objFormParam->getHashArray();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->update("dtb_module", array('sub_data' => serialize($arrConfig))
                          , "module_code = ?", array(MDL_GSHOPPING_CODE));
    }

    /**
     * 設定を取得
     */
    function getConfig() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $config = $objQuery->get("sub_data", "dtb_module", "module_code = ?", array(MDL_GSHOPPING_CODE));
        return unserialize($config);
    }
}