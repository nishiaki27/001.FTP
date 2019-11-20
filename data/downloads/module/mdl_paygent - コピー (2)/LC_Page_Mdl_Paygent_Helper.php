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
require_once MODULE_REALDIR . "mdl_paygent/SC_Mdl_Quick_Helper.php";

class LC_Page_Mdl_Paygent_Helper extends LC_Page_Ex {

    var $type;
    var $objFormParam;
    /**
     * コンストラクタ
     *
     * @return void
     */
    function LC_Page_Mdl_Paygent_Helper($type=null) {
    	if (is_null($type)) {
    		$this->type = PAY_PAYGENT_CREDIT;
    	} else {
    		$this->type = $type;
    	}
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
        case PAY_PAYGENT_CREDIT:
            $tpl_name = "paygent_credit.tpl";
            break;
        case PAY_PAYGENT_CONVENI_NUM:
            $tpl_name = "paygent_conveni.tpl";
            break;
        case PAY_PAYGENT_CONVENI_CALL:
            $tpl_name = "paygent_conveni_call.tpl";
            break;
        case PAY_PAYGENT_ATM:
            $tpl_name = "paygent_atm.tpl";
            break;
        case PAY_PAYGENT_BANK:
            $tpl_name = "paygent_bank.tpl";
            break;
        case PAY_PAYGENT_CAREER:
            $tpl_name = "paygent_career.tpl";
            break;
        case PAY_PAYGENT_EMONEY:
            $tpl_name = "paygent_emoney.tpl";
            break;
        case PAY_PAYGENT_YAHOOWALLET:
        	$tpl_name = "paygent_yahoowallet.tpl";
            break;
        case PAY_PAYGENT_VIRTUAL_ACCOUNT:
            $tpl_name = "paygent_virtual_account.tpl";
            break;
        case PAY_PAYGENT_LATER_PAYMENT:
            $tpl_name = "paygent_later_payment.tpl";
            break;
        case PAY_PAYGENT_PAIDY:
            $tpl_name = "paygent_paidy.tpl";
            break;
        default:
            GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type, PAYGENT_LOG_PATH);
            break;
        }
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
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objSiteSess = new SC_SiteSession_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objQuickHelper = new SC_Mdl_Quick_Helper();

        // クイック決済の情報取得と破棄
        $quick_info = $objQuickHelper->getQuickPayInfo();
        $objQuickHelper->clearQuickPayInfo();

        // モード設定
        $mode = $this->getSetMode($this->getMode(), $quick_info, $this->type);

        // 受注テーブルの読込
        if (isset($_SESSION['order_id'])){
            $order_id = $_SESSION['order_id'];
        } else {
            $order_id = $_REQUEST['order_id'];
            if (!array_key_exists('hash', $_REQUEST)) {
                SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true, "");
            }
            $hash = $_REQUEST['hash'];
            $_SESSION['order_id'] = $order_id;
        }
        // 受注情報の取得
        $arrData = $objPurchase->getOrder($order_id);

        if (compact('hash') && $hash !== createPaygentHash($arrData)) {
            unset($_SESSION['order_id']);
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true, "");
        }

		if ($mode === "career_auth") {
			// キャリア決済オーソリ後の処理
			$this->lfMoveCareerComplete();

		} else {
			if (
				// 受注情報が取得できない場合
				count($arrData) <= 0
				||
				// 受注状態が "7"：決済処理中 ではなく、
				// 銀行ネット決済の 受注状態が "2"：入金待ち でもない場合
				// Paidy決済の受注状態が"1":新規受付でもない場合
				(
					$arrData['status'] != ORDER_PENDING
					&& !($arrData['status'] == ORDER_PAY_WAIT && $this->type == PAY_PAYGENT_BANK)
				    && !($arrData['status'] == ORDER_NEW && $this->type == PAY_PAYGENT_PAIDY)
				)
				||
				// 決済ベンダの画面からブラウザバックで遷移した場合
				(
					// 課題No.111 対応
					( !isset($mode) || $mode === "next" || $mode === "quick" )
					&& $arrData['memo03'] !== "1"	// 処理結果 が "1"：異常 ではない
					&& !empty($arrData['memo06'])	// 決済ID が空ではない
					&& empty($arrData['memo08'])	// 電文種別ID が空である
				)
				||
				(
					( !isset($mode) || $mode === "next" )
					&& (
						$arrData['memo08'] == PAYGENT_BANK												// "060"：銀行ネット決済ASP申込電文
						|| (empty($arrData['memo04']) && $arrData['memo08'] == PAYGENT_CAREER_D)		// "100_1"：携帯キャリア決済申込電文（docomo）
						|| (empty($arrData['memo04']) && $arrData['memo08'] == PAYGENT_CAREER_A)		// "100_2"：携帯キャリア決済申込電文（au）
						|| (empty($arrData['memo04']) && $arrData['memo08'] == PAYGENT_CAREER_S)		// "100_3"：携帯キャリア決済申込電文（SoftBank）
						|| (empty($arrData['memo04']) && $arrData['memo08'] == PAYGENT_CAREER_AUTH_D)	// "104_1"：携帯キャリア決済ユーザ認証要求（docomo）
						|| (empty($arrData['memo04']) && $arrData['memo08'] == PAYGENT_CAREER_AUTH_A)	// "104_2"：携帯キャリア決済ユーザ認証要求（au）
						|| $arrData['memo08'] == PAYGENT_EMONEY_W										// "150_1"：電子マネー決済申込電文（WebMoney）
						|| $arrData['memo08'] == PAYGENT_YAHOOWALLET									// "160"：Yahoo!ウォレット決済申込電文
					)
				)
				||
				(
					$mode === "career_authentication"
					&& (
						(empty($arrData['memo04']) && $arrData['memo08'] == PAYGENT_CAREER_D)		// "100_1"：携帯キャリア決済申込電文（docomo）
						|| (empty($arrData['memo04']) && $arrData['memo08'] == PAYGENT_CAREER_A)	// "100_2"：携帯キャリア決済申込電文（au）)
					)
				)
			) {
				//「不正なページ移動です」エラー画面を表示
				SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true, "");
			}

			// Paidy決済の時、初回遷移 or  リロード時 or ブラウザバック時、既に受注情報.ステータスが新規受付であれば不正遷移エラーとする。
			if ($this->type == PAY_PAYGENT_PAIDY && $mode === null && ($arrData['status'] == ORDER_NEW)){
			    //「不正なページ移動です」エラー画面を表示
			    SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true, "");
			}

            // クイック決済時にはクイック情報設定
            if($quick_info['quick_flg'] == "1") {
                $quick_memo = $quick_info['quick_memo'];

                switch($this->type) {
                // クレジット決済時
                case PAY_PAYGENT_CREDIT:
                    $quick_memo['stock'] = "1";
                    $quick_memo['security_code'] = $quick_info['security_code'];
                    $quick_memo['card_token'] = $quick_info['card_token'];

                    if (SC_Utils_Ex::isBlank($quick_memo['split_count'])) {
                        $paymentDivision = $quick_memo['payment_class'];
                    } else {
          	              $paymentDivision = $quick_memo['payment_class'] . "-" . $quick_memo['split_count'];
                    }
                    $quick_memo['payment_class'] = $paymentDivision;
                    break;
                }

                $arrParam = $quick_memo;
            } else {
                $arrParam = $_POST;
            }
            // パラメータ情報の初期化
            $this->initStockParam($arrParam);
            $this->initParam($arrData, $mode);
            $this->objFormParam->setParam($arrParam);

            // キャリア決済の場合は事前に端末の情報を取得しておく
            if($this->tpl_mainpage == MODULE_REALDIR. MDL_PAYGENT_CODE. "/templates/mobile/paygent_career.tpl") {
                $this->lfCheckCareer();
            }

            // 0円決済(手数料はを除く)
            if ($arrData['payment_total'] == 0) {
                $this->orderComplete($order_id, array(), ORDER_PRE_END, '');
            }
        }

        $objConfig = new LC_Page_Mdl_Paygent_Config();
        $arrConfig = $objConfig->getConfig();

        switch($mode) {
        // 前のページに戻る
        case 'emoney_commit_cancel':
        case 'yahoowallet_commit_cancel':
        case 'career_authentication_cancel':
        case 'career_auth_cancel':
        case 'paidy_commit_cancel':
        case 'return':
            // 正常な推移であることを記録しておく
            $objPurchase->rollbackOrder($order_id, ORDER_CANCEL, true);
            SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
            $objCartSess->setValue('send_paygent','false', PAYGENT_CART_SESS_KEY);
            exit;
            break;
        // 次へ
        case 'next':
            // 入力値の変換
            $this->objFormParam->convParam();
            $this->arrErr = $this->checkError();
            // Paidy決済の多重注文対策。
            if ($this->type == PAY_PAYGENT_PAIDY && $arrData['status'] == ORDER_NEW) {
                SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true, "");
            }
            $send_paygent = $objCartSess->getValue('send_paygent', PAYGENT_CART_SESS_KEY);
            if($send_paygent == "true"){
                // 正常に登録されたことを記録
                $objSiteSess->setRegistFlag();
                SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                GC_Utils::gfPrintLog("ボタンを2度押しエラー");
                break;
            }

            // 入力エラーなしの場合
            if(count($this->arrErr) == 0) {
                // 入力データの取得
                $arrInput = $this->objFormParam->getHashArray();
                $objCartSess->setValue('send_paygent','true', PAYGENT_CART_SESS_KEY);

                switch($this->type) {
                // クレジット電文送信
                case PAY_PAYGENT_CREDIT:
                    // オーソリ失敗回数のクリア
                    if (isset($_SESSION['paygent_card_error_lock_expire']) && $_SESSION['paygent_card_error_lock_expire'] < time()) {
                        $this->resetCardErrorCount($arrData['order_temp_id']);
                    }
                    // オーソリ実行可否判定
                    if (isset($_SESSION['paygent_card_error_count']) && $_SESSION['paygent_card_error_count'] >= CREDIT_AUTHORITY_RETRY_LIMIT) {
                        $this->tpl_error = CREDIT_AUTHORITY_LOCK_MESSAGE;
                        break;
                    }
                    $arrRet = sfSendPaygentCredit($arrData, $arrInput, $order_id, $this->transactionid);
                    $this->sendData_Credit($arrRet, $arrData, $arrInput);
                    break;
                // コンビニ(番号方式)電文送信
                case PAY_PAYGENT_CONVENI_NUM:
                    $arrRet = sfSendPaygentConveni($arrData, $arrInput, $order_id);
                    $sqlVal = array();
                    $sqlVal['quick_flg'] = "1";
                    $quick_memo['cvs_company_id'] = $arrInput['cvs_company_id'];
                    $quick_memo['customer_family_name'] = $arrInput['customer_family_name'];
                    $quick_memo['customer_name'] = $arrInput['customer_name'];
                    $quick_memo['customer_family_name_kana'] = $arrInput['customer_family_name_kana'];
                    $quick_memo['customer_name_kana'] = $arrInput['customer_name_kana'];
                    $quick_memo['customer_tel'] = $arrInput['customer_tel'];
                    $sqlVal['quick_memo'] = serialize($quick_memo);
                    $this->sendData($arrRet, $arrData['payment_total'], $order_id, PAYGENT_CONVENI_NUM, $sqlVal);
                    break;
                // コンビニ(払込票方式)電文送信
                case PAY_PAYGENT_CONVENI_CALL:
                    $arrRet = sfSendPaygentConveniCall($arrData, $arrInput, $order_id);
                    $this->sendData($arrRet, $arrData['payment_total'], $order_id, PAYGENT_CONVENI_CALL);
                    break;
                // ATM電文送信
                case PAY_PAYGENT_ATM:
                    $arrRet = sfSendPaygentATM($arrData, $arrInput, $order_id);
                    $sqlVal = array();
                    $sqlVal['quick_flg'] = "1";
                    $quick_memo['customer_family_name'] = $arrInput['customer_family_name'];
                    $quick_memo['customer_name'] = $arrInput['customer_name'];
                    $quick_memo['customer_family_name_kana'] = $arrInput['customer_family_name_kana'];
                    $quick_memo['customer_name_kana'] = $arrInput['customer_name_kana'];
                    $sqlVal['quick_memo'] = serialize($quick_memo);
                    $this->sendData($arrRet, $arrData['payment_total'], $order_id, PAYGENT_ATM, $sqlVal);
                    break;
                // ネット銀行電文送信
                case PAY_PAYGENT_BANK:
                    $arrRet = sfSendPaygentBANK($arrData, $arrInput, $order_id, $this->transactionid);
                    $sqlVal = array();
                    $sqlVal['quick_flg'] = "1";
                    $quick_memo['customer_family_name'] = $arrInput['customer_family_name'];
                    $quick_memo['customer_name'] = $arrInput['customer_name'];
                    $quick_memo['customer_family_name_kana'] = $arrInput['customer_family_name_kana'];
                    $quick_memo['customer_name_kana'] = $arrInput['customer_name_kana'];
                    $sqlVal['quick_memo'] = serialize($quick_memo);
                    $this->sendData_Bank($arrRet, $order_id, $arrData['payment_total'], $sqlVal);
                    break;

				// キャリア電文送信
				case PAY_PAYGENT_CAREER:
					$arrRet = array();

					switch ($this->tpl_mainpage) {
						case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/default/paygent_career.tpl":
							// 端末が PC の場合
							switch ($arrInput['career_type']) {
								case CAREER_MOBILE_TYPE_DOCOMO:
									// ドコモ払いの場合、携帯キャリア決済ユーザ認証要求電文を送信
									$arrRet = sfSendPaygentAuthCareer($arrData, $arrInput, $order_id, $this->transactionid, PC_MOBILE_TYPE_PC);
									break;
								case CAREER_MOBILE_TYPE_AU:
									// auかんたん決済の場合、携帯キャリア決済ユーザ認証要求電文を送信
									$arrRet = sfSendPaygentAuthCareer($arrData, $arrInput, $order_id, $this->transactionid, PC_MOBILE_TYPE_PC);
									break;
								case CAREER_MOBILE_TYPE_SOFTBANK:
									// ソフトバンクの場合、携帯キャリア決済申込電文を送信
									$arrRet = sfSendPaygentCareer($arrData, $arrInput, $order_id, $this->transactionid, PC_MOBILE_TYPE_PC);
									break;
							}
							break;

						case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/mobile/paygent_career.tpl":
							// 端末が mobile の場合
							switch ($arrInput['career_type']) {
								case CAREER_MOBILE_TYPE_DOCOMO:
									// ドコモ払いの場合、携帯キャリア決済申込電文を送信
									$arrRet = sfSendPaygentCareer($arrData, $arrInput, $order_id, $this->transactionid, $this->career_type);
									break;
								case CAREER_MOBILE_TYPE_AU:
									// auかんたん決済の場合、携帯キャリア決済ユーザ認証要求電文を送信
									$arrRet = sfSendPaygentAuthCareer($arrData, $arrInput, $order_id, $this->transactionid, $this->career_type);
									break;
								case CAREER_MOBILE_TYPE_SOFTBANK:
									// ソフトバンクの場合、エラー
									$this->arrErr['career_type'] = "フィーチャーフォンでソフトバンク・ワイモバイルまとめて支払いは利用できません。<br />";
									break;
							}
							break;

						case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/sphone/paygent_career.tpl":
							// 端末が SmartPhone の場合
							switch ($arrInput['career_type']) {
								case CAREER_MOBILE_TYPE_DOCOMO:
									// ドコモ払いの場合、携帯キャリア決済ユーザ認証要求電文を送信
									$arrRet = sfSendPaygentAuthCareer($arrData, $arrInput, $order_id, $this->transactionid, PC_MOBILE_TYPE_SMARTPHONE);
									break;
								case CAREER_MOBILE_TYPE_AU:
									// auかんたん決済の場合、携帯キャリア決済ユーザ認証要求電文を送信
									$arrRet = sfSendPaygentAuthCareer($arrData, $arrInput, $order_id, $this->transactionid, PC_MOBILE_TYPE_SMARTPHONE);
									break;
								case CAREER_MOBILE_TYPE_SOFTBANK:
									// ソフトバンクの場合、携帯キャリア決済申込電文を送信
									$arrRet = sfSendPaygentCareer($arrData, $arrInput, $order_id, $this->transactionid, PC_MOBILE_TYPE_SMARTPHONE);
									break;
							}
							break;
					}

					$this->sendData_Career($arrRet);

					break;

                // 電子マネー電文送信
                case PAY_PAYGENT_EMONEY:
                	$arrRet = sfSendPaygentEMoney($arrData, $arrInput, $order_id, $_POST['PHPSESSID'], $this->transactionid);
                	$this->sendData_EMoney($arrRet, $arrData['payment_total']);
                	break;
                // Yahoo!ウォレット
                case PAY_PAYGENT_YAHOOWALLET:
                	$objCartSess->setValue('send_paygent','false', PAYGENT_CART_SESS_KEY);
		        	$arrRet = sfSendPaygentYahoowallet($arrData, $order_id, $this->transactionid);
		        	$this->sendData_Yahoowallet($arrRet);
                	break;
                case PAY_PAYGENT_VIRTUAL_ACCOUNT:
                    $arrRet = sfSendPaygentVirtualAccount($arrData, $arrInput, $this->transactionid);
                    $sqlVal = array();
                    $sqlVal['quick_flg'] = "1";
                    $quick_memo['billing_family_name'] = $arrInput['billing_family_name'];
                    $quick_memo['billing_name'] = $arrInput['billing_name'];
                    $quick_memo['billing_family_name_kana'] = $arrInput['billing_family_name_kana'];
                    $quick_memo['billing_name_kana'] = $arrInput['billing_name_kana'];
                    $sqlVal['quick_memo'] = serialize($quick_memo);
                    $this->sendData_VirtualAccount($arrRet, $arrData['payment_total'], $order_id, PAYGENT_VIRTUAL_ACCOUNT, $sqlVal);
                    break;
                case PAY_PAYGENT_LATER_PAYMENT:

                    //請求書送付方法を判定
                    $invoice_send_type = getInvoiceSendType($order_id);

                    //請求書送付方法を保存
                    $objQuery =& SC_Query_Ex::getSingletonInstance();
                    $objQuery->update("dtb_order", array("invoice_send_type" => $invoice_send_type), "order_id = ?", array($order_id));

                    $arrRet = sfSendPaygentLaterPayment($arrData, $arrInput, $this->transactionid, $invoice_send_type);
                    $sqlVal = array();
                    $sqlVal['quick_flg'] = "1";
                    $this->sendData_LaterPayment($arrRet, $arrData['payment_total'], $order_id, PAYGENT_LATER_PAYMENT, $sqlVal, $invoice_send_type);
                    break;
                // Paidy決済 次へボタン押下時処理
                case PAY_PAYGENT_PAIDY:
                    // 決済ステータス、汎用項目2、更新処理
                    $order_status = ORDER_NEW;
                    $sqlVal = array();
                    $arrMemo['ecOrderData'] = array(
                        'payment_total' => $arrData['payment_total'],
                        'payment_total_check_status' => ''
                    );
                    $sqlval["memo01"] = MDL_PAYGENT_CODE;
                    $sqlval['memo02'] = serialize($arrMemo);
                    $sqlval["memo08"] = PAYGENT_PAIDY;
                    $objPurchase->sfUpdateOrderStatus($order_id, $order_status, null, null, $sqlval);
                    // ステータス変更が終われば画面再表示無しで処理終了させる。
                    SC_Response_Ex::actionExit();
                    break;
                default:
                    GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type, PAYGENT_LOG_PATH);
                    break;
                }
                if (strlen($this->tpl_error)>0){
                    $objCartSess->setValue('send_paygent','false', PAYGENT_CART_SESS_KEY);
                }
            }
            break;
        // 3Dセキュア実施後のクレジット電文送信
        case '3d_secure':
            $arrRet = sfSendPaygentCredit3d($arrData, $_POST, $arrData['order_id']);

            if ($arrRet['result'] === "0") {
                $this->resetCardErrorCount($arrData['order_temp_id']);
            }

            $this->sendData($arrRet, $arrData['payment_total'], $arrData['order_id'], PAYGENT_CREDIT);

            if ($arrRet['result'] != "0") {
                $this->incrementCardErrorCount($arrData['order_temp_id']);
                if ($_SESSION['paygent_card_error_count'] >= CREDIT_AUTHORITY_RETRY_LIMIT) {
                    $this->tpl_error .= "<br><br>" . CREDIT_AUTHORITY_LOCK_MESSAGE;
                }
            }

            if (strlen($this->tpl_error)>0){
                $objCartSess->setValue('send_paygent','false', PAYGENT_CART_SESS_KEY);
            }
            break;
        // 登録カード削除
        case 'deletecard':
            // 入力値の変換
            $this->objFormParam->convParam();
            $this->arrErr = $this->checkError();
            // 入力エラーなしの場合
            if(count($this->arrErr) == 0) {
                // 入力データの取得
                $arrInput = $this->objFormParam->getHashArray();
                $arrRet = sfDelPaygentCreditStock($arrData, $arrInput);
                // 失敗
                if ($arrRet[0]['result'] !== "0") {
                    $this->arrErr['CardSeq'] = "登録カード情報の削除に失敗しました。". $arrRet[0]['response'];
                }
                $objCartSess->setValue('send_paygent','false', PAYGENT_CART_SESS_KEY);
            }
            break;

		// 携帯キャリア決済ユーザ認証要求電文送信後
		case 'career_authentication':
			$open_id = $_GET['open_id'];

			switch ($this->tpl_mainpage) {
				case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/default/paygent_career.tpl":
					// 端末が PC の場合
					$arrRet = sfSendPaygentCareer($arrData, $arrInput, $order_id, $this->transactionid, PC_MOBILE_TYPE_PC, $open_id);
					break;

				case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/mobile/paygent_career.tpl":
					// 端末が mobile の場合
					$arrRet = sfSendPaygentCareer($arrData, $arrInput, $order_id, $this->transactionid, $this->career_type, $open_id);
					break;

				case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/sphone/paygent_career.tpl":
					// 端末が SmartPhone の場合
					$arrRet = sfSendPaygentCareer($arrData, $arrInput, $order_id, $this->transactionid, PC_MOBILE_TYPE_SMARTPHONE, $open_id);
					break;
			}

			$this->sendData_Career($arrRet);

			break;

        // 電子マネー時の決済完了画面遷移
        case 'emoney_commit':
        	$arrRet['result'] = "0";
        	$arrVal['quick_flg'] = "1";
        	$this->sendData($arrRet, $arrData['payment_total'], $arrData['order_id'], PAYGENT_EMONEY, $arrVal);
        	if (strlen($this->tpl_error)>0){
                $objCartSess->setValue('send_paygent','false', PAYGENT_CART_SESS_KEY);
            }
            break;
        // Yahoo!ウォレット時の決済完了画面遷移
        case 'yahoowallet_commit':
        	$arrRet['result'] = "0";
        	$arrVal['quick_flg'] = "1";
        	$this->sendData($arrRet, $arrData['payment_total'], $arrData['order_id'], PAYGENT_YAHOOWALLET, $arrVal);
        	if (strlen($this->tpl_error)>0){
                $objCartSess->setValue('send_paygent','false', PAYGENT_CART_SESS_KEY);
            }
            break;
        // Paidy時の決済完了画面遷移
        case 'paidy_commit':
            // 受注情報.汎用項目2のシリアライズ配列をアンシリアライズ
            $arrMemo = unserialize($arrData['memo02']);
            // PaidyCheckoutからコールバックデータを取得
            $arrMemo['callbackData'] = array(
                'amount' => $_POST['amount'],
                'currency' => $_POST['currency'],
                'created_at' => $_POST['created_at'],
                'id' => $_POST['id'],
                'status' => $_POST['status']
            );
            // PaidyCheckout結果更新
            $this->paidyCommit($arrData['order_id'], serialize($arrMemo));
            break;
        default:
            $objCartSess->setValue('send_paygent','false', PAYGENT_CART_SESS_KEY);
            break;
        }

        // 登録カード情報の取得
        if ($this->type == PAY_PAYGENT_CREDIT && $arrConfig['stock_card'] == 1 && $arrData['customer_id'] != 0) {
            $this->getStockCardData($arrData);
        }

        // セキュリティコード入力要・不要チェック
        if ($this->type == PAY_PAYGENT_CREDIT && $arrConfig['security_code'] == 1) {
            $this->security_code = 1;
        }

        //トークン決済関連の設定値を取得
        if ($this->type == PAY_PAYGENT_CREDIT) {
            $this->merchant_id = $arrConfig['merchant_id'];
            $this->token_pay = $arrConfig['token_pay'];
            $this->token_key = $arrConfig['token_key'];
            $this->paygent_token_connect_url = PAYGENT_TOKEN_CONNECT_URL;

            if ($arrConfig['token_env'] === "1") {
                $this->paygent_token_js_url = PAYGENT_TOKEN_JS_URL_LIVE;
            } else {
                $this->paygent_token_js_url = PAYGENT_TOKEN_JS_URL_SANDBOX;
            }

            $this->token_js = file_get_contents(PATH_JS_TOKEN);
        }
        // Paidy決済関連の設定値を取得
        if ($this->type == PAY_PAYGENT_PAIDY) {
            // PaidyCheckout パラメータ作成
            $this->buildPaidyCheckout($arrConfig,$arrData,$objPurchase);
            // Paidy JS読込
            $this->paidy_js = file_get_contents(PATH_JS_PAIDY);
			// Paidy決済のみCache設定を無効にする。
            // 決済完了画面でブラウザバックしたときにリロードさせるため。
            // リロードしてエラー画面を出さないと、再度PaidyCheckoutから同取引IDで決済を作成されてしまう恐れがある。
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }

        // 後払い 請求書の同梱フラグの取得
        if ($this->type == PAY_PAYGENT_LATER_PAYMENT) {
            $this->invoice_send_type= getInvoiceSendType($order_id);
        }
        
        // 表示準備
        $this->dispData($arrData['payment_id'], $arrData);
        $this->arrForm = $this->objFormParam->getFormParamList();
    }

    /* パラメータ情報の初期化 */
    function initParam($arrData, $mode) {
        $objConfig = new LC_Page_Mdl_Paygent_Config();
        $arrConfig = $objConfig->getConfig();

        // セキュリティコード入力要・不要チェック
        if ($this->type == PAY_PAYGENT_CREDIT && $arrConfig['security_code'] == 1) {
            $this->security_code = 1;
        }

        switch($this->type) {
        case PAY_PAYGENT_CREDIT:
            $mode = (isset($mode)) ? $mode : "";
            $stock = $this->objFormParam->getValue('stock');
            if ($mode == "deletecard" || $stock == 1) {
                $this->objFormParam->addParam("支払回数", "payment_class", INT_LEN, "n", array());
                $this->objFormParam->addParam("カード番号1", "card_no01", CREDIT_NO_LEN, "n", array());
                $this->objFormParam->addParam("カード番号2", "card_no02", CREDIT_NO_LEN, "n", array());
                $this->objFormParam->addParam("カード番号3", "card_no03", CREDIT_NO_LEN, "n", array());
                $this->objFormParam->addParam("カード番号4", "card_no04", CREDIT_NO_LEN, "n", array());
                if($this->security_code == 1) {
                    $this->objFormParam->addParam("セキュリティコード", "security_code", 4, "n", array());
                }
                $this->objFormParam->addParam("カード期限年", "card_year", 2, "n", array());
                $this->objFormParam->addParam("カード期限月", "card_month", 2, "n", array());
                $this->objFormParam->addParam("姓", "card_name01", 32, "KVa", array());
                $this->objFormParam->addParam("名", "card_name02", 32, "KVa", array());

            } elseif ($arrConfig['token_pay'] === "1") { //トークン決済時

                $this->objFormParam->addParam("支払回数", "payment_class", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
                $this->objFormParam->addParam("カード番号1", "card_no01", CREDIT_NO_LEN, "n", array());
                $this->objFormParam->addParam("カード番号2", "card_no02", CREDIT_NO_LEN, "n", array());
                $this->objFormParam->addParam("カード番号3", "card_no03", CREDIT_NO_LEN, "n", array());
                $this->objFormParam->addParam("カード番号4", "card_no04", CREDIT_NO_LEN, "n", array());
                if($this->security_code == 1) {
                    $this->objFormParam->addParam("セキュリティコード", "security_code", 4, "n", array());
                }
                $this->objFormParam->addParam("カード期限年", "card_year", 2, "n", array());
                $this->objFormParam->addParam("カード期限月", "card_month", 2, "n", array());
                $this->objFormParam->addParam("姓", "card_name01", 32, "KVa", array());
                $this->objFormParam->addParam("名", "card_name02", 32, "KVa", array());

                $this->objFormParam->addParam("トークン", "card_token", 30, "n", array("EXIST_CHECK"));
                $this->objFormParam->addParam("トークン", "card_token_stock", 30, "n", array());

            } else {
                $this->objFormParam->addParam("支払回数", "payment_class", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
                $this->objFormParam->addParam("カード番号1", "card_no01", CREDIT_NO_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
                $this->objFormParam->addParam("カード番号2", "card_no02", CREDIT_NO_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
                $this->objFormParam->addParam("カード番号3", "card_no03", CREDIT_NO_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
                $this->objFormParam->addParam("カード番号4", "card_no04", CREDIT_NO_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
                if($this->security_code == 1) {
                    $this->objFormParam->addParam("セキュリティコード", "security_code", 4, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
                }
                $this->objFormParam->addParam("カード期限年", "card_year", 2, "n", array("EXIST_CHECK", "NUM_COUNT_CHECK", "NUM_CHECK"));
                $this->objFormParam->addParam("カード期限月", "card_month", 2, "n", array("EXIST_CHECK", "NUM_COUNT_CHECK", "NUM_CHECK"));
                $this->objFormParam->addParam("姓", "card_name01", 32, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "ALPHA_CHECK"));
                $this->objFormParam->addParam("名", "card_name02", 32, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "ALPHA_CHECK"));
            }
            if ($mode == "deletecard") {
                $this->objFormParam->addParam("削除カード", "CardSeq", "", "n", array("EXIST_CHECK", "NUM_CHECK"));
            } elseif ($stock == 1) {
                $this->objFormParam->addParam("登録カード", "CardSeq", "", "n", array("EXIST_CHECK", "NUM_CHECK"));
                if($this->security_code == 1) {
                    if($arrConfig['token_pay'] === "1") { //トークン決済時
                        //セキュリティコードは渡ってこないのでバリデーションチェックは行わない
                        $this->objFormParam->addParam("セキュリティコード", "security_code", 4, "n", array());
                        $this->objFormParam->addParam("トークン", "card_token", 30, "n", array("EXIST_CHECK"));
                    } else {
                        $this->objFormParam->addParam("セキュリティコード", "security_code", 4, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
                    }
                }
            }
            break;
        case PAY_PAYGENT_CONVENI_NUM:
            $this->objFormParam->addParam("コンビニ", "cvs_company_id", STEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
            $this->objFormParam->addParam("利用者姓", "customer_family_name", PAYGENT_CONVENI_MTEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name01']);
            $this->objFormParam->addParam("利用者名", "customer_name", PAYGENT_CONVENI_MTEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name02']);
            $this->objFormParam->addParam("利用者姓カナ", "customer_family_name_kana", PAYGENT_CONVENI_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana01']);
            $this->objFormParam->addParam("利用者名カナ", "customer_name_kana", PAYGENT_CONVENI_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana02']);
            $this->objFormParam->addParam("お電話番号", "customer_tel", PAYGENT_TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"), $arrData['order_tel01'].$arrData['order_tel02'].$arrData['order_tel03']);
            break;
        case PAY_PAYGENT_CONVENI_CALL:
            $this->objFormParam->addParam("利用者姓", "customer_family_name", PAYGENT_CONVENI_MTEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name01']);
            $this->objFormParam->addParam("利用者名", "customer_name", PAYGENT_CONVENI_MTEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name02']);
            $this->objFormParam->addParam("利用者姓カナ", "customer_family_name_kana", PAYGENT_CONVENI_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana01']);
            $this->objFormParam->addParam("利用者名カナ", "customer_name_kana", PAYGENT_CONVENI_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana02']);
            $this->objFormParam->addParam("郵便番号1", "customer_zip01", ZIP01_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"), $arrData['order_zip01']);
            $this->objFormParam->addParam("郵便番号2", "customer_zip02", ZIP02_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"), $arrData['order_zip02']);
            $this->objFormParam->addParam("都道府県", "customer_pref", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"), $arrData['order_pref']);
            $this->objFormParam->addParam("住所1", "customer_addr01", STEXT_LEN, "KVA", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_addr01']);
            $this->objFormParam->addParam("住所2", "customer_addr02", STEXT_LEN, "KVA", array("SPTAB_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_addr02']);
            $this->objFormParam->addParam("電話区分", "customer_tel_division", STEXT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
            $this->objFormParam->addParam("お電話番号01", "customer_tel01", TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"), $arrData['order_tel01']);
            $this->objFormParam->addParam("お電話番号02", "customer_tel02", PAYGENT_S_TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"), $arrData['order_tel02']);
            $this->objFormParam->addParam("お電話番号03", "customer_tel03", PAYGENT_S_TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"), $arrData['order_tel03']);
            break;
        case PAY_PAYGENT_ATM:
            $this->objFormParam->addParam("利用者姓", "customer_family_name", PAYGENT_BANK_STEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name01']);
            $this->objFormParam->addParam("利用者名", "customer_name", PAYGENT_BANK_STEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name02']);
            $this->objFormParam->addParam("利用者姓カナ", "customer_family_name_kana", PAYGENT_BANK_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana01']);
            $this->objFormParam->addParam("利用者名カナ", "customer_name_kana", PAYGENT_BANK_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana02']);
            break;
        case PAY_PAYGENT_BANK:
            $this->objFormParam->addParam("利用者姓", "customer_family_name", PAYGENT_BANK_STEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name01']);
            $this->objFormParam->addParam("利用者名", "customer_name", PAYGENT_BANK_STEXT_LEN / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name02']);
            $this->objFormParam->addParam("利用者姓カナ", "customer_family_name_kana", PAYGENT_BANK_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana01']);
            $this->objFormParam->addParam("利用者名カナ", "customer_name_kana", PAYGENT_BANK_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana02']);
            break;
        case PAY_PAYGENT_CAREER:
            $this->objFormParam->addParam("キャリア決済選択", "career_type", STEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
            break;
        case PAY_PAYGENT_EMONEY:
        	$this->objFormParam->addParam("利用決済選択", "emoney_type", STEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        	break;
        case PAY_PAYGENT_YAHOOWALLET:
        	break;
        case PAY_PAYGENT_VIRTUAL_ACCOUNT:
            $this->objFormParam->addParam("利用者姓", "billing_family_name", PAYGENT_VIRTUAL_ACCOUNT_MTEXT_LEN / 2 / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name01']);
            $this->objFormParam->addParam("利用者名", "billing_name", PAYGENT_VIRTUAL_ACCOUNT_MTEXT_LEN / 2 / 2, "KVA", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name02']);
            $this->objFormParam->addParam("利用者姓カナ", "billing_family_name_kana", PAYGENT_VIRTUAL_ACCOUNT_STEXT_LEN / 2 / 2, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana01']);
            $this->objFormParam->addParam("利用者名カナ", "billing_name_kana", PAYGENT_VIRTUAL_ACCOUNT_STEXT_LEN / 2 / 2, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana02']);
            break;
        case PAY_PAYGENT_PAIDY:
            break;
        default:
            GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type, PAYGENT_LOG_PATH);
            break;
        }
   }

   /* パラメータ情報の初期化 */
    function initStockParam($arrParam) {
        $this->objFormParam->addParam("", "stock", "", "n", array(), "");
        $this->objFormParam->addParam("", "stock_new", "", "n", array());

        $this->objFormParam->setParam($arrParam);
    }

    /* 入力内容のチェック */
    function checkError() {
        $objErr->arrErr = $this->objFormParam->checkError();

        return $objErr->arrErr;
    }

    /**
     * モード設定
     */
    function getSetMode($mode, $quick_info, $type) {
        // 3Dセキュアの戻り
        if (isset($mode) && $mode == "credit_3d" &&
            isset($_GET['order_id']) && $_GET['order_id'] == $_SESSION['order_id']) {
            $setMode = '3d_secure';
        // モバイル：登録カードの削除
        } elseif (isset($_POST['deletecard'])) {
            $setMode = 'deletecard';
        // 電子マネー決済時
        }  else if (isset($mode) && ($mode == "emoney_commit" || $mode == "emoney_commit_cancel")) {
			$setMode = $mode;
        // その他
        } elseif (isset($mode)) {
            $setMode = $mode;
        // クイック決済時
        } elseif($quick_info['quick_flg'] == "1" || $type == PAY_PAYGENT_YAHOOWALLET) {
            $setMode = 'next';
        // Yahoo!ウォレット決済時
        } elseif($type == PAY_PAYGENT_YAHOOWALLET) {
            $setMode = 'next';
        // キャリア決済仮完了処理
        } elseif(isset($_GET['payment_id']) && isset($_GET['trading_id']) && isset($_GET['career_payment_id'])) {
            $setMode = 'career_auth';
        }
        return $setMode;
    }

    /**
     * キャリア決済の場合、エンドユーザーの操作によっては必ずしも完了画面に戻るとは限らないため
     * 他の決済とは異なり特にデータの更新は行わず、メールの送信を以って完了とする
     * データの更新はpaygent_batchにより代替する
     */
    function lfMoveCareerComplete() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = "COUNT(memo06) AS cnt";
        $table = "dtb_order";
        $where = "memo01 = ? AND memo06 = ? AND order_id = ?";
        $arrRet = $objQuery->select($col, $table, $where, array(MDL_PAYGENT_CODE, $_GET['payment_id'], $_GET['trading_id']));
        $count_payment_id = $arrRet[0]['cnt'];

        // 実際に注文した内容なら仮完了ページへ遷移させる
        if($count_payment_id > 0) {
        	//if ($this->tpl_mainpage == MODULE_REALDIR. MDL_PAYGENT_CODE. "/templates/mobile/paygent_career.tpl") {
        	//	$this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/mobile/paygent_career_comp.tpl";
        	//} else if ($this->tpl_mainpage == MODULE_REALDIR. MDL_PAYGENT_CODE. "/templates/sphone/paygent_career.tpl") {
        	//	$this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/sphone/paygent_career_comp.tpl";
        	//} else {
        	//	$this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/default/paygent_career_comp.tpl";
        	//}

            $objPurchase = new SC_Helper_Purchase_Ex();
            // 受注完了メールを送信する。
            $objPurchase->sendOrderMail($_GET['trading_id']);

            $objCartSess = new SC_CartSession_Ex();
            $objSiteSess = new SC_SiteSession_Ex();
            // セッションカート内の商品を削除する。
            $objCartSess->delAllProducts();
            // 注文一時IDを解除する。
            $objSiteSess->unsetUniqId();

            // 購入完了ページへリダイレクト
            SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
        } else {
            // $_GET内に不正な値が入っていた場合はエラーページを表示
            SC_Utils::sfDispSiteError("");
        }
    }

    /**
     * キャリア端末を取得する
     */
    function lfCheckCareer() {
        $MobileUserAgent = new SC_MobileUserAgent();

        $agent = $MobileUserAgent->getCarrier();

        switch($agent) {
        // docomo
        case 'docomo':
            $this->career_type = 1;
            break;
        // au
        case 'ezweb':
            $this->career_type = 2;
            break;
        // softbank
        case 'softbank':
            $this->career_type = 3;
            break;
        // デフォルトはdocomo
        default:
            $this->career_type = 1;
            break;
        }

    }

    /**
     * データ送信
     */
    function sendData($arrRet, $payment_total, $orderId, $telegram_kind, $sqlVal = array()) {
        // 成功
        if($arrRet['result'] === "0") {
            // 正常に登録されたことを記録
            $objSiteSess = new SC_SiteSession_Ex();
            $objSiteSess->setRegistFlag();
            //LC_Helper_Send_Payment::sendPaymentData(MDL_PAYGENT_CODE, $payment_total);

            $arrInitStatus = getInitStatus();
            $order_status = $arrInitStatus[$telegram_kind];

            if ($telegram_kind != PAYGENT_EMONEY && $telegram_kind != PAYGENT_YAHOOWALLET) {
            	$sqlVal['memo08'] = $telegram_kind;
            }
            $this->orderComplete($orderId, $sqlVal, $order_status);

        // 失敗
        } else {
            $this->tpl_error = "決済に失敗しました。". $arrRet['response'];

            if ($telegram_kind == PAYGENT_ATM || $telegram_kind == PAYGENT_CONVENI_NUM) {
                $this->tpl_error_detail = getCommonDetailMsg($arrRet['code'], $arrRet['response_detail'], $telegram_kind);
            }
        }
    }

    /**
     * データ送信（クレジット）
     */
    function sendData_Credit($arrRet, $arrData, $arrInput) {
        $this->quick_flg = "0";
        $this->cardSeq = "";
        $stock_new = $this->objFormParam->getValue('stock_new');
        $stock = $this->objFormParam->getValue('stock');
        // カード登録
        if ($stock_new == 1 && $stock != 1 &&
            ($arrRet['result'] === "0" || $arrRet['result'] === "7")) {
            $arrRetStock = sfSetPaygentCreditStock($arrData, $arrInput);
            if($arrRetStock[0]['result'] == "0") {
                $this->quick_flg = "1";
                $this->cardSeq = $arrRetStock[1]['customer_card_id'];
            }
        }
        // 成功（3Dセキュア未対応）
        if($arrRet['result'] === "0") {
            // 正常に登録されたことを記録
            $objSiteSess = new SC_SiteSession_Ex();
            $objSiteSess->setRegistFlag();
            //LC_Helper_Send_Payment::sendPaymentData(MDL_PAYGENT_CODE, $arrData['payment_total']);
            $sqlVal = array();
            $sqlVal['memo08'] = PAYGENT_CREDIT;
            if ($arrInput['stock'] == 1) {
                $this->quick_flg = "1";
            }
            $sqlVal['quick_flg'] = $this->quick_flg;
            list($payment_class, $split_count) = split("-", $arrInput['payment_class']);
            $quick_memo['payment_class'] = $payment_class;
            $quick_memo['split_count'] = $split_count;
            if ($arrInput['stock'] == 1) {
                $quick_memo['CardSeq'] = $arrInput['CardSeq'];
            } else if($this->quick_flg == "1") {
                $quick_memo['CardSeq'] = $this->cardSeq;
            }
            $sqlVal['quick_memo'] = serialize($quick_memo);

            $this->resetCardErrorCount($arrData['order_temp_id']);

            $this->orderComplete($arrData['order_id'], $sqlVal);

        // 成功（3Dセキュア対応）
        } elseif ($arrRet['result'] === "7") {
        	// 正常に登録されたことを記録
            $objSiteSess = new SC_SiteSession_Ex();
            $objSiteSess->setRegistFlag();
            // quick_flg,quick_memoを更新
            $objPurchase = new SC_Helper_Purchase_Ex();
            $sqlVal = array();
            if($this->quick_flg == "1" || $arrInput['stock'] == 1) {
                $sqlVal['quick_flg'] = "1";
            }
            list($payment_class, $split_count) = split("-", $arrInput['payment_class']);
            $quick_memo['payment_class'] = $payment_class;
            $quick_memo['split_count'] = $split_count;
            if ($arrInput['stock'] == 1) {
                $quick_memo['CardSeq'] = $arrInput['CardSeq'];
            } else if($this->quick_flg == "1") {
                $quick_memo['CardSeq'] = $this->cardSeq;
            }
            $sqlVal['quick_memo'] = serialize($quick_memo);
            $objPurchase->registerOrder($arrData['order_id'], $sqlVal);

            // カード会社画面へ遷移（ACS支払人認証要求HTMLを表示）
            print mb_convert_encoding($arrRet['out_acs_html'], CHAR_CODE, "Shift-JIS");
            exit;

        // 失敗
        } else {
            $this->tpl_error = "決済に失敗しました。". $arrRet['response'];
            $this->incrementCardErrorCount($arrData['order_temp_id']);
            if ($_SESSION['paygent_card_error_count'] >= CREDIT_AUTHORITY_RETRY_LIMIT) {
                $this->tpl_error .= "<br><br>" . CREDIT_AUTHORITY_LOCK_MESSAGE;
            }
        }
    }

    /**
     * データ送信（ネット銀行）
     */
    function sendData_Bank($arrRet, $order_id, $payment_total, $sqlVal = array()) {
        // 成功
        if(strlen($arrRet['asp_url']) > 0) {
            // 正常に登録されたことを記録
            $objSiteSess = new SC_SiteSession_Ex();
            $objSiteSess->setRegistFlag();
            //LC_Helper_Send_Payment::sendPaymentData(MDL_PAYGENT_CODE, $payment_total);

            $arrInitStatus = getInitStatus();
            $order_status = $arrInitStatus[PAYGENT_BANK];

            // 受注登録
            $sqlVal['memo08'] = PAYGENT_BANK;
            $this->orderComplete($order_id, $sqlVal, $order_status, PAY_PAYGENT_BANK);

            unset($_SESSION['paygent_order_id']);

            // ペイジェント決済画面に遷移
            header("Location: ". $arrRet['asp_url']);
            exit;

        // 失敗
        } else {
            $this->tpl_error = "決済に失敗しました。". $arrRet['response'];
            $this->tpl_error_detail = getCommonDetailMsg($arrRet['code'], $arrRet['response_detail'], PAYGENT_BANK);
        }
    }

	/**
	 * 携帯キャリア決済 電文送信後の処理。
	 *
	 * @param $arrRet 応答情報
	 */
	function sendData_Career($arrRet) {

		if ($arrRet['result'] === "0") {
			// 処理結果が "0"：正常 の場合

			$objSiteSess = new SC_SiteSession_Ex();
			// 正常に登録されたことをセッションに記録
			$objSiteSess->setRegistFlag();
			// 注文一時ID をセッションから解除
			$objSiteSess->unsetUniqId();

			// 画面の設定
			if (strlen($arrRet['redirect_url']) > 0) {
				// 応答情報にリダイレクトURL が含まれる場合
				header("Location: ". $arrRet['redirect_url']);
				exit;

			} else {
				// 応答情報にリダイレクトForm が含まれる場合
				switch ($this->tpl_mainpage) {
					case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/default/paygent_career.tpl":
						// 端末が PC の場合
						$this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/default/paygent_career_d.tpl";
						break;

					case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/mobile/paygent_career.tpl":
						// 端末が mobile の場合
						$this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/mobile/paygent_career_d.tpl";
						break;

					case MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/sphone/paygent_career.tpl":
						// 端末が SmartPhone の場合
						$this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/sphone/paygent_career_d.tpl";
						break;
				}

				$this->redirect_html = $arrRet['redirect_html'];
			}

		} else {
			// 処理結果が正常ではない場合
			$this->tpl_error = "決済に失敗しました。" . $arrRet['response'];
		}
	}

	/**
     * データ送信(電子マネー)
     */
    function sendData_EMoney($arrRet, $payment_total) {
    	// 成功
        if(strlen($arrRet['redirect_url']) > 0) {
        	$result = true;
            // 正常に登録されたことを記録
            $objSiteSess = new SC_SiteSession_Ex();
            $objSiteSess->setRegistFlag();

            // 決済画面に遷移
            header("Location: ". $arrRet['redirect_url']);
            exit;
        // 失敗
        } else {
            $this->tpl_error = "決済に失敗しました。". $arrRet['response'];
        }
    }

	/**
     * データ送信(Yahoo!ウォレット)
     */
    function sendData_Yahoowallet($arrRet) {
    	// 成功
        if(strlen($arrRet['redirect_url']) > 0) {
        	$result = true;
            // 正常に登録されたことを記録
            $objSiteSess = new SC_SiteSession_Ex();
            $objSiteSess->setRegistFlag();

            // 決済画面に遷移
            header("Location: ". $arrRet['redirect_url']);
            exit;
        // 失敗
        } else {
            $this->tpl_error = "決済に失敗しました。". $arrRet['response'];
        }
    }

    /**
     * データ送信(仮想口座)
     */
    function sendData_VirtualAccount($arrRet, $payment_total, $orderId, $telegram_kind, $sqlVal = array()) {
        if($arrRet['result'] === "0") {
            $this->sendData($arrRet, $payment_total, $orderId, $telegram_kind, $sqlVal);
        } else {
            if($arrRet['code'] === "8001") {
                $this->tpl_error = "現在、このお支払方法をご利用いただけません。<br>お手数ですが別のお支払方法をお選びください。";
            } else {
                $this->tpl_error = "決済に失敗しました。". $arrRet['response'];
                $this->tpl_error_detail = getCommonDetailMsg($arrRet['code'], $arrRet['response_detail'], PAYGENT_VIRTUAL_ACCOUNT);
            }
        }
    }

    /**
     * データ送信(後払い)
     */
    function sendData_LaterPayment($arrRet, $payment_total, $orderId, $telegram_kind, $sqlVal = array(), $invoice_send_type) {
        if($arrRet['result'] === "0") {
            if ($invoice_send_type == INVOICE_SEND_TYPE_INCLUDE) {
                $sqlVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT;
            } else {
                $sqlVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZED;
            }
        } else {
            if ($arrRet['code'] === "15007") {
                // 審査保留
                $arrRet['result'] = "0";
                $sqlVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_RESERVE;
            } else if ($arrRet['code'] === "15006") {
                // 審査NG
                $this->tpl_exam_error = "後払い決済の審査が通りませんでした。お手数ですが、別の決済手段をご検討ください。<br>";
                $this->tpl_exam_error .= "<br>";
                $this->tpl_exam_error .= "審査はジャックス・ペイメント・ソリューションズ株式会社が行っております。<br>";
                $this->tpl_exam_error .= "審査結果についてはお問い合わせいただいてもお答えすることが出来ません。";
            } else {
                $this->tpl_error_detail = getLaterPaymentDetailMsg($arrRet['code'], $arrRet['response_detail'], SETTLEMENT_MODULE);

                if ($this->tpl_error_detail != NO_MAPPING_MESSAGE) {

                    // 会員ログインチェック
                    $objCustomer = new SC_Customer_Ex();
                    if ($objCustomer->isLoginSuccess(true)) {
                        $this->tpl_login = '1';
                    }

                    $this->show_attention = '1';
                }
            }
        }
        $this->sendData($arrRet, $payment_total, $orderId, $telegram_kind, $sqlVal);
    }

    /**
     * Paidy決済完了処理
     */
    public function paidyCommit($orderId,$memo2) {
        // 受注完了処理 PaidyCheckout応答結果の更新。
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->update("dtb_order", array("memo02" => $memo2), "order_id = ?", array($orderId));

        // セッションに紐付く情報を削除する。(カートを空にする)
        $objCartSession = new SC_CartSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cleanupSession(null, $objCartSession, $objCustomer, $objCartSession->getKey());
        $_SESSION['paygent_order_id'] = $_SESSION['order_id'];

        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_SMARTPHONE) {
        	$this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/sphone/paygent_paidy_comp.tpl";
        } else {
        	$this->tpl_mainpage = MODULE_REALDIR . MDL_PAYGENT_CODE . "/templates/default/paygent_paidy_comp.tpl";
        }

        $this->arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
    }

    /**
     * 表示処理
     */
    function dispData($payment_id, $arrData = array()) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // 支払方法の説明画像を取得
        $arrRet = $objQuery->select("payment_method, payment_image, charge", "dtb_payment", "payment_id = ?", array($payment_id));
        $this->tpl_title = $arrRet[0]['payment_method'];
        $this->tpl_payment_method = $arrRet[0]['payment_method'];
        $this->tpl_payment_image = $arrRet[0]['payment_image'];

        // その他
        switch($this->type) {
        case PAY_PAYGENT_CREDIT:
            $this->arrPaymentClass = $this->getPaymentClass();
            // 月日配列
            $objDate = new SC_Date();
            $objDate->setEndYear(date("Y") + 20);
            $this->arrYear = $objDate->getZeroYear();
            $this->arrMonth = $objDate->getZeroMonth();
            break;
        case PAY_PAYGENT_CONVENI_NUM:
            // コンビニ配列
            $this->arrConvenience = getConvenience();
            break;
        case PAY_PAYGENT_CONVENI_CALL:
            // 都道府県配列
            $objMasterData = new SC_DB_MasterData_Ex();
            $this->arrPref = $objMasterData->getMasterData("mtb_pref", array("pref_id", "pref_name", "rank"));
            // 電話区分配列
            $this->arrTelDivision = getTelDivision();
            break;
        case PAY_PAYGENT_BANK:
            // ネットバンク配列
            $this->arrNetBank = getNetBank();
            break;
        case PAY_PAYGENT_CAREER:
            // キャリア配列
            $this->arrCareer = $this->getCareerClass();
            break;
        case PAY_PAYGENT_EMONEY:
        	// 電子マネー配列
        	$this->arrEmoney = $this->getEmoneyClass();
        	break;
        case PAY_PAYGENT_LATER_PAYMENT:
            // 後払い決済
            $this->tpl_charge = $arrRet[0]['charge'];
            $objPurchase = new SC_Helper_Purchase_Ex();
            $arrShippings = $objPurchase->getShippings($arrData['order_id'], false);
            if (count($arrShippings) > 1) {
                // 配送先が複数件ある場合は後払い決済不可
                $this->tpl_shipping_error = "後払い決済は複数配送先をご指定いただいた場合はご利用いただけません。<br>";
                $this->tpl_shipping_error .= "別の決済手段をご検討ください。";
            }
            break;
        case PAY_PAYGENT_PAIDY:
            // Paidy
            $objPurchase = new SC_Helper_Purchase_Ex();
            $arrShippings = $objPurchase->getShippings($arrData['order_id'], false);
            if (count($arrShippings) > 1) {
                // 配送先が複数件ある場合はPaidy決済不可
                $this->tpl_shipping_error = "Paidy決済は複数配送先をご指定いただいた場合はご利用いただけません。<br>";
                $this->tpl_shipping_error .= "別の決済手段をご検討ください。";
            }
            break;
        default:
            break;
        }
    }

    /**
     * 登録カード情報取得
     */
    function getStockCardData($arrData) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // 登録者の確認
        $ret = $objQuery->select("paygent_card", "dtb_customer", "customer_id = ?", array($arrData['customer_id']));
        // 登録者の情報取得
        if (count($ret) > 0) {
            $this->stock_flg = 1;
            if ($ret[0]['paygent_card'] == 1) {
                $arrRet = sfGetPaygentCreditStock($arrData);
                // 成功
                if ($arrRet[0]['result'] === "0") {
                    foreach ($arrRet as $key => $val) {
                        if ($key != 0) {
                            $this->arrCardInfo[] = array("CardSeq" => $val['customer_card_id'],
                                                         "CardNo" => $val['card_number'],
                                                         "Expire" => $val['card_valid_term'],
                                                         "HolderName" => $val['cardholder_name']);
                        }
                    }
                // 失敗
                } else {
                    $this->tpl_error = "登録カード情報の取得に失敗しました。". $arrRet[0]['response'];
                }
            }
        }
        $this->cnt_card = count($this->arrCardInfo);
        if ($this->cnt_card >= CARD_STOCK_MAX) $this->stock_flg = 0;
        if ($this->cnt_card > 0) $this->tpl_onload = "fnCngStock();";
    }

    /**
     * 決済処理が正常終了
     *
     */
    function orderComplete($order_id, $sqlval = array(), $order_status = ORDER_NEW, $type = PAY_PAYGENT_CREDIT) {
        $objPurchase = new SC_Helper_Purchase_Ex();

        // 受注ステータスを「決済処理中」から更新する。
        if ($order_status != ORDER_PENDING) { // iDでは更新しない
            $objPurchase->sfUpdateOrderStatus($order_id, $order_status, null, null, $sqlval);
        } else if (!empty($sqlval)) {
            $objPurchase->registerOrder($order_id, $sqlval);
        }

        // 受注完了メールを送信する。
        $objPurchase->sendOrderMail($order_id);

        // セッションに紐付く情報を削除する。(カートを空にする)
        $objCartSession = new SC_CartSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase->cleanupSession(null, $objCartSession, $objCustomer, $objCartSession->getKey());

        $_SESSION['paygent_order_id'] = $_SESSION['order_id'];

        if ($type != PAY_PAYGENT_BANK) {
            // 購入完了ページへリダイレクト
            SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
        }
    }

    /**
     * 有効な支払回数を取得する
     *
     */
    function getPaymentClass() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrPaymentClassAll = getPaymentClass();

        $arrRet = $objQuery->select("sub_data", "dtb_module", "module_code = 'mdl_paygent'");
        $arrSubData = unserialize($arrRet[0]['sub_data']);
        $arrPaymentDivision = $arrSubData['payment_division'];
        $arrRet = array();
        foreach ($arrPaymentDivision as $val) {
            switch($val) {
                // 一括払い
                case '10':
                    $arrRet['10'] = $arrPaymentClassAll['10'];
                    break;
                // 分割払い
                case '61':
                    $arrRet['61-2'] = $arrPaymentClassAll['61-2'];
                    $arrRet['61-3'] = $arrPaymentClassAll['61-3'];
                    $arrRet['61-6'] = $arrPaymentClassAll['61-6'];
                    $arrRet['61-10'] = $arrPaymentClassAll['61-10'];
                    $arrRet['61-15'] = $arrPaymentClassAll['61-15'];
                    $arrRet['61-20'] = $arrPaymentClassAll['61-20'];
                    break;
                // リボ払い
                case '80':
                    $arrRet['80'] = $arrPaymentClassAll['80'];
                    break;
                // ボーナス一括払い
                case '23':
                    $arrRet['23'] = $arrPaymentClassAll['23'];
                    break;
            }
        }

        return $arrRet;
    }

	/**
	 * 利用決済として設定されている、携帯キャリア決済を取得する。
	 */
	 function getCareerClass() {
	 	// 携帯キャリア決済を全て取得
		$arrCareerClassAll = getCareerPaymentCategory();

		// モジュール情報テーブル（dtb_module）から、設定情報を取得
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$arrRet = $objQuery->select("sub_data", "dtb_module", "module_code = 'mdl_paygent'");
		$arrSubData = unserialize($arrRet[0]['sub_data']);
		// 携帯キャリア決済設定 利用決済
		$arrCareerDivision = $arrSubData['career_division'];

		$arrRet = array();
		foreach ($arrCareerDivision as $val) {
			switch ($val) {
				// ドコモ払い
				case CAREER_MOBILE_TYPE_DOCOMO:
					$arrRet[CAREER_MOBILE_TYPE_DOCOMO] = $arrCareerClassAll[CAREER_MOBILE_TYPE_DOCOMO];
					break;
				// auかんたん決済
				case CAREER_MOBILE_TYPE_AU:
					$arrRet[CAREER_MOBILE_TYPE_AU] = $arrCareerClassAll[CAREER_MOBILE_TYPE_AU];
					break;
				// ソフトバンク
				case CAREER_MOBILE_TYPE_SOFTBANK:
				    //フィーチャーフォンかスマホ(～2.11.1)の場合は短縮形の文言を使用
				    if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE ||
				        (SC_Display_Ex::detectDevice() == DEVICE_TYPE_SMARTPHONE && (ECCUBE_VERSION == "2.11.0" || ECCUBE_VERSION == "2.11.1"))) {
				        $arrRet[CAREER_MOBILE_TYPE_SOFTBANK] = CAREER_MOBILE_TYPE_SOFTBANK_SHORT;
				    } else {
				        $arrRet[CAREER_MOBILE_TYPE_SOFTBANK] = $arrCareerClassAll[CAREER_MOBILE_TYPE_SOFTBANK];
				    }
					break;
			}
		}
		return $arrRet;
	}

	/**
     * 有効な電子マネー決済の決済方法を取得する
     *
     */
    function getEmoneyClass() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrEmoneyClassAll = getEmoneyPaymentCategory();

        $arrRet = $objQuery->select("sub_data", "dtb_module", "module_code = 'mdl_paygent'");
        $arrSubData = unserialize($arrRet[0]['sub_data']);
        $arrEmoneyDivision = $arrSubData['emoney_division'];
        $arrRet = array();
        foreach ($arrEmoneyDivision as $val) {
            switch($val) {
            // WebMoney
            case '1':
                $arrRet['1'] = $arrEmoneyClassAll['1'];
                break;
            }
        }
        return $arrRet;
    }

    /*
     * オーソリ失敗回数をカウントアップする。
     */
    public function incrementCardErrorCount($order_temp_id) {
        if (isset($_SESSION['paygent_card_error_count'])) {
            $_SESSION['paygent_card_error_count']++;
        } else {
            $_SESSION['paygent_card_error_count'] = 1;
        }

        // オーソリ失敗回数が上限に達した場合はロックの有効期限を設定する。
        if ($_SESSION['paygent_card_error_count'] >= CREDIT_AUTHORITY_RETRY_LIMIT) {
            $_SESSION['paygent_card_error_lock_expire'] = time() + CREDIT_AUTHORITY_LOCK_EXPIRE;
        }

        $this->clearCardErrorCount($order_temp_id);
    }

    /*
     * オーソリ失敗回数をリセットする。
     */
    public function resetCardErrorCount($order_temp_id){
        unset($_SESSION['paygent_card_error_count']);
        unset($_SESSION['paygent_card_error_lock_expire']);
        $this->clearCardErrorCount($order_temp_id);
    }

    /*
     * 一時受注テーブルのsessionカラムに含まれるオーソリ失敗回数とロック有効期限を削除する。
     */
    public function clearCardErrorCount($order_temp_id){
        // 受注一時情報 取得
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrOrderTemp = $objQuery->select("session", "dtb_order_temp", "order_temp_id = ?", array($order_temp_id));

        // dtb_order_temp.sessionに格納されているシリアライズ配列を配列に変換
        $arrSession = unserialize($arrOrderTemp[0]['session']);

        // オーソリ失敗回数と失敗ロック有効期限を削除する。
        // これをしないとrollbackOrder内でdtb_order_temp.sessionを$_SESSIONに上書きする時にエラー回数がリセットされる。
        if (isset($arrSession['paygent_card_error_count'])) {
            unset($arrSession['paygent_card_error_count']);
        }
        if (isset($arrSession['paygent_card_error_lock_expire'])) {
            unset($arrSession['paygent_card_error_lock_expire']);
        }

        $arrVal['session'] = serialize($arrSession);

        // 受注一時情報テーブル（dtb_order_temp）の更新
        $objQuery->update("dtb_order_temp", $arrVal, "order_temp_id = ?", array($order_temp_id));
    }

    /**
     * 配送先情報を取得する。
     * @param $order_id
     * @return $arrPaidy['shipping_address']
     */
    public function convertShippingAddress($order_id)
    {
        $arrPaidy = array();
        $arrShipping = SC_Mdl_Quick_Helper::getShippingAddress($order_id);
        $objMasterData = new SC_DB_MasterData_Ex();
        $this->arrPref = $objMasterData->getMasterData("mtb_pref", array("pref_id", "pref_name", "rank"));
        $arrPaidy = array();
        $arrPaidy['line1'] = '';
        $arrPaidy['line2'] = $arrShipping['addr02'];
        $arrPaidy['city'] = $arrShipping['addr01'];
        $arrPaidy['state'] = $this->arrPref[$arrShipping['pref']];
        $arrPaidy['zip'] = $arrShipping['zip01'].$arrShipping['zip02'];

        return $arrPaidy;
    }

    /**
     * 注文情報を取得する。
     * @param $arrOrder
     * @param $arrOrderDetails
     * @return $arrPaidy['order']
     */
    public function convertOrder($arrOrder, $arrOrderDetails)
    {
        $arrPaidy = array();
        $arrPaidy['items'] = array();
        foreach ($arrOrderDetails as $arrOrderDetail) {
            $arrItem = array();
            $arrItem['id'] = $arrOrderDetail['product_code'];
            $arrItem['quantity'] = $arrOrderDetail['quantity'];
            $arrItem['title'] = $arrOrderDetail['product_name'];
            $arrItem['unit_price'] = $arrOrderDetail['price'];
            $arrItem['description'] = '';
            $arrPaidy['items'][] = $arrItem;
        }
        // 手数料
        if ($arrOrder['charge']) {
            $arrItem = array();
            $arrItem['id'] = '';
            $arrItem['quantity'] = 1;
            $arrItem['title'] = '手数料';
            $arrItem['unit_price'] = $arrOrder['charge'];
            $arrItem['description'] = '';
            $arrPaidy['items'][] = $arrItem;
        }
        // ポイント値引き
        if ($arrOrder['use_point']) {
            $arrItem = array();
            $arrItem['id'] = '';
            $arrItem['quantity'] = 1;
            $arrItem['title'] = 'ポイント値引き';
            $arrItem['unit_price'] = $arrOrder['use_point'] * POINT_VALUE * -1;
            $arrItem['description'] = '';
            $arrPaidy['items'][] = $arrItem;
        }
        $arrPaidy['order_ref'] = $arrOrder['order_id'];
        $arrPaidy['shipping'] = $arrOrder['deliv_fee'];
        $arrPaidy['tax'] = $arrOrder['tax'];
        return $arrPaidy;
    }

    /**
     * 購入者_顧客情報を取得する。
     * @param  array $arrOrder
     * @return $arrPaidy['buyer']
     */
    public function convertBuyer($arrOrder)
    {
        $arrPaidy = array();
        $arrPaidy['email'] = $arrOrder['order_email'];
        $arrPaidy['name1'] = $arrOrder['order_name01'] . ' ' . $arrOrder['order_name02'];
        $arrPaidy['name2'] = $arrOrder['order_kana01'] . ' ' . $arrOrder['order_kana02'];
        $arrPaidy['phone'] = $arrOrder['order_tel01'] . $arrOrder['order_tel02'] . $arrOrder['order_tel03'];
        if ($arrOrder['order_birth']) {
            $time = strtotime($arrOrder['order_birth']);
            $arrPaidy['dob'] = date('Y-m-d', $time);
        } else {
            $arrPaidy['dob'] = "";
        }
        return $arrPaidy;
    }

    /**
     * 購入者_購入情報を取得する。
     * @param  array $arrOrder
     * @return $arrPaidy['buyer_data']
     */
    public function convertBuyerData($arrOrder)
    {
        $arrPaidy = array();

        // 会員購入の場合
        if (0 < $arrOrder['customer_id']) {
            // PaidyのペイメントID取得
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objPayment =  $objQuery->getRow('payment_id ', 'dtb_payment ', 'memo03 = ?' , array(PAY_PAYGENT_PAIDY));
            $paymentId = $objPayment['payment_id'];

            // アカウント作成経過日数
            $customerCreateDate = $objQuery->get('create_date', 'dtb_customer', 'customer_id = ?', array($arrOrder['customer_id']));
            $arrPaidy['age'] = $this->getDayDiff($customerCreateDate);

            // 注文総数
            $noPaidyOrder = $objQuery->getRow('count(order_id) as orderCount,sum(payment_total) as orderSumPaymentTotal ', 'dtb_order', 'customer_id = ? AND del_flg = 0 AND status IN (5 , 6) AND payment_id <> ?', array($arrOrder['customer_id'],$paymentId));
            $arrPaidy['order_count'] = $noPaidyOrder['ordercount'];
            $arrPaidy['ltv'] = $noPaidyOrder['ordersumpaymenttotal'];

            // 最後に注文した金額(円)
            $lastPaidyOrder = $objQuery->getRow('payment_total , create_date ', 'dtb_order', 'customer_id = ? AND del_flg = 0 AND status <> 7 AND payment_id <> ? order by create_date desc limit 1', array($arrOrder['customer_id'],$paymentId));
            $arrPaidy['last_order_amount'] = $lastPaidyOrder['payment_total'];
            $arrPaidy['last_order_at']     = $this->getDayDiff($lastPaidyOrder['create_date']);
        // ゲスト購入の場合
        } else {
            $arrPaidy['age'] = 0;
            $arrPaidy['order_count'] = 0;
            $arrPaidy['ltv'] = 0;
            $arrPaidy['last_order_amount'] = 0;
            $arrPaidy['last_order_at'] = 0;
        }

        return $arrPaidy;
    }

    /**
     * getDayDiff
     * @param string $from_date
     * @return システム日付からの日数差
     */
    private function getDayDiff($from_date)
    {
        if (!$from_date) {
            return 0;
        }

        $from_date_time = strtotime($from_date);
        $to_date_time = strtotime(date('Y-m-d'));

        $time_diff = $to_date_time - $from_date_time;

        if ($time_diff <= 0) {
            return 0;
        } else {
            return (int) ceil($time_diff / (60 * 60 * 24));
        }
    }

   /**
    * PaidyCheckout パラメータ作成
    * @param $arrConfig
    * @param $arrData
    * @param $objPurchase
    */
    public function buildPaidyCheckout($arrConfig,$arrData,$objPurchase){
        // Paidy公開鍵
        $this->api_key = $arrConfig['api_key'];
        // PaidyロゴURL
        $this->logo_url = $arrConfig['logo_url'];

        // 受注明細情報の取得
        $arrDataDetail = $objPurchase->getOrderDetail($arrData['order_id'],false);

        // Paidy注文データ
        $arrPaidy = array();
        $arrPaidy['amount'] = $arrData['payment_total'];
        $arrPaidy['currency'] = 'JPY';
        $arrPaidy['store_name'] = $arrConfig['paidy_store_name'];
        $arrPaidy['buyer'] = $this->convertBuyer($arrData);
        $arrPaidy['buyer_data'] = $this->convertBuyerData($arrData);
        $arrPaidy['order'] = $this->convertOrder($arrData, $arrDataDetail);
        $arrPaidy['shipping_address'] = $this->convertShippingAddress($arrData['order_id']);
        $this->json_paidy = SC_Utils_Ex::jsonEncode($arrPaidy);
    }
}
?>
