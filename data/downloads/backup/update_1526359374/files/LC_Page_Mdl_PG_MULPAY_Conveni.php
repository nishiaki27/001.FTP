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
 * コンビニ決済情報入力画面クラス.
 *
 * @package Page
 */
class LC_Page_Mdl_PG_MULPAY_Conveni extends LC_Page_Mdl_PG_MULPAY {
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        $this->tpl_file = 'conveni.tpl';

        parent::init();

        $this->tpl_column_num = 1;
    }

    /**
     * テンプレート変数をassignする
     *
     */
    function setTemplateVars() {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $this->tpl_payment_method = 'コンビニ決済';
        $this->arrUseConveni = $objPG->getUserSettings('conveni');

        $this->arrCONVENI = $GLOBALS['arrCONVENI'];
        $this->arrConveniMegTitle = $GLOBALS['$arrConveniMegTitle'];
        $this->arrConveniMegSubTitle = $GLOBALS['arrConveniMegSubTitle'];
        $this->arrConveniMegBody = $GLOBALS['arrConveniMegBody'];
    }

    /**
     * フォームパラメータの初期化
     *
     * @return SC_FormParam
     */
    function initParam() {
        $objForm = new SC_FormParam_Ex();
        $objForm->addParam('お支払いコンビニエンスストア', 'conveni', STEXT_LEN, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
        $objForm->setParam($_POST);
        $objForm->convParam();
        return $objForm;
    }
    
    /**
     * リクエストパラメータに使用する値をチェックする。
     *
     * @return void
     */
    function checkParamError($uniqid, $objSiteSess) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrData = $objPurchase->getOrder($_SESSION['order_id']);
        if ($arrData['del_flg'] == '1') {
            $this->panic($_SESSION['order_id'], 'ご注文情報が無効になっています。');
        }

        $customerName = $arrData['order_name01'] . "　" . $arrData['order_name02'];    // 氏名
        $customerKana = $arrData['order_kana01'] . "　" . $arrData['order_kana02'];    // フリガナ
        $telNo = $arrData['order_tel01'] . "-" . $arrData['order_tel02'] . "-" . $arrData['order_tel03'];    // 電話番号
        $message = '';
        if (strlen(mb_convert_encoding($customerName, "SJIS", "UTF-8")) > CUSTOMER_NAME_LEN) {
            $message = "お客様のご氏名の桁数がシステムの許容範囲外です。<br>";
        }
        if (strlen(mb_convert_encoding($customerKana, "SJIS", "UTF-8")) > CUSTOMER_KANA_LEN) {
            $message .= "お客様のフリガナの桁数がシステムの許容範囲外です。<br>";
        }
        if (strlen($telNo) > TEL_NO_LEN) {
            $message .= "お客様のお電話番号の桁数がシステムの許容範囲外です<br>";
        }
        if (strlen($message) != 0) {
            $message .= "<br><br>ご選択されたお支払方法は、ご利用になれません。<br>";
            $message .= "申し訳ありませんが、その他のお支払方法をお選びください。";

            $this->panic($_SESSION['order_id'], $message);
        }
    }
    
    /**
     * 受注一時情報.汎用項目2(dtb_order_temp.memo02)に格納する値を取得する。
     *
     * @return array 汎用項目2格納値
     */
    function getSerializeMessage($arrExecRet) {
        $msg_body = $this->arrConveniMegBody[$this->arrForm['conveni']];
        if ($this->arrForm['conveni'] === CONVENI_SEVENELEVEN) {
            // GMOPGからメールを送信させない場合は、【払込票URLを利用した支払方法】を表示しない。
            if (!MDL_PG_MULPAY_CONF_PGMAIL_CONVENI && !MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_SEVENELEVEN) {
                $msg_body = mb_substr($msg_body, mb_strpos($msg_body, "払込票番号でのお支払い"));
                $msg_body = "\n" . $msg_body;
            }

            $msg_body = str_replace('%RECEIPT_NO%', $arrExecRet['ReceiptNo'], $msg_body);
        }

        $message['title'] = $this->lfSetConvMSG($this->arrConveniMegTitle[$this->arrForm['conveni']], true);
        $temp_message = $this->lfSetConvMSG($this->arrConveniMegSubTitle[$this->arrForm['conveni']], $msg_body);
        if ($this->arrForm['conveni'] === CONVENI_LOSON || $this->arrForm['conveni'] === CONVENI_FAMILYMART) {
            $message['receipt_no'] = $this->lfSetConvMSG("お客様番号", $arrExecRet['ReceiptNo']);
        } else {
            $message['receipt_no'] = $this->lfSetConvMSG("オンライン決済番号", substr($arrExecRet['ReceiptNo'], 0, 4) . '-' . substr($arrExecRet['ReceiptNo'], 4));
        }
        $message['conf_no'] = $this->lfSetConvMSG("確認番号", $arrExecRet['ConfNo']);
        $message['payment_term'] = $this->lfSetConvMSG("お支払期限", LC_Mdl_PG_MULPAY_Utils::convertDate($this->arrExecRet['PaymentTerm'], "Y年m月d日") . "\n\n");
        $message['message'] = $temp_message;
        return $message;
    }

    /**
     * EntryTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getEntryTranClassName() {
        return 'Entry_Conveni';
    }

    /**
     * ExecTranクラス名を取得する
     *
     * @return stirng ExecTranクラス名
     */
    function getExecClassName() {
        return 'Exec_Conveni';
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
        return CONVENI_RULE_MAX;
    }

    function save2clickPaymentMethod(&$objForm, &$objPurchase, $arrOrder) {
        $sqlval['memo06'] = $this->getMemo06($objForm, $arrOrder['payment_id']);

        $objPurchase->registerOrder($_SESSION['order_id'], $sqlval);
    }

    function getMemo06(&$objForm, $payment_id) {
        $conveni = $objForm->getValue('conveni');
        $data = array('conveni' => $conveni,
                      'payment_id' => $payment_id,
                      'user_confirm' => $this->arrCONVENI[$conveni],
                      );
        return serialize($data);
    }
}

?>
