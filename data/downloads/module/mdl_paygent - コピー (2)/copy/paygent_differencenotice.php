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
    $PAYGENT_BATCH_DIR = realpath(dirname( __FILE__));
	require_once ("../../../../require.php");
	require_once (MODULE_REALDIR . 'mdl_paygent/LC_Page_Mdl_Paygent_Config.php');
	ob_end_clean();

	line();
    logTrace("BEGIN PAYGENT ACCEPTED THE REQUEST!");
    line();

	requestMerchant();

	line();
    logTrace("END PAYGENT ACCEPTED THE REQUEST!");
    line();

	/**
	 * ペイジェントサーバーから送られてくるリクエストを処理して、ステータスの変更処理を行う。<br>
	 */
	function requestMerchant() {

		$arrParam = array();

		$objConfig = new LC_Page_Mdl_Paygent_Config();
		$arrConfig = $objConfig->getConfig();

		$issetFlg = checkError($arrConfig);

		if ($issetFlg) {
			// ペイジェントから送られてくるリクエストパラメータを取得
			$arrParam['trading_id'] = $_POST['trading_id'];
			$arrParam['payment_id'] = $_POST['payment_id'];
			$arrParam['payment_status'] = $_POST['payment_status'];
			$arrParam['payment_type'] = $_POST['payment_type'];
			$arrParam['payment_notice_id'] = $_POST['payment_notice_id'];
			$arrParam['payment_date'] = $_POST['payment_date'];
			$arrParam['clear_detail'] = $_POST['clear_detail'];
			$arrParam['payment_amount'] = $_POST['payment_amount'];
			$arrParam['paidy_payment_id'] = $_POST['paidy_payment_id'];

			// 取得したパラメータをログに出力する
	        foreach ($arrParam as $key => $val) {
	            $convertedKey = mb_convert_encoding($key, CHAR_CODE, "SJIS-win");
	            $convertedVal = mb_convert_encoding($val, CHAR_CODE, "SJIS-win");
	            logTrace("$convertedKey => $convertedVal");
	        }

			// 入金ステータスを更新する
			sfUpdatePaygentOrder(new SC_Query(), $arrParam, $arrConfig);
		}
	}

	/**
	 * 関数名：checkError
	 * 処理内容：チェック処理を行う。</br>
	 * エラーの場合は、ログにエラー内容を出力する。
	 *
	 * @param $arrConfig
	 * @return $issetFlg
	 */
	function checkError($arrConfig) {
		$issetFlg = true;
		// 送られてくるパラメータがnullでないかの確認

		if (empty($_POST['payment_notice_id'])) {
			// nullの場合はログに出力する
			logTrace("決済種別ID -> ". $_POST['payment_notice_id'] ."に値がありません。");
			$issetFlg = false;
		}
		if (empty($_POST['payment_id'])) {
			// nullの場合はログに出力する
			logTrace("決済ID -> ". $_POST['payment_id'] ."に値がありません。");
			$issetFlg = false;
		}
		if (empty($_POST['trading_id'])) {
		    if ($_POST['payment_type'] != PAYMENT_TYPE_VIRTUAL_ACCOUNT) {
				// nullの場合はログに出力する
				logTrace("マーチャント取引ID -> ". $_POST['trading_id'] ."に値がありません。");
				$issetFlg = false;
		    }
		}
		if (empty($_POST['payment_type'])) {
			// nullの場合はログに出力する
			logTrace("決済種別CD -> ". $_POST['payment_type'] ."に値がありません。");
			$issetFlg = false;
		}
		if (empty($_POST['payment_status'])) {
			// nullの場合はログに出力する
			logTrace("決済ステータス -> ". $_POST['payment_status'] ."に値がありません。");
			$issetFlg = false;
		}
		if (empty($_POST['payment_amount'])) {
		    if ($_POST['payment_type'] != PAYMENT_TYPE_VIRTUAL_ACCOUNT) {
				// nullの場合はログに出力する
				logTrace("決済金額 -> ". $_POST['payment_amount'] ."に値がありません。");
				$issetFlg = false;
		    }
		}

		// 型チェック
		if (! empty($_POST['payment_date'])) {
			if (! preg_match('/^\d{14}$/', $_POST['payment_date']) || ! strtotime($_POST['payment_date'])) {
				// 支払日時が null ではなく、yyyyMMddHHmmss の日付として不正な場合はログに出力する
				logTrace("支払日時 -> ". $_POST['payment_date'] ."の値が不正です。");
				$issetFlg = false;
			}
		}

		// 存在チェック
		$arrOrder = getOrderInfo($_POST['trading_id']);
		if ($_POST['payment_type'] == PAYMENT_TYPE_VIRTUAL_ACCOUNT) {
			// 仮想口座決済の場合
			// 存在チェックをスキップして後続の処理でメール送信する
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_PAIDY) {
			// Paidyの場合
			// 存在チェックをスキップして後続の決済金額照合処理を行う
		} else if (($_POST['payment_type'] == PAYMENT_TYPE_CREDIT && $_POST['payment_status'] == STATUS_PRE_REGISTRATION) || ($_POST['payment_type'] == PAYMENT_TYPE_CREDIT && $_POST['payment_status'] == STATUS_NG_AUTHORITY)) {
			// クレジット決済申込済かクレジット決済オーソリNGの場合
			// 存在チェックとステータス更新処理をスキップする。モジュール型と混合型で挙動を合わせるため。
			$issetFlg = false;
		} else if (count($arrOrder[0]) <= 0) {
			// 存在しなかった場合
	    	logTrace("マーチャント取引ID ->" . $_POST['trading_id'] . "が一致するデータは受注情報に存在しません。");
	    	$issetFlg = false;
		} else {
	    	// 銀行ネット決済時
	    	if ($arrOrder[0]['memo08'] == PAYGENT_BANK) {
	    		if ($arrOrder[0]['payment_total'] != $_POST['payment_amount'] ) {
					logTrace("マーチャント取引ID ->" . $_POST['trading_id'] .
						"決済金額 -> ". $_POST['payment_amount'] . "が一致するデータは受注情報に存在しません。");
					$issetFlg = false;
				}
	    	} else {
				if ($arrConfig['settlement_division'] == SETTLEMENT_MODULE && ($arrOrder[0]['payment_total'] != $_POST['payment_amount'] || $arrOrder[0]['memo06'] != $_POST['payment_id'])) {
					// オーソリ変更時や売上変更時は決済金額や決済IDが変わっていて当然なのでログを出力しない。
					if ($arrOrder[0]['memo09'] == PAYGENT_CREDIT || $arrOrder[0]['memo09'] == PAYGENT_CARD_COMMIT_REVICE || $arrOrder[0]['memo09'] == PAYGENT_CAREER_COMMIT_REVICE
						|| $arrOrder[0]['memo09'] == PAYGENT_EMONEY_COMMIT_REVICE) {
						$issetFlg = false;
					} else {
						logTrace("決済ID -> " . $_POST['payment_id'] . "マーチャント取引ID ->" . $_POST['trading_id'] .
							"決済金額 -> ". $_POST['payment_amount'] . "が一致するデータは受注情報に存在しません。");
						$issetFlg = false;
					}
				}
	    	}
		}

		// 複合チェック
		if ($_POST['payment_type'] == PAYMENT_TYPE_ATM) {
			if ($_POST['payment_status'] == STATUS_PRE_REGISTRATION
			    || $_POST['payment_status'] == STATUS_PAYMENT_EXPIRED
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED) {
			    return $issetFlg;
			} else {
				logTrace("決済種別CD ->". $_POST['payment_type'] ."で決済ステータス -> ".$_POST['payment_status'] ."は存在しません。");
				$issetFlg = false;
			}
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_CREDIT) {
			if ($_POST['payment_status'] == STATUS_PRE_REGISTRATION
			    || $_POST['payment_status'] == STATUS_NG_AUTHORITY
			    || $_POST['payment_status'] == STATUS_3DSECURE_INTERRUPTION
			    || $_POST['payment_status'] == STATUS_3DSECURE_AUTHORIZE
			    || $_POST['payment_status'] == STATUS_AUTHORITY_OK
			    || $_POST['payment_status'] == STATUS_CLEAR_REQUESTING
			    || $_POST['payment_status'] == STATUS_AUTHORITY_CANCELING
			    || $_POST['payment_status'] == STATUS_AUTHORITY_CANCELED
			    || $_POST['payment_status'] == STATUS_AUTHORITY_EXPIRED
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED_EXPIRATION_CANCELLATION_SALES
			    || $_POST['payment_status'] == STATUS_PRE_SALES_CANCELING
			    || $_POST['payment_status'] == STATUS_PRE_SALES_CANCEL_ARRANGING
			    || $_POST['payment_status'] == STATUS_PRE_SALES_CANCELLATION) {
			    return $issetFlg;
			} else {
				logTrace("決済種別CD ->". $_POST['payment_type'] ."で決済ステータス -> ".$_POST['payment_status'] ."は存在しません。");
				$issetFlg = false;
			}
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_CONVENI_NUM) {
			if ($_POST['payment_status'] == STATUS_PRE_REGISTRATION
			    || $_POST['payment_status'] == STATUS_PAYMENT_EXPIRED
			    || $_POST['payment_status'] == STATUS_AUTHORITY_CANCELED
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED
			    || $_POST['payment_status'] == STATUS_PRELIMINARY_PRE_DETECTION
			    || $_POST['payment_status'] == STATUS_PRELIMINARY_CANCELLATION) {
			    return $issetFlg;
			} else {
				logTrace("決済種別CD ->". $_POST['payment_type'] ."で決済ステータス -> ".$_POST['payment_status'] ."は存在しません。");
				$issetFlg = false;
			}
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_BANK) {
			if ($_POST['payment_status'] == STATUS_PRE_REGISTRATION
			    || $_POST['payment_status'] == STATUS_REGISTRATION_SUSPENDED
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED) {
			    return $issetFlg;
			} else {
				logTrace("決済種別CD ->". $_POST['payment_type'] ."で決済ステータス -> ".$_POST['payment_status'] ."は存在しません。");
				$issetFlg = false;
			}
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_CAREER) {
			if ($_POST['payment_status'] == STATUS_PRE_REGISTRATION
			    || $_POST['payment_status'] == STATUS_REGISTRATION_SUSPENDED
			    || $_POST['payment_status'] == STATUS_AUTHORITY_OK
			    || $_POST['payment_status'] == STATUS_AUTHORITY_COMPLETED
			    || $_POST['payment_status'] == STATUS_CLEAR_REQUESTING
			    || $_POST['payment_status'] == STATUS_AUTHORITY_CANCELED
			    || $_POST['payment_status'] == STATUS_AUTHORITY_EXPIRED
			    || $_POST['payment_status'] == CORRECT_REQUESTING
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED_EXPIRATION_CANCELLATION_SALES
			    || $_POST['payment_status'] == STATUS_PRE_SALES_CANCELING
			    || $_POST['payment_status'] == STATUS_COMPLETE_CLEARED
			    || $_POST['payment_status'] == STATUS_PRE_SALES_CANCELLATION
			    || $_POST['payment_status'] == STATUS_COMPLETE_CANCELLATION) {
			    return $issetFlg;
			} else {
				logTrace("決済種別CD ->". $_POST['payment_type'] ."で決済ステータス -> ".$_POST['payment_status'] ."は存在しません。");
				$issetFlg = false;
			}
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_EMONEY) {
			if ($_POST['payment_status'] == STATUS_PRE_REGISTRATION
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED_EXPIRATION_CANCELLATION_SALES
			    || $_POST['payment_status'] == STATUS_PRE_SALES_CANCELLATION) {
			    return $issetFlg;
			} else {
				logTrace("決済種別CD ->". $_POST['payment_type'] ."で決済ステータス -> ".$_POST['payment_status'] ."は存在しません。");
				$issetFlg = false;
			}
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_VIRTUAL_ACCOUNT) {
			if ($_POST['payment_status'] == STATUS_PRE_REGISTRATION
			    || $_POST['payment_status'] == STATUS_PAYMENT_EXPIRED
			    || $_POST['payment_status'] == STATUS_PAYMENT_INVALIDITY_NO_CLEAR
			    || $_POST['payment_status'] == STATUS_PRE_CLEARED) {
			    return $issetFlg;
			} else {
			    logTrace("決済種別CD ->" . $_POST['payment_type'] . "で決済ステータス -> " . $_POST['payment_status'] . "は存在しません。");
			    $issetFlg = false;
			}
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_LATER_PAYMENT) {
			if ($_POST['payment_status'] == STATUS_PRE_REGISTRATION
			    || $_POST['payment_status'] == STATUS_AUTHORIZE_NG
			    || $_POST['payment_status'] == STATUS_AUTHORIZE_RESERVE
			    || $_POST['payment_status'] == STATUS_AUTHORIZED_BEFORE_PRINT
			    || $_POST['payment_status'] == STATUS_AUTHORIZED
			    || $_POST['payment_status'] == STATUS_AUTHORIZE_CANCEL
			    || $_POST['payment_status'] == STATUS_AUTHORIZE_EXPIRE
			    || $_POST['payment_status'] == STATUS_CLEAR_REQ_FIN
			    || $_POST['payment_status'] == STATUS_SALES_RESERVE
			    || $_POST['payment_status'] == STATUS_CLEAR
			    || $_POST['payment_status'] == STATUS_CLEAR_SALES_CANCEL_INVALIDITY
			    || $_POST['payment_status'] == STATUS_SALES_CANCEL) {
			    return $issetFlg;
			} else {
				logTrace("決済種別CD ->" . $_POST['payment_type'] . "で決済ステータス -> " . $_POST['payment_status'] . "は存在しません。");
				$issetFlg = false;
			}
		} else if ($_POST['payment_type'] == PAYMENT_TYPE_PAIDY) {
		    if ($_POST['payment_status'] == STATUS_AUTHORIZED
		        || $_POST['payment_status'] == STATUS_AUTHORIZE_CANCEL
		        || $_POST['payment_status'] == STATUS_AUTHORIZE_EXPIRE
		        || $_POST['payment_status'] == STATUS_CLEAR
		        || $_POST['payment_status'] == STATUS_CLEAR_SALES_CANCEL_INVALIDITY
		        || $_POST['payment_status'] == STATUS_SALES_CANCEL) {
		            return $issetFlg;
		        } else {
		            logTrace("決済種別CD ->" . $_POST['payment_type'] . "で決済ステータス -> " . $_POST['payment_status'] . "は存在しません。");
		            $issetFlg = false;
		        }
		} else {
			logTrace("決済種別CD ->". $_POST['payment_type'] ."で決済ステータス -> ".$_POST['payment_status'] ."は存在しません。");
			$issetFlg = false;
		}
		return $issetFlg;
	}

	/**
	 * 罫線を出力する.
	 */
	function line() {
	    $log = "-----------------------------------------------------------";
	    GC_Utils::gfPrintLog($log, PAYGENT_LOG_PATH);
	    ln();
	}

	/**
	 * 改行(LF)を出力する.
	 */
	function ln() {
	    $log = "\n";
	    if (defined(PHP_EOL)) {
	        $log = PHP_EOL;
	    }
	    GC_Utils::gfPrintLog($log, PAYGENT_LOG_PATH);
	}

	/**
	 * ログのプレフィクスを出力する.
	 */
	function logPrefix() {
	    $log = "[";
	    $log .= date("Y-m-d H:i:s");
	    $log .= "] ";
	    GC_Utils::gfPrintLog($log, PAYGENT_LOG_PATH);
	}

	/**
	 * トレースログを出力する.
	 */
	function logTrace($log) {
	    logPrefix();
	    GC_Utils::gfPrintLog($log, PAYGENT_LOG_PATH);
	    ln();
	}
?>
<html>
	<head></head>
	<body>
		result = 0
	</body>
</html>