<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright (c) 2006 PAYGENT Co.,Ltd. All rights reserved.
 *
 * https://www.paygent.co.jp/
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
require_once realpath(dirname( __FILE__)) . '/LC_Page_Mdl_Paygent_Config.php';

// 過去の版ではHTTP_Requestがautoloadされないことに対応
if (!class_exists('HTTP_Request')) {
    // 版によってモジュールの設置場所が異なることへの対応
    if (file_exists( DATA_REALDIR . 'module/HTTP/Request.php')) {
        require_once DATA_REALDIR . 'module/HTTP/Request.php';
    } elseif (file_exists(DATA_REALDIR . 'module/Request.php')) {
        require_once DATA_REALDIR . 'module/Request.php';
    }
}


class LC_Page_Mdl_Paygent_Helper_Link extends LC_Page_Ex {

    var $objFormParam;
    var $type;

    /**
     * コンストラクタ
     *
     * @return void
     */
    function LC_Page_Mdl_Paygent_Helper_Link($type) {
        $this->type = $type;
        $this->objFormParam = new SC_FormParam();
    }

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

        switch($this->type) {
        case PAY_PAYGENT_LINK:
            $tpl_name = "paygent_link.tpl";

            switch(SC_Display_Ex::detectDevice()) {
            case DEVICE_TYPE_MOBILE :
                $tpl_dir = '/templates/mobile/';
                break;
            case DEVICE_TYPE_SMARTPHONE :
                $tpl_dir = '/templates/sphone/';
                break;
            case DEVICE_TYPE_PC :
            default:
                $tpl_dir = '/templates/default/';
                break;
            }
            $this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . $tpl_dir . $tpl_name;
            break;
        default:
            GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type, PAYGENT_LOG_PATH_LINK);
            break;
        }

        session_cache_limiter('private-no-expire');
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
     * Page のプロセス.
     *
     * @return void
     */
    function action() {
        $objSiteSess = new SC_SiteSession_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objCartSess = new SC_CartSession_Ex();

        // 受注テーブルの読込
        if (isset($_SESSION['order_id'])){
            $order_id = $_SESSION['order_id'];
        } else {
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true, "");
        }
        // 受注情報の取得
        $arrData = $objPurchase->getOrder($order_id);

        // パラメータ情報の初期化
        $arrParam = $_POST;
        $this->initParam($arrData);
        $this->objFormParam->setParam($arrParam);

        // 0円決済(手数料はを除く)
        if ($arrData['payment_total'] == 0) {
            $this->orderComplete($order_id, array(), ORDER_PRE_END, '');
            SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
        }

        $objConfig = new LC_Page_Mdl_Paygent_Config();
        $this->arrConfig = $objConfig->getConfig();

        if (array_key_exists('link_payment', $this->arrConfig) && in_array(PAY_PAYGENT_LATER_PAYMENT , $this->arrConfig['link_payment'])) {
            $this->is_available_later = true;
        } else {
            $this->is_available_later = false;
        }

        switch($this->getMode()) {
        // 前のページに戻る
        case 'return':
            // 正常な推移であることを記録しておく
            $objPurchase->rollbackOrder($order_id, ORDER_CANCEL, true);
            SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
            exit;
            break;
        // 次へ
        case 'next':

            // 入力値の変換
            $this->objFormParam->convParam();

            // 後払い決済利用時
            if ($this->is_available_later) {

                $objPurchase = new SC_Helper_Purchase_Ex();
                $arrShippings = $objPurchase->getShippings($order_id, false);

                //複数配送指定時はエラー
                if (count($arrShippings) > 1) {
                    $this->tpl_error .= "複数配送先の指定はご利用頂けません。";
                    break;
                }
            } else {
                // エラーチェック(後払い決済利用時は入力フォームを非表示にするので入力値のチェックは行わない)
                $this->arrErr = $this->checkError();
            }

            // 入力エラーなしの場合
            if(count($this->arrErr) == 0) {

                // 入力データの取得
                $arrInput = $this->objFormParam->getHashArray();

                // 後払い決済利用時は利用者の入力フォームを表示しないのでDBの値をセットする。
                // (2.11以外はinitParamメソッドのaddParamで指定したデフォルト値が$arrInputにセットされるがフォーマット変換されないので入れ直す必要がある)
                if ($this->is_available_later) {
                    $arrInput['customer_family_name'] = mb_convert_kana($arrData['order_name01'],'KVA');
                    $arrInput['customer_name'] = mb_convert_kana($arrData['order_name02'],'KVA');
                    $arrInput['customer_family_name_kana'] = mb_convert_kana($arrData['order_kana01'],'CKVa');
                    $arrInput['customer_name_kana'] = mb_convert_kana($arrData['order_kana02'],'CKVa');
                }

                switch($this->type) {
                case PAY_PAYGENT_LINK:

                    // 決済申込電文送信
                    $arrRet = $this->sendPaygent($arrData, $arrInput);
                    // 受注＆ページ遷移
                    $this->linkPaygentPage($arrRet, $arrData['payment_total'], $order_id);
                    break;
                default:
                    GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type, PAYGENT_LOG_PATH_LINK);
                    break;
                }
            }
            break;
        }

        // 表示準備
        $this->dispData($arrData['payment_id']);
        $this->arrForm = $this->objFormParam->getFormParamList();
    }

    /**
     * パラメータ情報の初期化
     */
    function initParam($arrData) {
        switch($this->type) {
        case PAY_PAYGENT_LINK:
            $this->objFormParam->addParam("利用者姓", "customer_family_name", PAYGENT_LINK_STEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name01']);
            $this->objFormParam->addParam("利用者名", "customer_name", PAYGENT_LINK_STEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name02']);
            $this->objFormParam->addParam("利用者姓カナ", "customer_family_name_kana", PAYGENT_LINK_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana01']);
            $this->objFormParam->addParam("利用者名カナ", "customer_name_kana", PAYGENT_LINK_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana02']);
            break;
        default:
            GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type, PAYGENT_LOG_PATH_LINK);
            break;
        }
   }

    /**
     * 入力内容のチェック
     */
    function checkError() {
        $objErr->arrErr = $this->objFormParam->checkError();
        return $objErr->arrErr;
    }

    /**
     * 決済申込電文送信
     */
    function sendPaygent($arrData, $arrInput) {
        // 決済用パラメータの取得
        $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_LINK. "'");
        $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

        // マーチャント取引ID
        $arrSend['trading_id'] = $arrData['order_id'];
        // 決済金額
        $arrSend['id'] = $arrData['payment_total'];
        // マーチャントID
        $arrSend['seq_merchant_id'] = $arrPaymentDB[0]['merchant_id'];
        // マーチャント名
        $arrSend['merchant_name'] = $arrOtherParam['merchant_name'];
        // 支払期間
        $arrSend['payment_term_day'] = $arrOtherParam['payment_term_day'];
        // 自由メモ欄
        $arrSend['free_memo'] = $arrOtherParam['free_memo'];
        // 戻りURL・中断時URL
        $arrSend['return_url'] = HTTPS_URL . "index.php?" . TRANSACTION_ID_NAME . "=" . $this->transactionid;
        $arrSend['stop_return_url'] = HTTPS_URL . "index.php?" . TRANSACTION_ID_NAME . "=" . $this->transactionid;
        // コピーライト
        $arrSend['copy_right'] = $arrOtherParam['copy_right'];
        // 利用者姓
        $arrSend['customer_family_name'] = $arrInput['customer_family_name'];
        // 利用者名
        $arrSend['customer_name'] = $arrInput['customer_name'];
        // 利用者姓半角カナ
        $arrSend['customer_family_name_kana'] = mb_convert_kana($arrInput['customer_family_name_kana'],'k');
        $arrSend['customer_family_name_kana'] = preg_replace("/ｰ/", "-", $arrSend['customer_family_name_kana']);
        $arrSend['customer_family_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['customer_family_name_kana']);
        // 利用者名半角カナ
        $arrSend['customer_name_kana'] = mb_convert_kana($arrInput['customer_name_kana'],'k');
        $arrSend['customer_name_kana'] = preg_replace("/ｰ/", "-", $arrSend['customer_name_kana']);
        $arrSend['customer_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['customer_name_kana']);
        // 連携モード(URL連携方式)
        $arrSend['isbtob'] = 1;
        // 支払区分
        $arrSend['payment_class'] = $arrOtherParam['payment_class'];
        // カード確認番号利用フラグ
        $arrSend['use_card_conf_number'] = $arrOtherParam['use_card_conf_number'];
        // 利用者電話番号
        $arrSend['customer_tel'] = $arrData['order_tel01']. $arrData['order_tel02']. $arrData['order_tel03'];
        // 決済モジュール識別
        $arrSend['partner'] = 'lockon';
        // EC-CUBE本体のバージョン
        $arrSend['eccube_version'] = ECCUBE_VERSION;
        // 決済モジュールのアップデート日時
        $arrSend['eccube_module_install_date'] = getModuleUpdateDate();

        // 後払い決済利用時
        if ($this->is_available_later) {

            //「決済モジュール設定画面で同梱が設定されている場合」かつ「注文者と配送先が同じ場合」
            if ($this->arrConfig['link_invoice_include'] && isSameOrderShip($arrData['order_id'])) {
                $invoice_send_type = INVOICE_SEND_TYPE_INCLUDE;
            } else {
                $invoice_send_type = INVOICE_SEND_TYPE_SEPARATE;
            }

            // 後払い決済
            $arrSend += sfGetPaygentLaterPaymentLink($arrData['order_id'], $invoice_send_type);
            // 結果取得区分
            $arrSend['result_get_type'] = $arrOtherParam['result_get_type'];
            // 自動キャンセル区分
            $arrSend['auto_cancel_type'] = $arrOtherParam['auto_cancel_type'];
            // 手数料
            $arrSend['commission'] = $arrData['charge'];

            // 固定項目
            if ($invoice_send_type === INVOICE_SEND_TYPE_INCLUDE) {
                $arrSend['fix_params'] = "customer_info";
            } else {
                $arrSend['fix_params'] = "customer_info,ship_info";
            }
        }

        // ハッシュ値
        if (strlen($arrOtherParam['hash_key']) > 0) {
            require_once(realpath(dirname( __FILE__)). "/paygent_hash.php");
            $arrSend['hc'] = setPaygentHash($arrSend, $arrOtherParam['hash_key']);
        }

        // リクエスト
        return $this->sendRequest($arrOtherParam['link_url'], $arrSend);
    }

    /**
     * リクエスト送信
     */
    function sendRequest($link_url, $arrSend) {
        // リクエスト設定
        $req = new HTTP_Request($link_url);
        $req->setMethod(HTTP_REQUEST_METHOD_POST);

        // 送信
        //// HTTP_Requestの版によってaddPostDataArray()が存在しないことへの対応
        if (method_exists($req, 'addPostDataArray')) {
            $req->addPostDataArray($arrSend);
        } else {
            foreach ($arrSend as $key=>$value) {
                $req->addPostData($key, $value);
            }
        }
        $response = $req->sendRequest();
        $req->clearPostData();

        // 通信エラーチェック
        if (!PEAR::isError($response)) {
            $body = $req->getResponseBody();
            $err_flg = false;
        } else {
            $mess = mb_convert_encoding($response->getMessage(), CHAR_CODE, "SJIS");
            $err_flg = true;
        }

        // レスポンス整理
        if (!$err_flg) {
            $res = $this->putResponse($body);
            return $res;
        } else {
            return $mess;
        }
    }

    /**
     * レスポンス整理
     */
    function putResponse($body) {
        $body = split("\r\n", $body);
        $logtext = "\n************ Response start ************";
        foreach ($body as $i => $line) {
            $item = split("=", $line, 2);
            if (strlen($item[0]) > 0) {
                $res[$item[0]] = $item[1];
                $logtext .= "\n". $item[0]." = ".$item[1];
            }
        }
        $logtext .= "\n************ Response end ************";
        GC_Utils::gfPrintLog($logtext, PAYGENT_LOG_PATH_LINK);
        return $res;
    }

    /**
     * 受注＆ページ遷移
     */
    function linkPaygentPage($arrRet, $payment_total, $order_id) {
        // 成功
        if ($arrRet['result'] === "0") {

            // 正常に登録されたことを記録
            $objSiteSess = new SC_SiteSession_Ex();
            $objSiteSess->setRegistFlag();

            $arrInitStatus = getInitStatus();
            $order_status = $arrInitStatus[PAYGENT_LINK];

            // 受注登録
            $arrMemo['title'] = sfSetConvMSG("お支払", true);
            $arrMemo['payment_url'] = sfSetConvMSG("お支払画面URL", $arrRet['url']);
            $year = substr($arrRet['limit_date'], 0, 4);
            $month = substr($arrRet['limit_date'], 4, 2);
            $day = substr($arrRet['limit_date'], 6, 2);
            $hour = substr($arrRet['limit_date'], 8, 2);
            $minute = substr($arrRet['limit_date'], 10, 2);
            $second = substr($arrRet['limit_date'], 12);
            $arrMemo['limit_date'] = sfSetConvMSG("お支払期限", "$year/$month/$day $hour:$minute:$second");

            $sqlVal['memo01'] = MDL_PAYGENT_CODE;
            $sqlVal['memo02'] = serialize($arrMemo);
            $sqlVal['memo03'] = $arrRet['result'];
            $sqlVal['memo08'] = PAYGENT_LINK;
            $this->orderComplete($order_id, $sqlVal, $order_status, PAY_PAYGENT_LINK);

            // ペイジェント決済画面に遷移
            header("Location: ". $arrRet['url']);

        // 失敗
        } elseif ($arrRet['result'] === "1") {
            $this->tpl_error = "決済に失敗しました。";
            if (preg_match('/^[P|E]/', $arrRet['response_code']) <= 0) {
                $this->tpl_error .= "<br />". $arrRet['response_detail']. "（". $arrRet['response_code']. "）";
            } else {
                $this->tpl_error .= "（". $arrRet['response_code']. "）";
                $this->tpl_error_detail = getLaterPaymentDetailMsg($arrRet['response_code'], $arrRet['response_detail'], SETTLEMENT_LINK);

                if ($this->tpl_error_detail != NO_MAPPING_MESSAGE) {

                    // 会員ログインチェック
                    $objCustomer = new SC_Customer_Ex();
                    if ($objCustomer->isLoginSuccess(true)) {
                        $this->tpl_login = '1';
                    }

                    $this->show_attention = '1';
                }
            }

        // 通信エラー
        } else {
            $this->tpl_error = "決済に失敗しました。<br />". $arrRet;
        }
    }

    /**
     * 表示用モジュール情報を取得
     */
    function dispData($payment_id) {
       $objQuery =& SC_Query_Ex::getSingletonInstance();
       // 支払方法の説明画像を取得
       $arrRet = $objQuery->select("payment_method, payment_image", "dtb_payment", "payment_id = ?", array($payment_id));
       $this->tpl_title = $arrRet[0]['payment_method'];
       $this->tpl_payment_method = $arrRet[0]['payment_method'];
       $this->tpl_payment_image = $arrRet[0]['payment_image'];
    }

    /**
     * 決済処理が正常終了
     */
    function orderComplete($order_id, $sqlval = array(), $order_status = ORDER_NEW, $type = PAY_PAYGENT_LINK) {
        $objPurchase = new SC_Helper_Purchase_Ex();

        // 受注ステータスを「決済処理中」から更新する。
        if ($order_status != ORDER_PENDING) { // iDでは更新しない
            $objPurchase->sfUpdateOrderStatus($order_id, $order_status, null, null, $sqlval);
        } else if (!empty($sqlval)) {
            $objPurchase->registerOrder($order_id, $sqlval);
        }

        // 受注完了メールを送信する。
        $objPurchase->sendOrderMail($order_id);

        // セッションに紐付く情報を削除する。
        $objCartSession = new SC_CartSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase->cleanupSession(null, $objCartSession, $objCustomer, $objCartSession->getKey());

        $_SESSION['paygent_order_id'] = $_SESSION['order_id'];
    }
}
?>
