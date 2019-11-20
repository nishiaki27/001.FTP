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
require_once(MDL_PG_MULPAY_CLASS_REALDIR . "pages/LC_Page_Mdl_PG_MULPAY.php");
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'LC_Mdl_PG_MULPAY_Client.php');
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'utils/LC_Mdl_PG_MULPAY_Utils.php');

/**
 * モバイルSuica決済情報入力画面 のページクラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_Suica extends LC_Page_Mdl_PG_MULPAY {
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        $this->tpl_file = 'suica.tpl';
        parent::init();
        $this->tpl_column_num = 1;
    }

    /**
     * テンプレート変数をassignする
     *
     */
    function setTemplateVars() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $this->tpl_payment_method = 'モバイルSuica';
        $this->arrMobileMailDomain = $GLOBALS['arrMobileMailDomain'];
    }

    /**
     * フォームパラメータの初期化
     *
     * @return SC_FormParam
     */
    function initParam() {
        $objForm = new SC_FormParam();
        $objForm->addParam('携帯メールアドレス', 'email', EMAIL_LEN, 'n', array('NO_SPTAB', 'EXIST_CHECK'));
        $objForm->addParam("携帯メールアドレスドメイン", "email_domain", STEXT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $_POST['email_all'] = $_POST['email'] . '@' . $_POST['email_domain'];
        $objForm->addParam('携帯メールアドレス', 'email_all', EMAIL_ALL_LEN, 'n', array('MAX_LENGTH_CHECK', 'CHANGE_LOWER', 'EMAIL_CHAR_CHECK'));
        $objForm->setParam($_POST);
        $objForm->convParam();
        return $objForm;
    }

    /**
     * 受注一時情報.汎用項目2(dtb_order_temp.memo02)に格納する値を取得する。
     *
     * @return array 汎用項目2格納値
     */
    function getSerializeMessage($arrExecRet) {
        $message['title'] = $this->lfSetConvMSG(SUICA_MEG_TITLE, true);
        $message['payment_term'] = $this->lfSetConvMSG("お支払期限", LC_Mdl_PG_MULPAY_Utils::convertDate($arrExecRet['PaymentTerm'], "Y年m月d日 H時i分") . "\n\n");
        $message['message'] = $this->lfSetConvMSG(SUICA_MEG_SUB_TITLE, SUICA_MEG_BODY);
        return $message;
    }

    /**
     * EntryTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getEntryTranClassName() {
        return 'Entry_Suica';
    }

    /**
     * ExecTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getExecClassName() {
        return 'Exec_Suica';
    }

    /**
     * 決済処理が正常終了
     *
     * @return void
     */
    function orderComplete($order_id, $sqlval = array(), $order_status = ORDER_PAY_WAIT) {
        parent::orderComplete($order_id, $sqlval, $order_status);
    }

    /**
     * 利用金額上限を取得する。
     * 
     * @return integer 利用金額上限
     */
    function getRuleMax() {
        return SUICA_RULE_MAX;
    }

    function save2clickPaymentMethod(&$objForm, &$objPurchase, $arrOrder) {
        $sqlval['update_date'] = 'Now()';

        $user_confirm = str_repeat('*', strlen($objForm->getValue('email'))) . '@' . $objForm->getValue('email_domain');

        $data = array('email' => $objForm->getValue('email'),
                      'email_domain' => $objForm->getValue('email_domain'),
                      'payment_id' => $arrOrder['payment_id'],
                      'user_confirm' => $user_confirm,
                      );
        $sqlval['memo06'] = serialize($data);

        $objPurchase->registerOrder($_SESSION['order_id'], $sqlval);
    }

    function getMemo06(&$objForm, $payment_id) {
        $user_confirm = str_repeat('*', strlen($objForm->getValue('email'))) . '@' . $objForm->getValue('email_domain');
        $data = array('email' => $objForm->getValue('email'),
                      'email_domain' => $objForm->getValue('email_domain'),
                      'payment_id' => $payment_id,
                      'user_confirm' => $user_confirm,
                      );
        return serialize($data);
    }
}

?>
