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
class LC_Page_Contact extends LC_Page_Ex {

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
            $this->tpl_title = 'お問い合わせ';
        } else {
            $this->tpl_title = 'お問い合わせ(入力ページ)';
        }
        $this->tpl_page_category = 'contact';
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
				
				switch ($this->getMode()) {
        case 'return2':
					SC_Response_Ex::sendRedirect("https://www.tokyo-aircon.net/contact/?g=1");
        break;
				
				default:
        break;
				}
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objDb = new SC_Helper_DB_Ex();
        $objFormParam = new SC_FormParam_Ex();

        $this->arrData = isset($_SESSION['customer']) ? $_SESSION['customer'] : "";

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

            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                // エラー無しで完了画面
                $this->tpl_mainpage = 'contact/confirm.tpl';
                $this->tpl_title = 'お問い合わせ(確認ページ)';
            }
						session_start();
						unset($_SESSION['contact']);
						
						if( $_POST['name02'] && $_POST['kana02'] && $_POST['email'] && $_POST['email02'] && $_GET['g'] ) {
							session_start();
							$_SESSION['contact']['name01'] = $_POST['name01'];
							$_SESSION['contact']['kana01'] = $_POST['kana01'];
							$_SESSION['contact']['name02'] = $_POST['name02'];
							$_SESSION['contact']['kana02'] = $_POST['kana02'];
							$_SESSION['contact']['zip01'] = $_POST['zip01'];
							$_SESSION['contact']['zip02'] = $_POST['zip02'];
							$_SESSION['contact']['pref'] = $_POST['pref'];
							$_SESSION['contact']['addr01'] = $_POST['addr01'];
							$_SESSION['contact']['addr02'] = $_POST['addr02'];
							$_SESSION['contact']['tel01'] = $_POST['tel01'];
							$_SESSION['contact']['tel02'] = $_POST['tel02'];
							$_SESSION['contact']['tel03'] = $_POST['tel03'];
							$_SESSION['contact']['email'] = $_POST['email'];
							$_SESSION['contact']['email02'] = $_POST['email02'];
							$_SESSION['contact']['contents'] = $_POST['contents'];
						}

            break;

        case 'return':
				case 'return2':
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
		            // ▼データベースに登録する
		            $this->lfRegisterContactData();

								$gencho_hyouji = $_POST['gencho_flg'];
								if( $gencho_hyouji == 1 ) {
									// dtb_genchoとdtb_sessionのデータを空にする
									$sessid = isset($_COOKIE["PHPSESSID"]) ? $_COOKIE["PHPSESSID"] : NULL;
									// PgSQLへ接続する
									$link = pg_connect("host=localhost port=5432 dbname=fs_eccube user=tokyo_aircon password=7dgaCBAhptyrZaDT") or die("PgSQLへの接続に失敗しました。");
									//dtb_genchoのデータを空にする
									$sql = "DELETE FROM dtb_gencho WHERE sess_id ='".$sessid."'";
									$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
									// セッションカート内の商品を削除する。
									$objCartSess = new SC_CartSession_Ex();
									$objSiteSess = new SC_SiteSession_Ex();
									$objCustomer = new SC_Customer_Ex();
									$this->cartKeys = $objCartSess->getKeys();
									foreach ($this->cartKeys as $key) {
										$objCartSess->delAllProducts($key);
									}
								}
								// PgSQLから切断
								pg_close($link);
								
								//session_start();
								//unset($_SESSION['contact']);
								
                // 完了ページへ移動する
                SC_Response_Ex::sendRedirect('complete.php');
                exit;
            } else {
                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                exit;
            }
            break;

        case 'complete2':
            // エラーチェック
            $this->lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);
            $objFormParam->convParam();
            $objFormParam->toLower('email');
            $objFormParam->toLower('email02');
            //$this->arrErr = $this->lfCheckError($objFormParam);
            // 入力値の取得
            $this->arrForm = $objFormParam->getFormParamList();
            // ▼データベースに登録する
            $this->lfRegisterContactData();

            //if (SC_Utils_Ex::isBlank($this->arrErr)) {
                 $this->lfSendMail($this);
								 
								 $gencho_hyouji = $_POST['gencho_flg'];
								if( $gencho_hyouji == 1 ) {
									// dtb_genchoとdtb_sessionのデータを空にする
									$sessid = isset($_COOKIE["PHPSESSID"]) ? $_COOKIE["PHPSESSID"] : NULL;
									// PgSQLへ接続する
									$link = pg_connect("host=localhost port=5432 dbname=fs_eccube user=tokyo_aircon password=7dgaCBAhptyrZaDT") or die("PgSQLへの接続に失敗しました。");
									//dtb_genchoのデータを空にする
									$sql = "DELETE FROM dtb_gencho WHERE sess_id ='".$sessid."'";
									$result = pg_query($link, $sql) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
									// セッションカート内の商品を削除する。
									$objCartSess = new SC_CartSession_Ex();
									$objSiteSess = new SC_SiteSession_Ex();
									$objCustomer = new SC_Customer_Ex();
									$this->cartKeys = $objCartSess->getKeys();
									foreach ($this->cartKeys as $key) {
										$objCartSess->delAllProducts($key);
									}
							//	}
								// PgSQLから切断
								pg_close($link);
								
								session_start();
								unset($_SESSION['contact']);
								
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
				if($_GET['g']) {
        	$objFormParam->addParam("お問い合わせ内容", 'contents', MLTEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
				}else{
					$objFormParam->addParam("お問い合わせ内容", 'contents', MLTEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
				}
        $objFormParam->addParam('メールアドレス', 'email', null, 'KVa',array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK"));
        $objFormParam->addParam('メールアドレス(確認)', "email02", null, 'KVa',array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK"));
        $objFormParam->addParam("お電話番号1", 'tel01', TEL_ITEM_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お電話番号2", 'tel02', TEL_ITEM_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お電話番号3", 'tel03', TEL_ITEM_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
				$objFormParam->addParam("現場調査商品内容", 'contents2', MLTEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
				$objFormParam->addParam("現場調査フラグ", 'gencho_flg', INT_LEN, 'n', array("MAX_LENGTH_CHECK"));
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
        $helperMail = new SC_Helper_Mail_Ex();
        $helperMail->sfSendTemplateMail(
            $objPage->arrForm['email']['value'],            // to
            $objPage->arrForm['name01']['value'] .' 様',    // to_name
            5,                                              // template_id
            $objPage,                                       // objPage
            $CONF['email03'],                               // from_address
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
if($_GET['g']) {
$sqlval['contents']    = "【現調】".$this->arrForm['contents']['value'];    //問い合わせ内容
}else{
$sqlval['contents']    = $this->arrForm['contents']['value'];
}
$sqlval['create_date'] = 'Now()'; //送信日時
$sqlval['contents2']   = $this->arrForm['contents2']['value'];  //現場調査依頼内容
$sqlval['gencho_flg']  = $this->arrForm['gencho_flg']['value']; //現場調査依頼フラグ
if(isset($this->arrData['customer_id']) && !empty($this->arrData['customer_id'])){
// 会員番号が存在するのであれば、会員番号も登録
$sqlval['customer_id'] = $this->arrData['customer_id'];     //会員番号
}
$objQuery->insert("dtb_contact",$sqlval);//問い合わせ内容を登録
}


}
?>
