<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
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
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * お問い合わせ のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Contact.php 21411 2012-01-17 07:34:02Z Seasoft $
 */
class LC_Page_Setsubi extends LC_Page_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            switch ($_GET['maker_name']) {case 'daikin':$this->tpl_title = 'ダイキン　設備用エアコン';
  	    break;case 'toshiba':$this->tpl_title = '東芝　設備用エアコン';
  	    break;case 'mitsubishidenki':$this->tpl_title = '三菱電機　設備用エアコン';
  	    break;case 'hitachi':$this->tpl_title = '日立　設備用エアコン';
  	    break;case 'mitsubishijyuko':$this->tpl_title = '三菱重工　設備用エアコン';
  	    break;default:$this->tpl_title = '設備用エアコン';
};
        } else {
            switch ($_GET['maker_name']) {case 'daikin':$this->tpl_title = 'ダイキン　設備用エアコン';
  	    break;case 'toshiba':$this->tpl_title = '東芝　設備用エアコン';
  	    break;case 'mitsubishidenki':$this->tpl_title = '三菱電機　設備用エアコン';
  	    break;case 'hitachi':$this->tpl_title = '日立　設備用エアコン';
  	    break;case 'mitsubishijyuko':$this->tpl_title = '三菱重工　設備用エアコン';
  	    break;default:$this->tpl_title = '設備用エアコン';
};
        }
        $this->tpl_page_category = 'setsubi';
        $this->httpCacheControl('nocache');

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPref = $masterData->getMasterData('mtb_pref');

        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            $this->CONF = SC_Helper_DB_Ex::sfGetBasisData();
        }
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objDb = new SC_Helper_DB_Ex();
        $objFormParam = new SC_FormParam_Ex();

        $this->arrData = isset($_SESSION['setsubi']) ? $_SESSION['setsubi'] : "";

        switch ($this->getMode()) {
        case 'confirm':
            // エラーチェック
            $this->lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);
            $objFormParam->convParam();
            $objFormParam->toLower('email');
            $objFormParam->toLower('email02');
            $this->arrErr = $this->lfCheckError($objFormParam);
            // 入力値の取得
            $this->arrForm = $objFormParam->getFormParamList();
            // ▼データベースに登録する
            $this->lfRegisterContactData();

            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                // エラー無しで完了画面
                $this->tpl_mainpage = 'setsubi/confirm.tpl';
                $this->tpl_title = 'お問い合わせ(確認ページ)';
            }

            break;

        case 'return':
            $this->lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);
            $this->arrForm = $objFormParam->getFormParamList();

            break;

        case 'complete':
            $this->lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);
            $this->arrErr = $objFormParam->checkError();
            $this->arrForm = $objFormParam->getFormParamList();
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                $this->lfSendMail($this);
                // 完了ページへ移動する
                SC_Response_Ex::sendRedirect('complete.php');
                exit;
            } else {
                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                exit;
            }
            break;

        default:
            break;
        }
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    // }}}
    // {{{ protected functions

    /**
     * お問い合わせ入力時のパラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {

        $objFormParam->addParam("会社名", 'name01', STEXT_LEN, 'KVa', array("SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お名前", 'name02', STEXT_LEN, 'KVa', array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objFormParam->addParam("会社名(フリガナ)", 'kana01', STEXT_LEN, 'KVCa', array("SPTAB_CHECK","MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objFormParam->addParam("お名前(フリガナ)", 'kana02', STEXT_LEN, 'KVCa', array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objFormParam->addParam("郵便番号1", "zip01", ZIP01_LEN, 'n',array("SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objFormParam->addParam("郵便番号2", "zip02", ZIP02_LEN, 'n',array("SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objFormParam->addParam("都道府県", 'pref', INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("住所1", "addr01", MTEXT_LEN, 'KVa', array("SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objFormParam->addParam("住所2", "addr02", MTEXT_LEN, 'KVa', array("SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お問い合わせ内容", 'contents', MLTEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam('メールアドレス', 'email', null, 'KVa',array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK"));
        $objFormParam->addParam('メールアドレス(確認)', "email02", null, 'KVa',array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK"));
        $objFormParam->addParam("お電話番号1", 'tel01', TEL_ITEM_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お電話番号2", 'tel02', TEL_ITEM_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お電話番号3", 'tel03', TEL_ITEM_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return array 入力チェック結果の配列
     */
    function lfCheckError(&$objFormParam) {
        // 入力データを渡す。
        $arrForm =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrForm);
        $objErr->arrErr = $objFormParam->checkError();
        $objErr->doFunc(array('メールアドレス', 'メールアドレス(確認)', 'email', "email02") ,array("EQUAL_CHECK"));
        return $objErr->arrErr;
    }

    /**
     * メールの送信を行う。
     *
     * @return void
     */
    function lfSendMail(&$objPage){
        $CONF = SC_Helper_DB_Ex::sfGetBasisData();
        $objPage->tpl_shopname = $CONF['shop_name'];
        $objPage->tpl_infoemail = $CONF['email02'];
        $fromMail_name = $objPage->arrForm['name01']['value'] . $objPage->arrForm['name02']['value'] ." 様";
        $fromMail_address = $objPage->arrForm['email']['value'];
        $helperMail = new SC_Helper_Mail_Ex();
        $helperMail->sfSendTemplateMail(
            $objPage->arrForm['email']['value'],            // to
            $objPage->arrForm['name01']['value'] . $objPage->arrForm['name02']['value'] .' 様',    // to_name
            5,                                              // template_id
            $objPage,                                       // objPage
            $CONF['email02'],                               // from_address
            $CONF['shop_name'],                             // from_name
            $CONF['email02'],                               // reply_to
            $CONF['email02']                                // bcc
        );
    }


function lfRegisterContactData(){
$sqlval = array();//データベース登録用の配列を用意
$objQuery = new SC_Query();//データベース操作クラスをインスタンス化
//DB登録用の配列に値を代入
$sqlval['name01']      = $this->arrForm['name01']['value'];      //お名前（姓）
$sqlval['name02']      = $this->arrForm['name02']['value'];      //お名前（姓
$sqlval['email']       = $this->arrForm['email']['value'];       //メールアドレス
$sqlval['zip01']       = $this->arrForm['zip01']['value'];       //郵便番号上1
$sqlval['zip02']       = $this->arrForm['zip02']['value'];       //郵便番号下2
$sqlval['pref']        = $this->arrForm['pref']['value'];        //都道府県番号
$sqlval['addr01']      = $this->arrForm['addr01']['value'];      //住所1
$sqlval['addr02']      = $this->arrForm['addr02']['value'];      //住所2
$sqlval['tel01']       = $this->arrForm['tel01']['value'];       //お電話番号1
$sqlval['tel02']       = $this->arrForm['tel02']['value'];       //お電話番号2
$sqlval['tel03']       = $this->arrForm['tel03']['value'];       //お電話番号3
$sqlval['contents']    = $this->arrForm['contents']['value'];    //問い合わせ内容
$sqlval['create_date'] = 'Now()'; //送信日時
if(isset($this->arrData['customer_id']) && !empty($this->arrData['customer_id'])){
// 会員番号が存在するのであれば、会員番号も登録
$sqlval['customer_id'] = $this->arrData['customer_id'];     //会員番号
}
$objQuery->insert("dtb_estimate",$sqlval);//問い合わせ内容を登録
}

}
?>
