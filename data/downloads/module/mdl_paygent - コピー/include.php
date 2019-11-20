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
$paygent_credit_php_dir = realpath(dirname( __FILE__));

// include_pathにmdl_paygentのパスを含める
ini_set('include_path', $paygent_credit_php_dir . PATH_SEPARATOR . ini_get('include_path'));

// paygentモジュールの読込
$paygent_module_dir = "jp/co/ks/merchanttool/connectmodule/";
if(file_exists($paygent_credit_php_dir . "/" . $paygent_module_dir)) {
    include_once($paygent_module_dir . "system/PaygentB2BModule.php");
}

// ペイジェント決済のモジュールコード
define("MDL_PAYGENT_CODE", "mdl_paygent");

// ログファイルパス（EC-CUBEサイド）
define("PAYGENT_LOG_PATH_LINK", DATA_REALDIR . "logs/paygent.log");
define("PAYGENT_LOG_PATH", DATA_REALDIR . "logs/paygent_cube.log");

// 決済種別
define("SETTLEMENT_LINK", 1);
define("SETTLEMENT_MODULE", 2);
define("SETTLEMENT_MIX", 3);

// 請求書送付方法
define("INVOICE_SEND_TYPE_SEPARATE", 2);
define("INVOICE_SEND_TYPE_INCLUDE", 3);

function getSettlement() {
    return array(
        SETTLEMENT_MODULE => 'モジュール型',
        SETTLEMENT_LINK => 'リンク型',
        SETTLEMENT_MIX => '混合型',
        );
}

// 支払いの種類
define("PAY_PAYGENT_CREDIT", "1");
define("PAY_PAYGENT_CONVENI_NUM", "2");
define("PAY_PAYGENT_CONVENI_CALL", "3");
define("PAY_PAYGENT_ATM", "4");
define("PAY_PAYGENT_BANK", "5");
define("PAY_PAYGENT_CAREER", "6");
define("PAY_PAYGENT_EMONEY", "7");
define("PAY_PAYGENT_YAHOOWALLET", "8");
define("PAY_PAYGENT_LINK", "50");
define("PAY_PAYGENT_VIRTUAL_ACCOUNT", "9");
define("PAY_PAYGENT_LATER_PAYMENT", "10");
define("PAY_PAYGENT_PAIDY", "11");

function getPayment() {
    return array(
        PAY_PAYGENT_CREDIT => 'クレジット',
        PAY_PAYGENT_CONVENI_NUM => 'コンビニ(番号方式)',
        PAY_PAYGENT_ATM => 'ATM決済',
        PAY_PAYGENT_BANK => '銀行ネット',
        PAY_PAYGENT_CAREER => '携帯キャリア',
        PAY_PAYGENT_EMONEY => '電子マネー',
        PAY_PAYGENT_VIRTUAL_ACCOUNT => '仮想口座',
        PAY_PAYGENT_LATER_PAYMENT => '後払い',
        PAY_PAYGENT_PAIDY => 'Paidy'
    );
}

function getLinkPayment() {
    return array(
        PAY_PAYGENT_LATER_PAYMENT => '後払いを利用する'
    );
}

// ペイジェントの各払込の対応番号
/*
    電文種別を表す区分
    010：ATM決済申込
    020：ｶｰﾄﾞ決済ｵｰｿﾘ
    021：ｶｰﾄﾞ決済ｵｰｿﾘｷｬﾝｾﾙ
    022：ｶｰﾄﾞ決済売上
    023：ｶｰﾄﾞ決済売上ｷｬﾝｾﾙ
    024：ｶｰﾄﾞ決済3Dｵｰｿﾘ
    025：ｶｰﾄﾞ情報設定
    026：ｶｰﾄﾞ情報削除
    027：ｶｰﾄﾞ情報照会
    029：ｶｰﾄﾞ決済補正売上
    030：ｺﾝﾋﾞﾆ決済(番号方式)申込
    040：ｺﾝﾋﾞﾆ決済(払込票方式)申込
    050：銀行ﾈｯﾄ決済申込
    060：銀行ネット決済ASP
    090：決済情報照会
    091：差分照会
    100：携帯ｷｬﾘｱ決済申込
    101：携帯キャリア決済売上要求電文
    102：携帯キャリア決済取消要求電文
    103：携帯キャリア決済補正売上要求電文
    150：電子マネー決済申込
    152：電子マネー決済取消要求電文
    153：電子マネー決済補正売上要求電文
    160：Yahoo!ウォレット決済申込
    161：Yahoo!ウォレット決済売上要求電文
    162：Yahoo!ウォレット決済取消要求電文
    163：Yahoo!ウォレット決済補正売上要求電文
    340：Paidyオーソリキャンセル電文
    341：Paidy売上電文
    342：Paidy返金電文
*/
define("PAYGENT_ATM", '010');
define("PAYGENT_CREDIT", '020');
define("PAYGENT_CREDIT_PROCESSING", '0201');
define("PAYGENT_AUTH_CANCEL", '021');
define("PAYGENT_CARD_COMMIT", '022');
define("PAYGENT_CARD_COMMIT_CANCEL", '023');
define("PAYGENT_CARD_COMMIT_REVICE", '029');
define("PAYGENT_CARD_COMMIT_REVICE_PROCESSING", '0291');
define("PAYGENT_CARD_3D", '024');
define("PAYGENT_CARD_STOCK_SET", '025');
define("PAYGENT_CARD_STOCK_DEL", '026');
define("PAYGENT_CARD_STOCK_GET", '027');
define("PAYGENT_CONVENI_NUM", '030');
define("PAYGENT_CONVENI_CALL", '040');
define("PAYGENT_BANK", '060');
define("PAYGENT_SETTLEMENT_DETAIL", '094');
define("PAYGENT_CAREER", '100');
define("PAYGENT_CAREER_COMMIT", '101');
define("PAYGENT_CAREER_COMMIT_CANCEL", '102');
define("PAYGENT_CAREER_COMMIT_REVICE", '103');
define("PAYGENT_CAREER_COMMIT_AUTH", '104');
define("PAYGENT_EMONEY", '150');
//define("PAYGENT_EMONEY_COMMIT", '151');
define("PAYGENT_EMONEY_COMMIT_CANCEL", '152');
define("PAYGENT_EMONEY_COMMIT_REVICE", '153');
define("PAYGENT_YAHOOWALLET", '160');
define("PAYGENT_YAHOOWALLET_COMMIT", '161');
define("PAYGENT_YAHOOWALLET_COMMIT_CANCEL", '162');
define("PAYGENT_YAHOOWALLET_COMMIT_REVICE", '163');
define("PAYGENT_VIRTUAL_ACCOUNT", '070');
define("PAYGENT_LATER_PAYMENT", '220');
define("PAYGENT_LATER_PAYMENT_CANCEL", '221');
define("PAYGENT_LATER_PAYMENT_CLEAR", '222');
define("PAYGENT_LATER_PAYMENT_REDUCTION", '223');
define("PAYGENT_LATER_PAYMENT_BILL_REISSUE", '224');
define("PAYGENT_LATER_PAYMENT_PRINT", '225');
define("PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_NG", PAYGENT_LATER_PAYMENT . '_' . '11');
define("PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_RESERVE", PAYGENT_LATER_PAYMENT . '_' . '12');
define("PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT", PAYGENT_LATER_PAYMENT . '_' . '19');
define("PAYGENT_LATER_PAYMENT_ST_AUTHORIZED", PAYGENT_LATER_PAYMENT . '_' . '20');
define("PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_CANCEL", PAYGENT_LATER_PAYMENT . '_' . '32');
define("PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_EXPIRE", PAYGENT_LATER_PAYMENT . '_' . '33');
define("PAYGENT_LATER_PAYMENT_ST_CLEAR_REQ_FIN", PAYGENT_LATER_PAYMENT . '_' . '35');
define("PAYGENT_LATER_PAYMENT_ST_SALES_RESERVE", PAYGENT_LATER_PAYMENT . '_' . '36');
define("PAYGENT_LATER_PAYMENT_ST_CLEAR", PAYGENT_LATER_PAYMENT . '_' . '40');
define("PAYGENT_LATER_PAYMENT_ST_CLEAR_SALES_CANCEL_INVALIDITY", PAYGENT_LATER_PAYMENT . '_' . '41');
define("PAYGENT_LATER_PAYMENT_ST_SALES_CANCEL", PAYGENT_LATER_PAYMENT . '_' . '60');
define("PAYGENT_PAIDY_AUTH_CANCELED", '340');
define("PAYGENT_PAIDY_COMMIT",  '341');
define("PAYGENT_PAIDY_REFUND", '342');

// Paidy 電文に紐づかない表示用パラメータ値
define("PAYGENT_PAIDY_AUTHORIZED", 'ETC_1');
define("PAYGENT_PAIDY_AUTH_EXPIRED", 'ETC_2');
define("PAYGENT_PAIDY_COMMIT_EXPIRED", 'ETC_3');
define("PAYGENT_PAIDY_COMMIT_CANCELED", 'ETC_4');
define("PAYGENT_PAIDY_COMMIT_REVICE", 'ETC_5');

define("PAYGENT_CAREER_D", '100_1');
define("PAYGENT_CAREER_A", '100_2');
define("PAYGENT_CAREER_S", '100_3');
define("PAYGENT_CAREER_AUTH_D", '104_1');
define("PAYGENT_CAREER_AUTH_A", '104_2');

define("PAYGENT_EMONEY_W", '150_1');

define("PAYGENT_LINK", 'link');

define("PAYGENT_PAIDY", 'paidy');

// バッチで使用する。
define("PAYGENT_REF", '091');

// バッチで使用する決済種別CD
define("PAYMENT_TYPE_ATM", '01');
define("PAYMENT_TYPE_CREDIT", '02');
define("PAYMENT_TYPE_CONVENI_NUM", '03');
define("PAYMENT_TYPE_BANK", '05');
define("PAYMENT_TYPE_CAREER", '06');
define("PAYMENT_TYPE_EMONEY", '11');
define("PAYMENT_TYPE_YAHOOWALLET", '12');
define("PAYMENT_TYPE_VIRTUAL_ACCOUNT", '07');
define("PAYMENT_TYPE_LATER_PAYMENT", '15');
define("PAYMENT_TYPE_PAIDY", '22');

// バッチで使用する決済ステータス
define("STATUS_PRE_REGISTRATION", '10');
define("STATUS_NG_AUTHORITY", '11');
define("STATUS_PAYMENT_EXPIRED", '12');
define("STATUS_3DSECURE_INTERRUPTION", '13');
define("STATUS_3DSECURE_AUTHORIZE", '14');
define("STATUS_REGISTRATION_SUSPENDED", '15');
define("STATUS_PAYMENT_INVALIDITY_NO_CLEAR", '16');
define("STATUS_AUTHORITY_OK", '20');
define("STATUS_AUTHORITY_COMPLETED", '21');
define("STATUS_CLEAR_REQUESTING", '30');
define("STATUS_AUTHORITY_CANCELING", '31');
define("STATUS_AUTHORITY_CANCELED", '32');
define("STATUS_AUTHORITY_EXPIRED", '33');
define("CORRECT_REQUESTING", '34');
define("STATUS_PENDING_SALES", '36');
define("STATUS_NO_PENDING", '37');
define("STATUS_PRE_CLEARED", '40');
define("STATUS_PRE_CLEARED_EXPIRATION_CANCELLATION_SALES", '41');
define("STATUS_PRE_SALES_CANCELING", '42');
define("STATUS_PRELIMINARY_PRE_DETECTION", '43');
define("STATUS_COMPLETE_CLEARED", '44');
define("STATUS_PRE_SALES_CANCEL_ARRANGING", '50');
define("STATUS_PRE_SALES_CANCELLATION", '60');
define("STATUS_PRELIMINARY_CANCELLATION", '61');
define("STATUS_COMPLETE_CANCELLATION", '62');
define("STATUS_REQUESTED", '10');
define("STATUS_AUTHORIZE_NG", '11');
define("STATUS_AUTHORIZE_RESERVE", '12');
define("STATUS_AUTHORIZED_BEFORE_PRINT", '19');
define("STATUS_AUTHORIZED", '20');
define("STATUS_AUTHORIZE_CANCEL", '32');
define("STATUS_AUTHORIZE_EXPIRE", '33');
define("STATUS_CLEAR_REQ_FIN", '35');
define("STATUS_SALES_RESERVE", '36');
define("STATUS_CLEAR", '40');
define("STATUS_CLEAR_SALES_CANCEL_INVALIDITY", '41');
define("STATUS_SALES_CANCEL", '60');

// 無限ループを避ける
define("PAYGENT_REF_LOOP", 5);

// カートセッション状態管理キー
define('PAYGENT_CART_SESS_KEY', '_paygent_cart_sess_key_');

// 表示用パラーメータ
function getDispKind(){
    return $arrDispKind = array(
        PAYGENT_AUTH_CANCEL => 'オーソリキャンセル',
        PAYGENT_CARD_COMMIT => '売上',
        PAYGENT_CARD_COMMIT_REVICE => '売上変更',
        PAYGENT_CARD_COMMIT_REVICE_PROCESSING => '売上変更処理中',
        PAYGENT_CARD_COMMIT_CANCEL => '売上キャンセル',
        PAYGENT_CREDIT => 'オーソリ変更',
        PAYGENT_CREDIT_PROCESSING => 'オーソリ変更処理中',
        PAYGENT_CAREER_COMMIT => '売上',
        PAYGENT_CAREER_COMMIT_CANCEL => '取消',
        PAYGENT_CAREER_COMMIT_REVICE => '売上変更',
        PAYGENT_EMONEY_COMMIT_CANCEL => '取消',
        PAYGENT_EMONEY_COMMIT_REVICE => '売上変更',
        PAYGENT_YAHOOWALLET_COMMIT => '売上',
        PAYGENT_YAHOOWALLET_COMMIT_CANCEL => '取消',
        PAYGENT_YAHOOWALLET_COMMIT_REVICE => '金額変更',
        PAYGENT_VIRTUAL_ACCOUNT . '_01' => '売上',
        PAYGENT_VIRTUAL_ACCOUNT . '_02' => '売上(不足）',
        PAYGENT_VIRTUAL_ACCOUNT . '_06' => '売上',
        PAYGENT_VIRTUAL_ACCOUNT . '_07' => '売上（不足）',
        PAYGENT_VIRTUAL_ACCOUNT . '_08' => '売上（過多）',
        PAYGENT_VIRTUAL_ACCOUNT . '_99' => '取消',
        PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_NG => '審査NG',
        PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_RESERVE => '審査保留',
        PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT => '審査OK(印字データ取得前)',
        PAYGENT_LATER_PAYMENT_ST_AUTHORIZED => '審査OK',
        PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_CANCEL => 'オーソリキャンセル',
        PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_EXPIRE => '審査OK',
        PAYGENT_LATER_PAYMENT_ST_CLEAR_REQ_FIN => '売上処理中',
        PAYGENT_LATER_PAYMENT_ST_SALES_RESERVE => '売上保留',
        PAYGENT_LATER_PAYMENT_ST_CLEAR => '売上',
        PAYGENT_LATER_PAYMENT_ST_CLEAR_SALES_CANCEL_INVALIDITY => '売上',
        PAYGENT_LATER_PAYMENT_ST_SALES_CANCEL => '売上キャンセル',
        PAYGENT_LATER_PAYMENT_CANCEL => '取消',
        PAYGENT_LATER_PAYMENT_CLEAR => '売上',
        PAYGENT_LATER_PAYMENT_REDUCTION => 'オーソリ変更',
        PAYGENT_LATER_PAYMENT_BILL_REISSUE => '請求書再発行',
        PAYGENT_LATER_PAYMENT_PRINT => '請求書印字データ出力',
        PAYGENT_PAIDY_AUTHORIZED => 'オーソリＯＫ',
        PAYGENT_PAIDY_AUTH_CANCELED => 'オーソリキャンセル',
        PAYGENT_PAIDY_AUTH_EXPIRED => 'オーソリ期限切れ',
        PAYGENT_PAIDY_COMMIT => '売上',
        PAYGENT_PAIDY_COMMIT_REVICE => '売上変更',
        PAYGENT_PAIDY_COMMIT_EXPIRED => '売上(売上取消期限切れ）',
        PAYGENT_PAIDY_COMMIT_CANCELED => '売上キャンセル',
    );
}

// 受注時の初期ステータス
function getInitStatus(){
    return $arrInitStatus = array(
        PAYGENT_CREDIT => ORDER_NEW,            // クレジットは新規受付
        PAYGENT_ATM => ORDER_PAY_WAIT,          // ATM決済は入金待ち
        PAYGENT_CONVENI_NUM => ORDER_PAY_WAIT,  // コンビニ(番号方式)は入金待ち
        PAYGENT_CONVENI_CALL => ORDER_PAY_WAIT, // コンビニ(払込票方式)は入金待ち
        PAYGENT_BANK => ORDER_PAY_WAIT,         // 銀行は入金待ち
        PAYGENT_CAREER =>  ORDER_NEW,           // キャリア決済は新規受付
        PAYGENT_EMONEY => ORDER_NEW,            // 電子マネー決済は新規受付
        PAYGENT_YAHOOWALLET => ORDER_NEW,       // Yahoo!ウォレット決済は新規受付
        PAYGENT_LINK => ORDER_NEW,              // リンクは新規受付
        PAYGENT_VIRTUAL_ACCOUNT => ORDER_PAY_WAIT, // 仮想口座決済は入金待ち
        PAYGENT_LATER_PAYMENT => ORDER_NEW,     // 後払い決済は新規受付
    );
}

// 後払い決済の審査結果
function getArrLaterPaymentExampResult(){
    return $arrLaterPaymentStatus = array(
            PAYGENT_LATER_PAYMENT_ST_AUTHORIZED => '後払い決済の審査が通りました。',
            PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_NG => '後払い決済の審査が通りませんでした。'
    );
}

// 利用上限金額
define ("CHARGE_MAX", 500000);
define ("SEVEN_CHARGE_MAX", 300000);
// define ("CAREER_CHARGE_MAX", 30000);

// 利用下限金額
// define ("CAREER_CHARGE_MIN", 1);

// 電文バージョン
define ("TELEGRAM_VERSION", '1.0');

// コンビニコード
define ("CODE_SEVENELEVEN", "00C001");   // セブンイレブン
define ("CODE_LOWSON", "00C002");        // ローソン
define ("CODE_MINISTOP", "00C004");      // ミニストップ
define ("CODE_FAMILYMART", "00C005");    // ファミリーマート
define ("CODE_YAMAZAKI", "00C014");      // デイリーヤマザキ
define ("CODE_SEICOMART", "00C016");     // セイコーマート

// コンビニの種類
function getConvenience(){
    return $arrConvenience = array(
        CODE_SEICOMART => 'セイコーマート',
        CODE_LOWSON => 'ローソン',
        CODE_MINISTOP => 'ミニストップ',
        CODE_FAMILYMART => 'ファミリーマート',
        CODE_YAMAZAKI => 'デイリーヤマザキ',
        CODE_SEVENELEVEN => 'セブンイレブン'
    );
}

// PC_MOBILE_TYPE
define ("PC_MOBILE_TYPE_PC", "0");              // PC
define ("PC_MOBILE_TYPE_SMARTPHONE", "4");      // SmartPhone

// キャリア種別
define("CAREER_MOBILE_TYPE_DOCOMO", "1");	// ドコモ払い(iﾓｰﾄﾞ)
define("CAREER_MOBILE_TYPE_AU", "2");		// まとめてau支払い
define("CAREER_MOBILE_TYPE_SOFTBANK", "3");	// ソフトバンク(S!まとめて支払い)
define("CAREER_TYPE_AU", "4");				// auかんたん決済
define("CAREER_TYPE_DOCOMO", "5");			// ドコモ払い
define("CAREER_TYPE_SOFTBANK", "6");		// ソフトバンクまとめて支払い

// 電話区分コード
define ("TEL_HOME", "1");         // 自宅
define ("TEL_CALL", "2");         // 呼び出し
define ("TEL_DORMITORY", "3");    // 寮
define ("TEL_MOBILE", "5");       // 携帯

// 電話区分(コンビニ払込票方式)の種類
function getTelDivision(){
    return $arrTelDivision = array(
        TEL_HOME => '自宅',
        TEL_CALL => '呼び出し',
        TEL_DORMITORY => '寮',
        TEL_MOBILE => '携帯'
    );
}

// 電子マネー種別
define ("EMONEY_TYPE_WEBMONEY", "1"); // WebMoney
define ("EMONEY_TYPE_CHOCOMU", "2");  // ちょコム

// 電子マネーの種類
function getEmoney(){
    return $arrEmoney = array(
        EMONEY_TYPE_WEBMONEY => 'WebMoney'
    );
}

// ネットバンクの種類
function getNetBank(){
    return $arrNetBank = array(
        'D005' => 'e-payment',
        'D008' => 'ネット振込EDI',
        'D009' => 'One\'s ダイレクト',
        'D033' => 'ジャパンネット銀行',
        'D036' => 'イーバンク'
    );
}

function getPaymentClass(){
    // クレジット分割回数
    return $arrPaymentClass = array(
        '10' => '一括払い',
        '61-2' => '分割2回払い',
        '61-3' => '分割3回払い',
        '61-6' => '分割6回払い',
        '61-10' => '分割10回払い',
        '61-15' => '分割15回払い',
        '61-20' => '分割20回払い',
        '80' => 'リボ払い',
        '23' => 'ボーナス一括払い'
    );
}

// ペイジェント決済モジュール設定用(クレジット)
function getCartPaymentCategory(){
    // クレジット支払方法分類
    return $arrCartPaymentCategory = array(
        '10' => '一括払い',
        '61' => '分割払い',
        '80' => 'リボ払い',
        '23' => 'ボーナス一括払い'
    );
}

// トークン接続先
function getTokenEnv() {
    return array(
        '0' => '試験環境',
        '1' => '本番環境'
    );
}

// ペイジェント決済モジュール設定用(携帯キャリア)
function getCareerPaymentCategory() {
	// キャリア決済方法分類
	return $arrCareerPaymentCategory = array(
		CAREER_MOBILE_TYPE_DOCOMO => 'ドコモ払い',
		CAREER_MOBILE_TYPE_AU => 'auかんたん決済',
		CAREER_MOBILE_TYPE_SOFTBANK => 'ソフトバンクまとめて支払い・ワイモバイルまとめて支払い'
	);
}

// ソフトバンクの短縮形の文言
define("CAREER_MOBILE_TYPE_SOFTBANK_SHORT", "ソフトバンク・ワイモバイルまとめて支払い");

// ペイジェント決済モジュール設定用(電子マネー)
function getEmoneyPaymentCategory(){
    // 電子マネー決済方法分類
    return $arrEmoneyPaymentCategory = array(
        '1' => 'WebMoney'
    );
}

// カード支払区分(リンク型)
function getCardClass() {
    return array(
        '0' => '1回払いのみ',
        '1' => '全て',
        '2' => 'ボーナス一括以外全て'
    );
}

// オプションの要/不要
function getOptionActive() {
    return array(
        '1' => '要',
        '0' => '不要'
    );
}

// 決済種別
define("NUMBERING_TYPE_CYCLE", 0);
define("NUMBERING_TYPE_FIX", 1);
function getNumberingType() {
    return array(
        NUMBERING_TYPE_CYCLE => '回転付番のみ',
        NUMBERING_TYPE_FIX => '固定・回転付番併用',
    );
}

// 結果取得区分
define("RESULT_GET_TYPE_WAIT", 0);
define("RESULT_GET_TYPE_NO_WAIT", 1);
function getResultGetType() {
    return array(
        RESULT_GET_TYPE_WAIT => '審査結果を待つ',
        RESULT_GET_TYPE_NO_WAIT => '審査結果を後で取得する',
    );
}

// 審査結果通知メール
define("EXAM_RESULT_NOTIFICATION_TYPE_AUTO", 0);
define("EXAM_RESULT_NOTIFICATION_TYPE_MANUAL", 1);
function getExamResultNotificationType() {
    return array(
        EXAM_RESULT_NOTIFICATION_TYPE_AUTO => '自動で送信する',
        EXAM_RESULT_NOTIFICATION_TYPE_MANUAL => '自動で送信しない',
    );
}

// 請求書の同梱
function getInvoiceIncludeOption(){
    return $arrInvoiceIncludeOption = array(
        '1' => '請求書を商品に同梱して配送する'
    );
}

// 自動キャンセル区分
define("AUTO_CANCEL_TYPE_WAIT", 0);
define("AUTO_CANCEL_TYPE_NO_WAIT", 1);
function getAutoCancelType() {
    return array(
        AUTO_CANCEL_TYPE_WAIT => '目視審査結果を待つ',
        AUTO_CANCEL_TYPE_NO_WAIT => '目視審査結果を待たずキャンセルする',
    );
}

// 後払い決済 オーソリ変更 請求書送付方法
define("OPTION_INVOICE_SEND_TYPE_SEPARATE", "2");
define("OPTION_INVOICE_SEND_TYPE_INCLUDE", "3");
function getInvoiceSendTypeOption() {
    return array(
        OPTION_INVOICE_SEND_TYPE_SEPARATE => '別送',
        OPTION_INVOICE_SEND_TYPE_INCLUDE => '同梱',
    );
}

// 後払い決済 請求書再発行 依頼理由
define("CLIENT_REASON_CODE_DEFAULT", "");
define("CLIENT_REASON_CODE_BILL_LOSS", "01");
define("CLIENT_REASON_CODE_BILL_NO_DELIVERY", "02");
define("CLIENT_REASON_CODE_MOVE", "03");
define("CLIENT_REASON_CODE_OTHER", "99");
function getClientReasonCode() {
    return array(
        CLIENT_REASON_CODE_DEFAULT => '選択してください',
        CLIENT_REASON_CODE_BILL_LOSS => '請求書紛失',
        CLIENT_REASON_CODE_BILL_NO_DELIVERY => '請求書未達',
        CLIENT_REASON_CODE_MOVE => '転居',
        CLIENT_REASON_CODE_OTHER => 'その他',
    );
}

// 後払い決済 売上 運送会社コード
define("CARRIERS_COMPANY_CODE_DEFAULT", "");
define("CARRIERS_COMPANY_CODE_SAGAWA", "11");
define("CARRIERS_COMPANY_CODE_YAMATO", "12");
define("CARRIERS_COMPANY_CODE_NITTSU", "13");
define("CARRIERS_COMPANY_CODE_SEINO", "14");
define("CARRIERS_COMPANY_CODE_REGISTERED", "15");
define("CARRIERS_COMPANY_CODE_YUPACK", "16");
define("CARRIERS_COMPANY_CODE_FUKUTSU", "18");
define("CARRIERS_COMPANY_CODE_NIIGATA", "20");
define("CARRIERS_COMPANY_CODE_MEITETSU", "21");
define("CARRIERS_COMPANY_CODE_SINSYU", "23");
define("CARRIERS_COMPANY_CODE_TOTAL", "26");
define("CARRIERS_COMPANY_CODE_SPECIFY_TIME", "28");
define("CARRIERS_COMPANY_CODE_ECOHAI", "27");
define("CARRIERS_COMPANY_CODE_TONAMI", "29");
define("CARRIERS_COMPANY_CODE_SEINO_EX", "30");
define("CARRIERS_COMPANY_CODE_OHKAWA", "31");
define("CARRIERS_COMPANY_CODE_PULUS", "32");
function getCarriersCompanyCode() {
    return array(
        CARRIERS_COMPANY_CODE_DEFAULT => '選択してください',
        CARRIERS_COMPANY_CODE_SAGAWA => '佐川急便',
        CARRIERS_COMPANY_CODE_YAMATO => 'ヤマト運輸',
        CARRIERS_COMPANY_CODE_NITTSU => '日本通運',
        CARRIERS_COMPANY_CODE_SEINO => '西濃運輸',
        CARRIERS_COMPANY_CODE_REGISTERED => '郵便書留',
        CARRIERS_COMPANY_CODE_YUPACK => 'ゆうパック',
        CARRIERS_COMPANY_CODE_FUKUTSU => '福山通運',
        CARRIERS_COMPANY_CODE_NIIGATA => '新潟運輸',
        CARRIERS_COMPANY_CODE_MEITETSU => '名鉄運輸',
        CARRIERS_COMPANY_CODE_SINSYU => '信州名鉄運輸',
        CARRIERS_COMPANY_CODE_TOTAL => 'トールエクスプレス',
        CARRIERS_COMPANY_CODE_SPECIFY_TIME => '配達時間指定郵便',
        CARRIERS_COMPANY_CODE_ECOHAI => 'エコ配',
        CARRIERS_COMPANY_CODE_TONAMI => 'トナミ運輸',
        CARRIERS_COMPANY_CODE_SEINO_EX => 'セイノーエクスプレス',
        CARRIERS_COMPANY_CODE_OHKAWA => '大川配送サービス',
        CARRIERS_COMPANY_CODE_PULUS => 'プラスサービス',
    );
}

// 文字入力制限（byte）
define ("PAYGENT_BANK_STEXT_LEN", "12");      // ATM，銀行ネットの利用者名（漢字，カナ）
define ("PAYGENT_CONVENI_STEXT_LEN", "14");   // コンビニの利用者名（カナ）
define ("PAYGENT_CONVENI_MTEXT_LEN", "20");   // コンビニの利用者名（漢字）
define ("PAYGENT_TEL_ITEM_LEN", 11);          // 電話番号各項目制限(11文字：全入力)
define ("PAYGENT_S_TEL_ITEM_LEN", 4);         // 電話番号各項目制限(4文字：3項目入力)
define ("PAYGENT_LINK_STEXT_LEN", "12");      // リンク型の利用者名（漢字，カナ）
define ("PAYGENT_VIRTUAL_ACCOUNT_STEXT_LEN", "48");  // 仮想口座の利用者名（カナ）
define ("PAYGENT_VIRTUAL_ACCOUNT_MTEXT_LEN", "100"); // 仮想口座の利用者名（漢字）

// カード情報お預かり上限数
define ("CARD_STOCK_MAX", "10");

//トークン決済のJSのパス
define("PATH_JS_TOKEN", MODULE_REALDIR . "mdl_paygent/resources/token.js");

//トーク生成JSのURL(本番環境)
define("PAYGENT_TOKEN_JS_URL_LIVE", "https://token.paygent.co.jp/js/PaygentToken.js");

//トーク生成JSのURL(試験環境)
define("PAYGENT_TOKEN_JS_URL_SANDBOX", "https://sandbox.paygent.co.jp/js/PaygentToken.js");

//トーク生成JSの参照先を変更する場合はここで設定
define("PAYGENT_TOKEN_CONNECT_URL", "");

//決済ID更新対象のステータス
function getPaymentIdUpdateStatus() {
    return array(
        STATUS_AUTHORITY_OK,
        STATUS_PRE_CLEARED,
        STATUS_PRELIMINARY_PRE_DETECTION
    );
}

// KS側の文字コード
define("CHAR_CODE_KS", "SJIS-win");

// エラー項目のマッピング対象外の場合のエラーメッセージ
define("NO_MAPPING_MESSAGE", "恐れ入りますが店舗にお問い合わせ下さいますようお願い致します。");

// クレジットカード オーソリ失敗累積カウント上限数
define("CREDIT_AUTHORITY_RETRY_LIMIT", 10);

// クレジットカード オーソリ失敗カウンタ有効期限 : 60分で指定
define("CREDIT_AUTHORITY_LOCK_EXPIRE", 3600);

// クレジットカード オーソリ失敗時のエラーメッセージ
define("CREDIT_AUTHORITY_LOCK_MESSAGE", "クレジットカード情報の誤入力が所定回数を超えました。<br>別の決済手段をご検討ください。");

// Resources JS Path
define("PATH_JS_PAIDY", MODULE_REALDIR . "mdl_paygent/resources/paidy.js");

// 決済金額一致　不一致(増額・減額)
define("PAYMENT_AMOUNT_MATCH", 0);
define("PAYMENT_AMOUNT_UNMATCH", 1);

//====================================================================================

/**
 * 関数名：sfGetPaygentShare
 * 処理内容：ペイジェント情報送信の共通処理
 * 戻り値：取得結果
 */
function sfGetPaygentShare($telegram_kind, $order_id, $arrParam, $payment_id = "") {
    /** 共通電文 **/
    // マーチャントID
    $arrSend['merchant_id'] = $arrParam['merchant_id'];
    // 接続ID
    $arrSend['connect_id'] = $arrParam['connect_id'];
    // 接続パスワード
    $arrSend['connect_password'] = $arrParam['connect_password'];
    // 電文種別ID
    $arrSend['telegram_kind'] = $telegram_kind;
    // 電文バージョン
    $arrSend['telegram_version'] = TELEGRAM_VERSION;
    // マーチャント取引ID
    $arrSend['trading_id'] = $order_id;
    // 決済ID
    if (strlen($payment_id) > 0) $arrSend['payment_id'] = $payment_id;
    // EC-CUBEからの電文であることを示す。
    $arrSend['partner'] = 'lockon';
    // EC-CUBE本体のバージョン
    $arrSend['eccube_version'] = ECCUBE_VERSION;
    // 決済モジュールのアップデート日時
    $arrSend['eccube_module_install_date'] = getModuleUpdateDate();

    return $arrSend;
}

/**
 * 関数名：sfGetPaygentLaterPaymentModule
 * 処理内容：後払い決済電文送信の共通処理（モジュール）
 * 戻り値：取得結果
 */
function sfGetPaygentLaterPaymentModule($order_id) {

    $objQuery =& SC_Query_Ex::getSingletonInstance();
    // 受注情報
    $arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($order_id));
    $objPurchase = new SC_Helper_Purchase_Ex();
    // 配送情報
    $arrShippings = $objPurchase->getShippings($arrOrder[0]['order_id'], false);
    $arrShippings = reset($arrShippings);
    // 受注詳細
    $arrOrderDetail = $objPurchase->getOrderDetail($arrOrder[0]['order_id']);

    $arrSend = array();
    // 顧客
    $arrSend += getPaygentLaterPaymentCustomer($arrOrder);
    // 配送先
    $arrSend += getPaygentLaterPaymentShip($arrShippings);
    // 明細（商品/手数料/ポイント/送料）
    $arrSend += getPaygentLaterPaymentGoods($arrOrder, $arrOrderDetail);

    // モジュールとリンクで異なる項目
    // 購入者氏名（漢字）
    $arrSend['customer_name_kanji'] = $arrOrder[0]['order_name01'] . $arrOrder[0]['order_name02'];
    // 購入者氏名（カナ）
    $arrSend['customer_name_kana'] = $arrOrder[0]['order_kana01'] . $arrOrder[0]['order_kana02'];
    // 購入者電話番号
    $arrSend['customer_tel'] = $arrOrder[0]['order_tel01'] . "-" . $arrOrder[0]['order_tel02'] . "-" . $arrOrder[0]['order_tel03'];
    // 配送先氏名（漢字）
    $arrSend['ship_name_kanji'] = $arrShippings['shipping_name01'] . $arrShippings['shipping_name02'];
    // 配送先氏名（カナ）
    $arrSend['ship_name_kana'] = $arrShippings['shipping_kana01'] . $arrShippings['shipping_kana02'];

    return $arrSend;
}

/**
 * 関数名：sfGetPaygentLaterPaymentLink
 * 処理内容：後払い決済電文送信の共通処理（リンク/混合）
 * 戻り値：取得結果
 */
function sfGetPaygentLaterPaymentLink($order_id, $invoice_send_type) {

    $objQuery =& SC_Query_Ex::getSingletonInstance();
    // 受注情報
    $arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($order_id));
    $objPurchase = new SC_Helper_Purchase_Ex();
    // 配送情報
    $arrShippings = $objPurchase->getShippings($arrOrder[0]['order_id'], false);
    $arrShippings = reset($arrShippings);
    // 受注詳細
    $arrOrderDetail = $objPurchase->getOrderDetail($arrOrder[0]['order_id']);

    $arrSend = array();
    // 顧客
    $arrSend += getPaygentLaterPaymentCustomer($arrOrder);
    // 配送先
    $arrSend += getPaygentLaterPaymentShip($arrShippings);
    // 明細（商品/手数料/ポイント/送料）
    $arrSend += getPaygentLaterPaymentGoods($arrOrder, $arrOrderDetail);

    // モジュールとリンクで異なる項目
    // 購入者電話番号
    $arrSend['customer_tel_hyphen'] = $arrOrder[0]['order_tel01'] . "-" . $arrOrder[0]['order_tel02'] . "-" . $arrOrder[0]['order_tel03'];
    // 配送先姓・名
    $arrSend['ship_family_name'] = mb_convert_kana($arrShippings['shipping_name01'],'KVA');
    $arrSend['ship_name'] = mb_convert_kana($arrShippings['shipping_name02'],'KVA');
    // 配送先姓・名（カナ）
    $arrSend['ship_family_name_kana'] = mb_convert_kana($arrShippings['shipping_kana01'],'k');
    $arrSend['ship_family_name_kana'] = preg_replace("/ｰ/", "-", $arrSend['ship_family_name_kana']);
    $arrSend['ship_family_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['ship_family_name_kana']);
    $arrSend['ship_name_kana'] = mb_convert_kana($arrShippings['shipping_kana02'],'k');
    $arrSend['ship_name_kana'] = preg_replace("/ｰ/", "-", $arrSend['ship_name_kana']);
    $arrSend['ship_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['ship_name_kana']);

    //請求書送付方法
    $arrSend['invoice_send_type'] = $invoice_send_type;

    //同梱の場合は配送先をクリア(JACCSの仕様に準拠するため)
    if ($invoice_send_type == INVOICE_SEND_TYPE_INCLUDE) {
        $arrSend = clearShipParamLink($arrSend);
    }

    return $arrSend;
}

/**
 * 関数名：getPaygentLaterPaymentCustomer
 * 処理内容：後払い決済電文送信の顧客情報作成処理
 * 戻り値：取得結果
 */
function getPaygentLaterPaymentCustomer($arrOrder) {

    // 決済金額
    $arrSend['payment_amount'] = $arrOrder[0]['payment_total'];
    // 購入者注文日
    $arrSend['shop_order_date'] = date("Ymd", strtotime($arrOrder[0]['create_date']));
    // 購入者郵便番号
    $arrSend['customer_zip_code'] = $arrOrder[0]['order_zip01'] . $arrOrder[0]['order_zip02'];
    // 購入者住所
    $masterData = new SC_DB_MasterData_Ex();
    $arrPref = $masterData->getMasterData('mtb_pref');
    $arrSend['customer_address'] = $arrPref[$arrOrder[0]['order_pref']] . $arrOrder[0]['order_addr01'] . $arrOrder[0]['order_addr02'];
    // 購入者メールアドレス
    $arrSend['customer_email'] = $arrOrder[0]['order_email'];

    return $arrSend;
}

/**
 * 関数名：getPaygentLaterPaymentShip
 * 処理内容：後払い決済電文送信の配送先情報作成処理
 * 戻り値：取得結果
 */
function getPaygentLaterPaymentShip($arrShippings) {
    // 配送先郵便番号
    $arrSend['ship_zip_code'] = $arrShippings['shipping_zip01'] . $arrShippings['shipping_zip02'];
    // 配送先住所
    $masterData = new SC_DB_MasterData_Ex();
    $arrPref = $masterData->getMasterData('mtb_pref');
    $arrSend['ship_address'] = $arrPref[$arrShippings['shipping_pref']] . $arrShippings['shipping_addr01'] . $arrShippings['shipping_addr02'];
    // 配送先電話番号
    $arrSend['ship_tel'] = $arrShippings['shipping_tel01'] .  "-" . $arrShippings['shipping_tel02'] .  "-" . $arrShippings['shipping_tel03'];

    return $arrSend;
}

/**
 * 関数名：getPaygentLaterPaymentGoods
 * 処理内容：後払い決済電文送信の明細情報作成処理
 * 戻り値：取得結果
 */
function getPaygentLaterPaymentGoods($arrOrder, $arrOrderDetail) {

    // 明細（商品）
    foreach ($arrOrderDetail as $key => $orderDetail) {
        $index = $key;
        // 明細(商品名）
        $arrSend['goods[' . $index . ']'] = $orderDetail['product_name'];
        // 明細(単価）
        $arrSend['goods_price[' . $index . ']'] = SC_Helper_DB_Ex::sfCalcIncTax($orderDetail['price']);
        // 明細(数量）
        $arrSend['goods_amount[' . $index . ']'] = $orderDetail['quantity'];
    }
    // 明細（手数料）
    if ($arrOrder[0]['charge'] != "0") {
        $index++;
        // 明細(商品名）
        $arrSend['goods[' . $index . ']'] = "手数料";
        // 明細(単価）
        $arrSend['goods_price[' . $index . ']'] = $arrOrder[0]['charge'];
        // 明細(数量）
        $arrSend['goods_amount[' . $index . ']'] = "1";
    }
    // 明細（使用ポイント）
    if ($arrOrder[0]['use_point'] != "0") {
        $index++;
        // 明細(商品名）
        $arrSend['goods[' . $index . ']'] = "使用ポイント";
        // 明細(単価）
        $arrSend['goods_price[' . $index . ']'] = 0 - $arrOrder[0]['use_point'];
        // 明細(数量）
        $arrSend['goods_amount[' . $index . ']'] = "1";
    }
    // 明細（送料）
    if ($arrOrder[0]['deliv_fee'] != "0") {
        $index++;
        // 明細(商品名）
        $arrSend['goods[' . $index . ']'] = "送料";
        // 明細(単価）
        $arrSend['goods_price[' . $index . ']'] = $arrOrder[0]['deliv_fee'];
        // 明細(数量）
        $arrSend['goods_amount[' . $index . ']'] = "1";
    }
    return $arrSend;
}

/**
 * 関数名：sfSendPaygentCredit
 * 処理内容：クレジット情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentCredit($arrData, $arrInput, $order_id, $transactionid) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // クレジット用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_CREDIT . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_CREDIT, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    // 決済金額
    $arrSend['payment_amount'] = $arrData['payment_total'];
    // カード番号
    $arrSend['card_number'] = $arrInput['card_no01'].$arrInput['card_no02'].$arrInput['card_no03'].$arrInput['card_no04'];
    // セキュリティコード
    $arrSend['card_conf_number'] = $arrInput['security_code'];
    // カード有効期限(MMYY)
    $arrSend['card_valid_term'] = $arrInput['card_month'].$arrInput['card_year'];
    // 支払い区分、分割回数の取得
    list($payment_class, $split_count) = split("-", $arrInput['payment_class']);
    // トークン
    $arrSend['card_token'] = $arrInput['card_token'];
    // セキュリティコード利用
    $arrSend['security_code_use'] = isset($arrOtherParam['security_code'])  ?  $arrOtherParam['security_code'] : 0;

    // 支払い区分
    /*
     * 10:1回
     * 23:ボーナス1回
     * 61:分割
     * 80:リボルビング
     */
    $arrSend['payment_class'] = $payment_class;
    // 分割回数
    $arrSend['split_count'] = $split_count;
    /** 3Dセキュア関連 **/
    if ($arrOtherParam['credit_3d'] != 1) {
        // 3Dセキュア不要区分
        $arrSend['3dsecure_ryaku'] = "1";
    } else {
        // HttpAccept
		if (isset($_SERVER['HTTP_ACCEPT'])) {
		    $arrSend['http_accept'] = $_SERVER['HTTP_ACCEPT'];
		} else {
		    $arrSend['http_accept'] = "*/*";
		}
        // HttpUserAgent
        $arrSend['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        // 3Dセキュア戻りURL
        $arrSend['term_url'] = HTTPS_URL;
		$arrSend['term_url'] .= "shopping/load_payment_module.php?mode=credit_3d&order_id=" . $order_id . "&" . TRANSACTION_ID_NAME . "=" . $transactionid . '&hash=' . createPaygentHash($arrData);
		if (SC_MobileUserAgent::isMobile()) {
		    $arrSend['term_url'] .= "&" . session_name() . "=" . session_id();
	    }
    }
    /** カード情報お預かり機能 **/
    if ($arrInput['stock'] == 1) {
        // 不要
        unset($arrSend['card_number']);
        unset($arrSend['card_valid_term']);
        // カード情報お預かりモード
        $arrSend['stock_card_mode'] = "1";
        // 顧客ID
        $arrSend['customer_id'] = $arrData['customer_id'];
        // 顧客カードID
        $arrSend['customer_card_id'] = $arrInput['CardSeq'];

        // セキュリティーコード有効かつトークン決済時
        if ($arrOtherParam['security_code'] === "1" && $arrOtherParam['token_pay'] === "1") {
            $arrSend['security_code_token'] = "1";
        }
    }

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $p->reqPut($key, $val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_CREDIT, $p, $arrData['order_id'], $arrInput);

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentCredit3d
 * 処理内容：3Dセキュア情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentCredit3d($arrData, $arrInput, $order_id) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // クレジット用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_CREDIT . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_CARD_3D, $arrData['order_id'], $arrPaymentDB[0], $arrData['memo06']);

    /** 個別電文 **/
    // ACS応答
    $arrSend['PaRes'] = $arrInput['PaRes'];
    // マーチャントデータ
    $arrSend['MD'] = $arrInput['MD'];

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $p->reqPut($key, $val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_CREDIT, $p, $order_id, $arrInput);

    return $arrRet;
}

/**
 * 関数名：sfSetPaygentCreditStock
 * 処理内容：カード情報の設定
 * 戻り値：取得結果
 */
function sfSetPaygentCreditStock($arrData, $arrInput) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // 設定パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_CARD_STOCK_SET, 0, $arrPaymentDB[0]);

    /** 個別電文 **/
    // 顧客ID
    $arrSend['customer_id'] = $arrData['customer_id'];
    // カード番号
    $arrSend['card_number'] = $arrInput['card_no01'].$arrInput['card_no02'].$arrInput['card_no03'].$arrInput['card_no04'];
    // カード有効期限(MMYY)
    $arrSend['card_valid_term'] = $arrInput['card_month'].$arrInput['card_year'];
    // カード名義人
    $arrSend['cardholder_name'] = ($arrInput['card_name01'] || $arrInput['card_name02']) ? $arrInput['card_name01']." ".$arrInput['card_name02'] : "";
    // トークン
    $arrSend['card_token'] = $arrInput['card_token_stock'];

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $p->reqPut($key, $val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponseCard(PAYGENT_CARD_STOCK_SET, $p, $arrData['customer_id']);

    return $arrRet;
}

/**
 * 関数名：sfDelPaygentCreditStock
 * 処理内容：カード情報の削除
 * 戻り値：取得結果
 */
function sfDelPaygentCreditStock($arrData, $arrInput) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // 設定パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_CARD_STOCK_DEL, 0, $arrPaymentDB[0]);

    /** 個別電文 **/
    // 顧客ID
    $arrSend['customer_id'] = $arrData['customer_id'];
    // 顧客カードID
    $arrSend['customer_card_id'] = $arrInput['CardSeq'];

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $p->reqPut($key, $val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponseCard(PAYGENT_CARD_STOCK_DEL, $p, $arrData['customer_id']);

    return $arrRet;
}

/**
 * 関数名：sfGetPaygentCreditStock
 * 処理内容：カード情報の照会
 * 戻り値：取得結果
 */
function sfGetPaygentCreditStock($arrData) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // 設定パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_CARD_STOCK_GET, 0, $arrPaymentDB[0]);

    /** 個別電文 **/
    // 顧客ID
    $arrSend['customer_id'] = $arrData['customer_id'];
    // 顧客カードID
    $arrSend['customer_card_id'] = $arrInput['delete_card'];

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $p->reqPut($key, $val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponseCard(PAYGENT_CARD_STOCK_GET, $p, $arrData['customer_id']);

    return $arrRet;
}

/**
 * 関数名：sfGetPaygentCreditStockQuick
 * 処理内容：カード情報の照会
 * 戻り値：取得結果
 */
function sfGetPaygentCreditStockQuick($arrData, $arrParam) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // 設定パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE);

    $arrPaymentDB[0]['merchant_id'] = $arrParam['merchant_id'];
    $arrPaymentDB[0]['connect_id'] = $arrParam['connect_id'];
    $arrPaymentDB[0]['connect_password'] = $arrParam['connect_password'];

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_CARD_STOCK_GET, 0, $arrPaymentDB[0]);

    /** 個別電文 **/
    // 顧客ID
    $arrSend['customer_id'] = $arrData['customer_id'];
    // 顧客カードID
    $arrSend['customer_card_id'] = $arrInput['delete_card'];

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $p->reqPut($key, $val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponseCard(PAYGENT_CARD_STOCK_GET, $p, $arrData['customer_id']);

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentConveni
 * 処理内容：コンビニ(番号方式)情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentConveni($arrData, $arrInput, $uniqid) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // コンビニ用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_CONVENI_NUM . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_CONVENI_NUM, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    // 決済金額
    $arrSend['payment_amount'] = $arrData['payment_total'];
    // 利用者姓
    $arrSend['customer_family_name'] = $arrInput['customer_family_name'];
    // 利用者名
    $arrSend['customer_name'] = $arrInput['customer_name'];
    // 利用者姓半角カナ
    $arrSend['customer_family_name_kana'] = mb_convert_kana($arrInput['customer_family_name_kana'],'k');
    $arrSend['customer_family_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['customer_family_name_kana']);
    // 利用者名半角カナ
    $arrSend['customer_name_kana'] = mb_convert_kana($arrInput['customer_name_kana'],'k');
    $arrSend['customer_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['customer_name_kana']);
    // 利用者電話番号
    $arrSend['customer_tel'] = $arrInput['customer_tel'];
    // 支払期限日
    $arrSend['payment_limit_date'] = $arrOtherParam['payment_limit_date'];
    // 有効期限日
    // TODO : 上と同じパラメータ・・・。
    $arrSend['payment_limit_date'] = $arrOtherParam['payment_limit_date'];
    // コンビニ企業コード
    $arrSend['cvs_company_id'] =  $arrInput['cvs_company_id'];
    // 支払種別
    $arrSend['sales_type'] = '1';

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $enc_val = mb_convert_encoding($val, CHAR_CODE_KS, CHAR_CODE);

        if (!reverseCheck($val, $enc_val)) {
            return createErrorReturnFront($key);
        }

        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_CONVENI_NUM, $p, $uniqid, $arrInput);

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentConveniCall
 * 処理内容：コンビニ(払込票方式)情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentConveniCall($arrData, $arrInput, $uniqid) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // コンビニ用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_CONVENI_CALL . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_CONVENI_CALL, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    // 決済金額
    $arrSend['payment_amount'] = $arrData['payment_total'];
    // 税区分
    $arrSend['tax_class'] = 1;
    // 支払情報
    $arrSend['site_info'] = $arrOtherParam['site_info'];
    // 支払期限日
    $arrSend['payment_limit_date'] = $arrOtherParam['payment_limit_date'];
    // 有効期限日
    $arrSend['bill_expiration_date'] = $arrOtherParam['bill_expiration_date'];
    // 利用者区分
    $arrSend['customer_type'] = 0;
    // 利用者姓
    $arrSend['customer_family_name'] = $arrInput['customer_family_name'];
    // 利用者名
    $arrSend['customer_name'] = $arrInput['customer_name'];
    // 利用者姓半角カナ
    $arrSend['customer_family_name_kana'] = mb_convert_kana($arrInput['customer_family_name_kana'],'k');
    $arrSend['customer_family_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['customer_family_name_kana']);
    // 利用者名半角カナ
    $arrSend['customer_name_kana'] = mb_convert_kana($arrInput['customer_name_kana'],'k');
    $arrSend['customer_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['customer_name_kana']);
    // 利用者郵便番号
    $arrSend['customer_zip_code'] = $arrInput['customer_zip01'] . $arrInput['customer_zip02'];
    // 利用者住所1
    $objMasterData = new SC_DB_MasterData_Ex();
    $arrPref = $objMasterData->getMasterData("mtb_pref", array("pref_id", "pref_name", "rank"));
    $arrSend['customer_address_1'] = $arrPref[$arrInput['customer_pref']] . $arrInput['customer_addr01'];
    // 利用者住所2
    $arrSend['customer_address_2'] = $arrInput['customer_addr02'];
    // 利用者電話区分
    $arrSend['customer_tel_type'] = $arrInput['customer_tel_division'];
    // 利用者電話番号
    $arrSend['customer_tel'] = $arrInput['customer_tel01'] . "-" . $arrInput['customer_tel02'] . "-" . $arrInput['customer_tel03'];

    // 電文の送付




    foreach($arrSend as $key => $val) {
        // Shift-JISにエンコードする必要あり
        $enc_val = mb_convert_encoding($val, "Shift-JIS", CHAR_CODE);
        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_CONVENI_CALL, $p, $uniqid, $arrInput);

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentATM
 * 処理内容：ATM決済情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentATM($arrData, $arrInput, $uniqid) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // ATM決済用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_ATM . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_ATM, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    // 決済金額
    $arrSend['payment_amount'] = $arrData['payment_total'];
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
    // 決済内容
    $arrSend['payment_detail'] = $arrOtherParam['payment_detail'];
    // 決済内容半角カナ
    $arrSend['payment_detail_kana'] = mb_convert_kana($arrOtherParam['payment_detail'],'k');
    $arrSend['payment_detail_kana'] = preg_replace("/ｰ/", "-", $arrSend['payment_detail_kana']);
    // 支払期限日
    $arrSend['payment_limit_date'] = $arrOtherParam['payment_limit_date'];

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $enc_val = mb_convert_encoding($val, CHAR_CODE_KS, CHAR_CODE);

        if (!reverseCheck($val, $enc_val)) {
            return createErrorReturnFront($key);
        }

        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_ATM, $p, $uniqid, $arrInput);

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentBANK
 * 処理内容：銀行NET決済情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentBANK($arrData, $arrInput, $order_id, $transactionid) {
// 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // 銀行NET用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_BANK . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_BANK, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    //$arrSend['bank_code'] = $arrInput['bank_code'];
    // 決済金額
    $arrSend['amount'] = $arrData['payment_total'];
    // 請求内容カナ
    $arrSend['claim_kana'] = mb_convert_kana($arrOtherParam['claim_kana'],'k');
    $arrSend['claim_kana'] = preg_replace("/ｰ/", "-", $arrSend['claim_kana']);
    // 請求内容漢字
    // TODO : $arrOtherParam['claim_kanji']の内容がおかしい気がする・・・。
    $arrSend['claim_kanji'] = $arrOtherParam['claim_kanji'];
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
    // PC-Mobile区分
    /*
     * 0:PC
     * 1:docomo
     * 2:au
     * 3:softbank
     */
    // TODO : 電文に存在しないパラメータ
    $arrSend['pc_mobile_type'] = '0';
    // 店舗名
    // TODO : $arrOtherParam['claim_kanji']に店舗名が入っている。
    $arrSend['merchant_name'] = $arrOtherParam['claim_kanji'];
    // 完了後の戻りＵＲＬ
    $arrSend['return_url'] = HTTP_URL. "index.php";
    // 戻りボタンＵＲＬ
    $arrSend['stop_return_url'] = HTTP_URL. "index.php?" . TRANSACTION_ID_NAME . "=" . $transactionid;
    // コピーライト
    $arrSend['copy_right'] = $arrOtherParam['copy_right'];
    // 自由メモ欄
    $arrSend['free_memo'] = $arrOtherParam['free_memo'];
    // 支払期間(0DDhhmm)
    $arrSend['asp_payment_term'] = sprintf("0%02d0000", $arrOtherParam['asp_payment_term']);

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $enc_val = mb_convert_encoding($val, CHAR_CODE_KS, CHAR_CODE);

        if (!reverseCheck($val, $enc_val)) {
            return createErrorReturnFront($key);
        }

        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_BANK, $p, $order_id, $arrInput);

    return $arrRet;
}

/**
 * 携帯キャリア決済申込電文（ID=100）を送信する。
 *
 * @param $arrData 受注情報
 * @param $arrInput 入力情報
 * @param $order_id 受注ID
 * @param $transactionid EC-CUBE側のトランザクションID
 * @param $pc_mobile_type PC/Mobile区分
 * @param $open_id OpenID
 * @return 応答情報
 */
function sfSendPaygentCareer($arrData, $arrInput, $order_id, $transactionid, $pc_mobile_type, $open_id = "") {

	// 支払方法情報テーブル（dtb_payment）から、携帯キャリア決済に関する情報を取得
	$arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '" . PAY_PAYGENT_CAREER . "'");

	// --- パラメータを設定 ------------------------------
	// 共通ヘッダ
	$arrSend = sfGetPaygentShare(PAYGENT_CAREER, $arrData['order_id'], $arrPaymentDB[0]);

	// キャリア種別
	if ($arrInput['career_type'] === CAREER_MOBILE_TYPE_DOCOMO
		|| (empty($arrData['memo04']) && $arrData['memo08'] === PAYGENT_CAREER_AUTH_D)) {
		// キャリア決済選択で「ドコモ払い」が選ばれた場合、
		// または、ドコモへの認証要求が正常に完了し、リダイレクトで戻って来た場合
		$arrSend['career_type'] = CAREER_TYPE_DOCOMO;

	} else if ($arrInput['career_type'] === CAREER_MOBILE_TYPE_AU
		|| (empty($arrData['memo04']) && $arrData['memo08'] === PAYGENT_CAREER_AUTH_A)) {
		// キャリア決済選択で「auかんたん決済」が選ばれた場合、
		// または、au への認証要求が正常に完了し、リダイレクトで戻って来た場合
		$arrSend['career_type'] = CAREER_TYPE_AU;

	} else if ($arrInput['career_type'] === CAREER_MOBILE_TYPE_SOFTBANK) {
		// キャリア決済選択で「ソフトバンク」が選ばれた場合
		$arrSend['career_type'] = CAREER_TYPE_SOFTBANK;

	} else {
		$arrSend['career_type'] = $arrInput['career_type'];
	}

	// 請求金額
	$arrSend['amount'] = $arrData['payment_total'];
	// UID
	// $arrSend['uid'] = $_SERVER['HTTP_X_JPHONE_UID'];
	// オーソリ通知URL
	$arrSend['return_url'] = HTTPS_URL . "shopping/load_payment_module.php?mode=career_auth&order_id=" . $order_id . "&" . TRANSACTION_ID_NAME . "=" . $transactionid . '&hash=' . createPaygentHash($arrData);
	// キャンセル通知URL
	$arrSend['cancel_url'] = HTTPS_URL . "shopping/load_payment_module.php?mode=career_auth_cancel&order_id=" . $order_id . "&" . TRANSACTION_ID_NAME . "=" . $transactionid . '&hash=' . createPaygentHash($arrData);
	if ($arrSend['career_type'] === CAREER_TYPE_DOCOMO) {
		// 他決済用URL
		$arrSend['other_url'] = $arrSend['cancel_url'];
	}
	// PC/Mobile区分
	$arrSend['pc_mobile_type'] = $pc_mobile_type;
	// OpenId
	$arrSend['open_id'] = $open_id;

	// --- 電文を送信 ------------------------------
	// 接続モジュールのインスタンスを取得、及び初期化
	$p = new PaygentB2BModule();
	$p->init();

	foreach ($arrSend as $key => $val) {
		// Shift-JIS でエンコードをした値を設定
		$p->reqPut($key, mb_convert_encoding($val, "Shift-JIS", CHAR_CODE));
	}

	// 電文を送信
	$p->post();

	// 応答情報を取得
	$arrRet = sfPaygentResponse(PAYGENT_CAREER, $p, $order_id, $arrInput, $arrData);

	return $arrRet;
}

/**
 * 携帯キャリア決済ユーザ認証要求電文（ID=104）を送信する。
 *
 * @param $arrData 受注情報
 * @param $arrInput 入力情報
 * @param $order_id 受注ID
 * @param $transactionid EC-CUBE側のトランザクションID
 * @param $pc_mobile_type PC/Mobile区分
 * @return 応答情報
 */
function sfSendPaygentAuthCareer($arrData, $arrInput, $order_id, $transactionid, $pc_mobile_type) {

	// 支払方法情報テーブル（dtb_payment）から、携帯キャリア決済に関する情報を取得
	$arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '" . PAY_PAYGENT_CAREER . "'");

	// --- パラメータを設定 ------------------------------
	// 共通ヘッダ
	$arrSend = sfGetPaygentShare(PAYGENT_CAREER_COMMIT_AUTH, $arrData['order_id'], $arrPaymentDB[0]);

	// 認証OKURL
	$arrSend['redirect_url'] = HTTPS_URL . "shopping/load_payment_module.php?mode=career_authentication&order_id=" . $order_id . "&" . TRANSACTION_ID_NAME . "=" . $transactionid . '&hash=' . createPaygentHash($arrData);
	// 認証NGURL
	$arrSend['cancel_url'] = HTTPS_URL . "shopping/load_payment_module.php?mode=career_authentication_cancel&order_id=" . $order_id . "&" . TRANSACTION_ID_NAME . "=" . $transactionid . '&hash=' . createPaygentHash($arrData);
	// PC/Mobile区分
	$arrSend['pc_mobile_type'] = $pc_mobile_type;
	// キャリア種別
	if ($arrInput['career_type'] === CAREER_MOBILE_TYPE_DOCOMO) {
		$arrSend['career_type'] = CAREER_TYPE_DOCOMO;
	} else if ($arrInput['career_type'] === CAREER_MOBILE_TYPE_AU) {
		$arrSend['career_type'] = CAREER_TYPE_AU;
	} else {
		$arrSend['career_type'] = $arrInput['career_type'];
	}

	// --- 電文を送信 ------------------------------
	// 接続モジュールのインスタンスを取得、及び初期化
	$p = new PaygentB2BModule();
	$p->init();

	foreach ($arrSend as $key => $val) {
		// Shift-JIS でエンコードをした値を設定
		$p->reqPut($key, mb_convert_encoding($val, "Shift-JIS", CHAR_CODE));
	}

	// 電文を送信
	$p->post();

    // 応答情報を取得
    $arrRet = sfPaygentResponse(PAYGENT_CAREER_COMMIT_AUTH, $p, $order_id, $arrInput);

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentEMoney
 * 処理内容：電子マネー決済情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentEMoney($arrData, $arrInput, $order_id, $phpSessionId, $transactionid) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // EMoney用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_EMONEY . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_EMONEY, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    // 電子マネー種別
    if($arrInput['emoney_type'] == 1) {
    	$arrSend['emoney_type'] = EMONEY_TYPE_WEBMONEY;
    }
    // 決済金額
    $arrSend['amount'] = $arrData['payment_total'];
    // 受付完了通知URL
    $arrSend['return_url'] = HTTPS_URL . "shopping/load_payment_module.php?mode=emoney_commit&order_id=". $order_id. "&". TRANSACTION_ID_NAME . "=" . $transactionid . '&hash=' . createPaygentHash($arrData);
    // キャンセルURL
    $arrSend['cancel_url'] = HTTPS_URL . "shopping/load_payment_module.php?mode=emoney_commit_cancel&order_id=". $order_id. "&". TRANSACTION_ID_NAME . "=" . $transactionid . '&hash=' . createPaygentHash($arrData);
    // PC-MOBILE区分
    // webmoneyでは必要ない

    // 電文の送付
    foreach($arrSend as $key => $val) {
        // Shift-JISにエンコードする必要あり
        $enc_val = mb_convert_encoding($val, "Shift-JIS", CHAR_CODE);
        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_EMONEY, $p, $order_id, $arrInput);

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentYahoowallet
 * 処理内容：Yahoo!ウォレット決済情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentYahoowallet($arrData, $order_id, $transactionid) {
	// 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // Yahoo!ウォレット用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_YAHOOWALLET . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_YAHOOWALLET, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    // 決済金額
    $arrSend['payment_amount'] = $arrData['payment_total'];
    // 受付完了通知URL
    $arrSend['return_url'] = HTTPS_URL . "shopping/load_payment_module.php?mode=yahoowallet_commit&order_id=". $order_id. "&". TRANSACTION_ID_NAME . "=" . $transactionid;
    // 電文の送付
    foreach($arrSend as $key => $val) {
        // Shift-JISにエンコードする必要あり
        $enc_val = mb_convert_encoding($val, "Shift-JIS", CHAR_CODE);
        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_YAHOOWALLET, $p, $order_id);

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentVirtualAccount
 * 処理内容：仮想口座決済情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentVirtualAccount($arrData, $arrInput, $uniqid) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // 仮想口座決済用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_VIRTUAL_ACCOUNT . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_VIRTUAL_ACCOUNT, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    // 請求金額
    $arrSend['claim_amount'] = $arrData['payment_total'];
    // 請求先名
    $arrSend['billing_name'] = $arrInput['billing_family_name'] . $arrInput['billing_name'];
    // 請求先名ｶﾅ
    $arrSend['billing_name_kana'] = $arrInput['billing_family_name_kana'] . $arrInput['billing_name_kana'];
    $arrSend['billing_name_kana'] = mb_convert_kana($arrSend['billing_name_kana'],'k');
    $arrSend['billing_name_kana'] = preg_replace("/ｰ/", "-", $arrSend['billing_name_kana']);
    $arrSend['billing_name_kana'] = preg_replace("/ﾞ|ﾟ/", "", $arrSend['billing_name_kana']);
    // 支払期限日数
    $arrSend['expire_days'] = $arrOtherParam['payment_limit_date'];

    $is_fix = false;
    $is_first = false;
    if (0 < $arrData['customer_id']) {
        // ログインユーザ
        $objHelperCustomer = new SC_Helper_Customer_Ex();
        $arrCustomer = $objHelperCustomer->sfGetCustomerData($arrData['customer_id']);
        if (isset($arrCustomer['virtual_account_bank_code']) &&
            isset($arrCustomer['virtual_account_branch_code']) &&
            isset($arrCustomer['virtual_account_number'])) {
            // 仮想口座番号発番済み
            $is_fix = true;
        } else if ($arrOtherParam['numbering_type'] === '1') {
            // 仮想口座番号未発番 && 付番区分："1"固定付番
            $is_fix = true;
            $is_first = true;
        }
    }

    if ($is_fix) {
        /** 固定付番 **/
        // 付番区分
        $arrSend['numbering_type'] = '1';
        // 固定付番登録先
        $arrSend['fix_numbering_reg'] = $arrData['customer_id'];
        if (!$is_first) {
            // 仮想口座金融機関
            $arrSend['virtual_account_bank_code'] = $arrCustomer['virtual_account_bank_code'];
            // 仮想口座支店
            $arrSend['virtual_account_branch_code'] = $arrCustomer['virtual_account_branch_code'];
            // 仮想口座番号
            $arrSend['virtual_account_number'] = $arrCustomer['virtual_account_number'];
        }
    } else {
        /** 回転付番 **/
        // 付番区分
        $arrSend['numbering_type'] = '0';
    }

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $enc_val = mb_convert_encoding($val, CHAR_CODE_KS, CHAR_CODE);

        if (!reverseCheck($val, $enc_val)) {
            return createErrorReturnFront($key);
        }

        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_VIRTUAL_ACCOUNT, $p, $arrData['order_id'], $arrInput);

    if ($arrRet['result'] === "0") {
        // 処理結果コードが0の場合は顧客情報を更新
        deleteVirtualAccountInf(
            $arrData['customer_id'],
            $arrRet['virtual_account_bank_code'],
            $arrRet['virtual_account_branch_code'],
            $arrRet['virtual_account_number']);
        if ($is_first) {
            updateVirtualAccountInfo(
                $arrData['customer_id'],
                $arrRet['virtual_account_bank_code'],
                $arrRet['virtual_account_branch_code'],
                $arrRet['virtual_account_number']);
        }
    }

    return $arrRet;
}

/**
 * 関数名：sfSendPaygentLaterPayment
 * 処理内容：後払い決済情報の送信
 * 戻り値：取得結果
 */
function sfSendPaygentLaterPayment($arrData, $arrInput, $uniqid, $invoice_send_type) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // 後払い決済用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_LATER_PAYMENT . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_LATER_PAYMENT, $arrData['order_id'], $arrPaymentDB[0]);

    /** 個別電文 **/
    $arrSend += sfGetPaygentLaterPaymentModule($arrData['order_id']);
    // 結果取得区分
    $arrSend['result_get_type'] = $arrOtherParam['result_get_type'];
    //請求書送付方法
    $arrSend['invoice_send_type'] = $invoice_send_type;

    //同梱の場合は配送先をクリア(JACCSの仕様に準拠するため)
    if ($invoice_send_type == INVOICE_SEND_TYPE_INCLUDE) {
        $arrSend = clearShipParam($arrSend);
    }

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $enc_val = mb_convert_encoding($val, CHAR_CODE_KS, CHAR_CODE);

        if (!reverseCheck($val, $enc_val)) {
            return createErrorReturnFront($key);
        }

        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $arrRet = sfPaygentResponse(PAYGENT_LATER_PAYMENT, $p, $arrData['order_id'], $arrInput);

    return $arrRet;
}

/**
 * 関数名：reverseCheck
 * 処理内容：SJIS-win未対応文字の有無チェック
 * 戻り値：true:無/false:有
 */
function reverseCheck($val, $enc_val) {

    // UTF-8に戻す
    $reverse_val = mb_convert_encoding($enc_val, CHAR_CODE, CHAR_CODE_KS);

    // オリジナルと差分がある場合は拡張文字が欠落していると判断
    if ($val == $reverse_val) {
        return true;
    } else {
        return false;
    }
}

/**
 * 関数名：createErrorReturnFront
 * 処理内容：エンコードエラー時の返却値を生成する(フロント)
 * 戻り値：エラー情報の配列
 */
function createErrorReturnFront($key) {
    // 電文のフォーマットエラーと同様のエラーメッセージを表示するため、
    // 電文からフォーマットエラーが返却された場合と同等のレスポンスをセットする
    $arrRet['code'] = "P008";
    $arrRet['response_detail'] = $key;
    $arrRet['result'] = 1;
    $arrRet['response'] = "（P008）";
    return $arrRet;
}

/**
 * 関数名：createErrorReturnAdmin
 * 処理内容：エンコードエラー時の返却値を生成する(受注管理)
 * 戻り値：エラー情報の配列
 */
function createErrorReturnAdmin($key, $kind) {
    $arrReturn['kind'] = $kind;
    $arrReturn['return'] = false;
    $arrReturn['response'] = $key."に使用できない文字が含まれています。";
    return $arrReturn;
}

/**
 * 関数名：getInvoiceSendType
 * 処理内容：請求書送付方法が同梱か別送かを判定する
 * 戻り値：判定結果
 */
function getInvoiceSendType($order_id) {

    // 後払い決済用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_LATER_PAYMENT . "'");
    $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);

    //「決済モジュール設定画面で同梱が設定されている場合」かつ「注文者と配送先が同じ場合」
    if ($arrOtherParam['invoice_include'] && isSameOrderShip($order_id)) {
        return OPTION_INVOICE_SEND_TYPE_INCLUDE;
    } else {
        return INVOICE_SEND_TYPE_SEPARATE;
    }
}

/**
 * 関数名：isSameOrderShip
 * 処理内容：注文者と配送先が同じかどうかを返す
 * 戻り値：結果
 */
function isSameOrderShip($order_id) {

    $objQuery =& SC_Query_Ex::getSingletonInstance();
    // 受注情報
    $arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($order_id));
    $arrOrder = reset($arrOrder);

    $objPurchase = new SC_Helper_Purchase_Ex();
    // 配送情報
    $arrShippings = $objPurchase->getShippings($arrOrder['order_id'], false);
    $arrShippings = reset($arrShippings);

    //配送先側の照合項目を定義
    $arrCompareParam = array(
        array("order_name01","shipping_name01"),
        array("order_name02","shipping_name02"),
        array("order_kana01","shipping_kana01"),
        array("order_kana02","shipping_kana02"),
        array("order_zip01","shipping_zip01"),
        array("order_zip02","shipping_zip02"),
        array("order_pref","shipping_pref"),
        array("order_addr01","shipping_addr01"),
        array("order_addr02","shipping_addr02"),
        array("order_tel01","shipping_tel01"),
        array("order_tel02","shipping_tel02"),
        array("order_tel03","shipping_tel03"),
    );

    $isSame = true;

    foreach ($arrCompareParam AS $compareParam) {
        if ($arrOrder[$compareParam[0]] != $arrShippings[$compareParam[1]]) {
            $isSame = false;
            break;
        }
    }
    return $isSame;
}

/**
 * 関数名：clearShipParam
 * 処理内容：配送先関連のパラメータをクリア
 * 戻り値：クリア後の配列
 */
function clearShipParam($arrParam) {

    $arrClearParam = array(
        "ship_name_kanji",
        "ship_name_kana",
        "ship_zip_code",
        "ship_address",
        "ship_tel"
    );

    foreach ($arrClearParam AS $clearParam) {
        $arrParam[$clearParam] = "";
    }

    return $arrParam;
}

/**
 * 関数名：clearShipParamLink
 * 処理内容：リンクタイプの配送先関連のパラメータをクリア
 * 戻り値：クリア後の配列
 */
function clearShipParamLink($arrParam) {

    $arrClearParam = array(
        "ship_family_name",
        "ship_name",
        "ship_family_name_kana",
        "ship_name_kana",
        "ship_zip_code",
        "ship_address",
        "ship_tel"
    );

    foreach ($arrClearParam AS $clearParam) {
        $arrParam[$clearParam] = "";
    }

    return $arrParam;
}

/**
 * 関数名：deleteVirtualAccountInf
 * 処理内容：指定した仮想口座情報が指定した顧客以外に使用されている場合に削除する。
 */
function deleteVirtualAccountInf($customer_id, $bank_code, $branch_code, $number) {

    $objQuery =& SC_Query_Ex::getSingletonInstance();
    $dest = array();
    $dest['virtual_account_bank_code'] = null;
    $dest['virtual_account_branch_code'] = null;
    $dest['virtual_account_number'] = null;
    $objQuery->update('dtb_customer', $dest,
        'customer_id <> ? and virtual_account_bank_code = ? and virtual_account_branch_code = ? and virtual_account_number = ?',
        array($customer_id, $bank_code, $branch_code, $number));
}

/**
 * 関数名：updateVirtualAccountInfo
 * 処理内容：指定した仮想口座情報を顧客に紐付ける。
 */
function updateVirtualAccountInfo($customer_id, $bank_code, $branch_code, $number) {

    $objQuery =& SC_Query_Ex::getSingletonInstance();
    $dest = array();
    $dest['virtual_account_bank_code'] = $bank_code;
    $dest['virtual_account_branch_code'] = $branch_code;
    $dest['virtual_account_number'] = $number;
    $objQuery->update('dtb_customer', $dest, 'customer_id = ?', array($customer_id));

}

/**
 * 関数名：sfPaygentResponse
 * 処理内容：応答を処理する
 * 戻り値：取得結果
 */
function sfPaygentResponse($telegram_kind, $objPaygent, $orderId, $arrInput, $arrData=null) {

    $objPurchase = new SC_Helper_Purchase_Ex();
    $objCartSess = new SC_CartSession_Ex();

    $arrConvenience = getConvenience();

    // 処理結果取得（共通）
    $resultStatus = $objPaygent->getResultStatus(); # 処理結果 0=正常終了, 1=異常終了
    $responseCode = $objPaygent->getResponseCode(); # 異常終了時、レスポンスコードが取得できる
    $responseDetail = $objPaygent->getResponseDetail(); # 異常終了時、レスポンス詳細が取得できる
    $responseDetail = mb_convert_encoding($responseDetail, CHAR_CODE, "Shift-JIS");

    // 取得した値をログに保存する。
    if ($resultStatus == 1) {
        $arrResOther['result'] = $resultStatus;
        $arrResOther['code'] = $responseCode;
        $arrResOther['detail'] = $responseDetail;
        foreach($arrResOther as $key => $val) {
            GC_Utils::gfPrintLog($key."->".$val, PAYGENT_LOG_PATH);
        }
    }

    // レスポンスの取得
    while($objPaygent->hasResNext()) {
        # データが存在する限り、取得
        $arrRes[] = $objPaygent->resNext(); # 要求結果取得
    }

    // 決済毎に異なる処理
    switch($telegram_kind) {
    // クレジット決済の場合
    case PAYGENT_CREDIT:
        // 空の配列を格納しておく
        $arrVal["memo02"] = serialize(array());
        break;

	// コンビニ決済（番号方式）の場合
	case PAYGENT_CONVENI_NUM:
		// お支払可能なコンビニ
		$cvsLine = "";

		// 応答情報.利用可能コンビニ企業CD を文字列に変換し、"," で結合する
		$arrCvs = split("-", $arrRes[0]['usable_cvs_company_id']);
		foreach ($arrCvs as $val) {
		    if (array_key_exists($val, $arrConvenience)) {
		        if ($cvsLine !== "") {
		            $cvsLine .= ",";
		        }
		        $cvsLine .= $arrConvenience[$val];
		    }
		}

		// 決済ベンダ受付番号
		$receiptNumName = "";
		// 特記事項
		$confirmMemo = "";

		// 選択されたコンビニ毎の処理
		switch ($arrInput['cvs_company_id']) {
			// セブンイレブンの場合
			case CODE_SEVENELEVEN:
				$receiptNumName = "払込票番号";
				$confirmMemo = "";
				break;

			// デイリーヤマザキの場合
			case CODE_YAMAZAKI:
				if (in_array(CODE_SEICOMART, $arrCvs)) {
					$receiptNumName = "受付番号";
				} else {
					$receiptNumName = "ケータイ／オンライン決済番号";
					$confirmMemo = $arrConvenience[CODE_LOWSON] . "、" . $arrConvenience[CODE_MINISTOP];
					if (in_array(CODE_FAMILYMART, $arrCvs)) {
						$confirmMemo .= "、" . $arrConvenience[CODE_FAMILYMART];
					}
					$confirmMemo .= "でのお支払いには下記の確認番号も必要となります";
				}
				break;

			// ローソン、ミニストップの場合
			case CODE_LOWSON:
			case CODE_MINISTOP:
				if (in_array(CODE_SEICOMART, $arrCvs)) {
					$receiptNumName = "受付番号";
				} else {
					$receiptNumName = "お客様番号";
					$confirmMemo = $arrConvenience[CODE_LOWSON] . "、" . $arrConvenience[CODE_MINISTOP];
					if (in_array(CODE_FAMILYMART, $arrCvs)) {
						$confirmMemo .= "、" . $arrConvenience[CODE_FAMILYMART];
					}
					$confirmMemo .= "でのお支払いには下記の確認番号も必要となります";
				}
				break;

			// ファミリーマートの場合
			case CODE_FAMILYMART:
				if (in_array(CODE_LOWSON, $arrCvs)) {
					$receiptNumName = "お客様番号";
					$confirmMemo = $arrConvenience[CODE_LOWSON] . "、" . $arrConvenience[CODE_MINISTOP] . "、" . $arrConvenience[CODE_FAMILYMART] . "でのお支払いには下記の確認番号も必要となります";
				} else {
					// 課題No.110 対応 コンビニ接続タイプA の場合
					$receiptNumName = "収納番号";
					$confirmMemo = "";
				}
				break;

			// セイコーマートの場合
			case CODE_SEICOMART:
				$receiptNumName = "お客様の受付番号";
				$confirmMemo = "";
				break;

			default:
				break;
		}

		// --- memo02 にパラメータを設定 ------------------------------
		// タイトル
		$arrMemo['title'] = sfSetConvMSG("コンビニお支払", true);
		// 決済ベンダ受付番号
		$arrMemo['receipt_number'] = sfSetConvMSG($receiptNumName, $arrRes[0]['receipt_number']);
		// 電話番号
		if (in_array(CODE_SEICOMART, $arrCvs)) {
			// イーコンの場合は電話番号を表示
			$arrMemo['customer_tel'] = sfSetConvMSG("電話番号", $arrInput['customer_tel']);
		}

		// 払込票URL（結果URL情報）
		if ($arrInput['cvs_company_id'] === CODE_SEVENELEVEN && ! SC_MobileUserAgent::isMobile()) {
			// 選択されたコンビニが「セブンイレブン」で、携帯端末でない場合のみ指定
			$arrMemo['receipt_print_url'] = sfSetConvMSG("払込票URL", $arrRes[0]['receipt_print_url']);
		}

		// お支払可能なコンビニ
		$arrMemo['usable_cvs_company_id'] = sfSetConvMSG("お支払可能なコンビニ", $cvsLine);
		// お支払期日（支払期限日）
		$arrMemo['payment_limit_date'] = sfSetConvMSG("お支払期日", date("Y年m月d日", strtotime($arrRes[0]['payment_limit_date'])));

		// お支払方法の説明
		if (! SC_MobileUserAgent::isMobile()) {
			$arrMemo['help_url'] = sfSetConvMSG("お支払方法の説明", "http://www.paygent.co.jp/merchant_info/help/shophelp_cvs.html");
		} else {
			$arrMemo['help_url'] = sfSetConvMSG("お支払方法の説明", "http://www.paygent.co.jp/mb/pay_help/conv.html");
		}

		// 特記事項、確認番号
		if ($confirmMemo !== "") {
			$arrMemo['confirm_memo'] = sfSetConvMSG("特記事項", $confirmMemo);
			$arrMemo['confirm_number'] = sfSetConvMSG("確認番号", "400008");
		}

		$arrVal["memo02"] = serialize($arrMemo);

		break;

    // コンビニ(払込票方式)決済の場合
    case PAYGENT_CONVENI_CALL:
        break;
    // ATM決済の場合
    case PAYGENT_ATM:
        // タイトルを設定する
        $arrMemo['title'] = sfSetConvMSG("ATMお支払", true);
        $arrMemo['pay_center_number'] = sfSetConvMSG("収納機関番号", $arrRes[0]['pay_center_number']);
        $arrMemo['customer_number'] = sfSetConvMSG("お客様番号", $arrRes[0]['customer_number']);
        $arrMemo['conf_number'] = sfSetConvMSG("確認番号", $arrRes[0]['conf_number']);
        // 支払期日
        $arrMemo['payment_limit_date'] = sfSetConvMSG("お支払期日", date("Y年m月d日", strtotime($arrRes[0]['payment_limit_date'])));
        // ヘルプ画面
        if (SC_MobileUserAgent::isMobile()) {
            $arrMemo['help_url'] = sfSetConvMSG("お支払方法の説明", "http://www.paygent.co.jp/mb/pay_help/atm.html");
        } else {
            $arrMemo['help_url'] = sfSetConvMSG("お支払方法の説明", "http://www.paygent.co.jp/merchant_info/help/shophelp_atm.html");
        }
        // 受注テーブルに保存
        $arrVal["memo02"] = serialize($arrMemo);

        $arrRes[0]['code'] = $responseCode;
        $arrRes[0]['response_detail'] = $responseDetail;

        break;
    // 銀行ネットの場合
    case PAYGENT_BANK:
        $arrMemo['title'] = sfSetConvMSG("銀行ネットお支払", true);
        $arrMemo['pay_message'] = sfSetConvMSG("お支払について", "支払期限日までに下記URLからお支払を完了してください。\nお支払の手続を途中で中断された場合も、こちらから再手続が可能です。");
        $arrMemo['pay_url'] = sfSetConvMSG("お支払URL", $arrRes[0]['asp_url']);
        $arrVal["memo02"] = serialize($arrMemo);

        $arrRes[0]['code'] = $responseCode;
        $arrRes[0]['response_detail'] = $responseDetail;

        break;
    // キャリア決済の場合
    case PAYGENT_CAREER:
        $arrVal['quick_flg'] = "1";
        // 空の配列を格納しておく
        $arrVal["memo02"] = serialize(array());
        // 支払画面フォームをデコード
        if (isset($arrRes[0]['redirect_html'])) {
            $arrRes[0]['redirect_html'] = mb_convert_encoding($arrRes[0]['redirect_html'], CHAR_CODE, "Shift-JIS");
        }
        // 初期ステータスを設定する。
        $arrVal["status"] = $arrInitStatus[PAYGENT_CAREER];
        break;
    case PAYGENT_CAREER_COMMIT_AUTH:
    	// 支払画面フォームをデコード
        if (isset($arrRes[0]['redirect_html'])) {
            $arrRes[0]['redirect_html'] = mb_convert_encoding($arrRes[0]['redirect_html'], CHAR_CODE, "Shift-JIS");
        }
        // 初期ステータスを設定する。
        $arrVal["status"] = $arrInitStatus[PAYGENT_CAREER];
        // 空の配列を格納しておく
        $arrVal["memo02"] = serialize(array());
    	break;
    case PAYGENT_EMONEY:
		// 空の配列を格納しておく
        $arrVal["memo02"] = serialize(array());
        // クイック決済用
        $quick_memo['emoney_type'] = $arrInput['emoney_type'];
        $arrVal['quick_memo'] = serialize($quick_memo);
        break;
    case PAYGENT_YAHOOWALLET:
    	// 空の配列を格納しておく
        $arrVal["memo02"] = serialize(array());
        $arrVal['quick_memo'] = serialize(array());
        break;
    // 仮想口座決済の場合
    case PAYGENT_VIRTUAL_ACCOUNT:
        $arrMemo['title'] = sfSetConvMSG("銀行お振込", true);
        $arrMemo['bank_name'] = sfSetConvMSG("金融機関名",
            mb_convert_encoding($arrRes[0]['virtual_account_bank_name'], CHAR_CODE, "Shift-JIS")."(".$arrRes[0]['virtual_account_bank_code'].")");
        $arrMemo['branch_name'] = sfSetConvMSG("支店名",
            mb_convert_encoding($arrRes[0]['virtual_account_branch_name'], CHAR_CODE, "Shift-JIS")."(".$arrRes[0]['virtual_account_branch_code'].")");

        if ($arrRes[0]['virtual_account_deposit_kind'] == 1) {
            $deposit_kind = '普通預金';
        } else if ($arrRes[0]['virtual_account_deposit_kind'] == 2) {
            $deposit_kind = '当座預金';
        } else if ($arrRes[0]['virtual_account_deposit_kind'] == 4) {
            $deposit_kind = '貯蓄預金';
        } else {
            $deposit_kind = "";
        }
        $arrMemo['deposit_kind'] = sfSetConvMSG("預金種目名", $deposit_kind);
        $arrMemo['number'] = sfSetConvMSG("口座番号", $arrRes[0]['virtual_account_number']);
        $arrMemo['expire_date'] = sfSetConvMSG("お支払期日", date("Y年m月d日", strtotime($arrRes[0]['expire_date'])));
        $arrMemo['blank'] = sfSetConvMSG("", "");
        $arrMemo['info1'] = sfSetConvMSG("", "お振込先にペイジェントグチと表示されます。");
        $arrMemo['info2'] = sfSetConvMSG("", "お支払期日までに上記お支払先へお振込みをお願いします。");
        // 受注テーブルに保存
        $arrVal["memo02"] = serialize($arrMemo);

        if ($resultStatus !== "0") {
            $arrRes[0]['code'] = $responseCode;
        }

        $arrRes[0]['code'] = $responseCode;
        $arrRes[0]['response_detail'] = $responseDetail;

        break;
    case PAYGENT_LATER_PAYMENT:
        if ($resultStatus == 1) {
            $arrRes[0]['code'] = $responseCode;
            if ($responseCode === '15007') {
                $arrMemo['title'] = sfSetConvMSG("後払い決済", true);
                $arrMemo['info1'] = sfSetConvMSG("", "後払い決済（アトディーネ）の審査が保留になりました。");
                $arrMemo['info2'] = sfSetConvMSG("", "審査の結果が出次第改めてご連絡いたします。");
                $arrMemo['info3'] = sfSetConvMSG("", "もうしばらくお待ちください。");
                $arrMemo['info4'] = sfSetConvMSG("", "（審査の結果がでるまで1,2日かかります。）");
                // 受注テーブルに保存
                $arrVal["memo02"] = serialize($arrMemo);
            }
            $arrRes[0]['response_detail'] = $responseDetail;
        }
        break;
    default:
        break;
    }

    // 受注テーブルに記録する
    $arrVal["memo01"] = MDL_PAYGENT_CODE;        // 処理結果

    // memo02は、支払情報を格納
    $arrVal["memo03"] = $resultStatus;        // 処理結果
    $arrVal["memo04"] = $responseCode;        // レスポンスコード
    $arrVal["memo05"] = $responseDetail;    // エラーメッセージ
    $arrVal["memo06"] = $arrRes[0]['payment_id'];        // 承認番号
    $arrVal["memo07"] = "";                    // ステータス取得で使用

    // キャリアの場合はキャリアタイプ(1:docomo,2:au,3:softbank)を$telegram_kindに追記
    if(strlen($arrInput['career_type']) > 0) {
        $arrVal["memo08"] = $telegram_kind . "_" . $arrInput['career_type'];
    // 画面入力値(キャリアタイプ)がnull且つ汎用項目8に値があり、その値が"104_1"の場合
    } else if (!isset($arrInput['career_type']) && isset($arrData['memo08']) && $arrData['memo08'] == PAYGENT_CAREER_AUTH_D) {
    	$arrVal["memo08"] = PAYGENT_CAREER_D;
    // 画面入力値(キャリアタイプ)がnull且つ汎用項目8に値があり、その値が"104_2"の場合
    } else if (!isset($arrInput['career_type']) && isset($arrData['memo08']) && $arrData['memo08'] == PAYGENT_CAREER_AUTH_A) {
    	$arrVal["memo08"] = PAYGENT_CAREER_A;
    // 電子マネーの場合は(1:WebMoney)を$telegram_kindに追記
    } else if (strlen($arrInput['emoney_type']) > 0) {
    	$arrVal["memo08"] = $telegram_kind . "_" . $arrInput['emoney_type'];
    // Yahoo!ウォレットの場合は汎用項目8に電文種別をセットする
    } else if ($telegram_kind == PAYGENT_YAHOOWALLET) {
    	$arrVal["memo08"] = $telegram_kind;
    } else {
        $arrVal["memo08"] = "";                // この段階では空にしておく
    }

    $arrVal["memo09"] = "";                    // カード、キャリア決済連携で使用
    $arrVal["memo10"] = "";                    // 再取得用のnotice_idを保存しておく

    // 受注テーブルの更新
    $objPurchase->registerOrder($orderId, $arrVal);

    // 結果とメッセージを返却
    $arrRes[0]['result'] = $resultStatus;
    if (preg_match('/^[P|E]/', $responseCode) <= 0) {
        $arrRes[0]['response'] = "<br />". $responseDetail. "（". $responseCode. "）";
    } elseif (strlen($responseCode) > 0) {
        $arrRes[0]['response'] = "（". $responseCode. "）";
    } else {
        $arrRes[0]['response'] = "";
    }
    return $arrRes[0];
}

/**
 * 関数名：sfPaygentResponseCard
 * 処理内容：応答を処理する
 * 戻り値：取得結果
 */
function sfPaygentResponseCard($telegram_kind, $objPaygent, $customer_id) {
    $objQuery =& SC_Query_Ex::getSingletonInstance();

    // 処理結果取得（共通）
    $resultStatus = $objPaygent->getResultStatus(); # 処理結果 0=正常終了, 1=異常終了
    $responseCode = $objPaygent->getResponseCode(); # 異常終了時、レスポンスコードが取得できる
    $responseDetail = $objPaygent->getResponseDetail(); # 異常終了時、レスポンス詳細が取得できる
    $responseDetail = mb_convert_encoding($responseDetail, CHAR_CODE, "Shift-JIS");

    // 異常終了
    if ($resultStatus == 1) {
        $arrResOther['result'] = $resultStatus;
        $arrResOther['code'] = $responseCode;
        $arrResOther['detail'] = $responseDetail;
        foreach($arrResOther as $key => $val) {
            GC_Utils::gfPrintLog($key."->".$val, PAYGENT_LOG_PATH);
        }

    // 正常終了
    } else {
        // レスポンスの取得
        $arrRes[0]['result'] = $resultStatus;
        while($objPaygent->hasResNext()) {
            // データが存在する限り取得
            $arrRes[] = $objPaygent->resNext(); # 要求結果取得
        }
        $num_card = (isset($arrRes[1]['num_of_cards'])) ? $arrRes[1]['num_of_cards'] : "0";
        switch($telegram_kind) {
        // カード情報設定
        case PAYGENT_CARD_STOCK_SET:
            if ($num_card > 0) {
                $objQuery->update("dtb_customer", array("paygent_card" => 1), "customer_id = ?", array($customer_id));
            }
            break;
        // カード情報削除
        case PAYGENT_CARD_STOCK_DEL:
            if ($num_card <= 0) {
                $objQuery->update("dtb_customer", array("paygent_card" => 0), "customer_id = ?", array($customer_id));
            }
            break;
        default:
            break;
        }
    }

    // 結果とメッセージを返却
    if (preg_match('/^[P|E]/', $responseCode) <= 0) {
        $arrRes[0]['response'] = "<br />". $responseDetail. "（". $responseCode. "）";
    } else {
        $arrRes[0]['response'] = "（". $responseCode. "）";
    }
    return $arrRes;
}

/**
 * 関数名：sfSetConvMSG
 * 処理内容：コンビニ情報表示用
 * 戻り値：取得結果
 */
function sfSetConvMSG($name, $value){
    return array("name" => $name, "value" => $value);
}

/**
 * 関数名：sfGetPaymentDB
 * 処理内容：必要なデータを取得する。
 * 戻り値：取得結果
 */
function sfGetPaymentDB($module_code, $where = "", $arrWhereVal = array()){
    $objQuery =& SC_Query_Ex::getSingletonInstance();

    $arrVal = array($module_code);
    $arrVal = array_merge($arrVal, $arrWhereVal);

    $arrRet = array();
    $sql = "SELECT
                module_code,
                memo01 as merchant_id,
                memo02 as connect_id,
                memo03 as payment,
                memo04 as connect_password,
                memo05 as other_param
            FROM dtb_payment WHERE module_code = ? AND del_flg = 0 ". $where;
    $arrRet = $objQuery->getall($sql, $arrVal);
    return $arrRet;
}

/**
 * KSシステムからの決済情報差分通知、または決済情報差分照会の応答情報を受けて、
 * 受注情報テーブル（dtb_order）の更新処理の振り分けを行う。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function sfUpdatePaygentOrder($objQuery, $arrRet, $arrConfig) {

    if ($arrRet['payment_type'] == PAYMENT_TYPE_PAIDY) {
        // Paidy
        updatePaygentOrderPaidy($objQuery, $arrRet, $arrConfig);

    } elseif ($arrRet['payment_type'] == PAYMENT_TYPE_LATER_PAYMENT) {
        // 後払い決済
        updatePaygentOrderLaterPayment($objQuery, $arrRet, $arrConfig);

    } elseif ($arrRet['payment_type'] == PAYMENT_TYPE_ATM) {
        // ATM
        updatePaygentOrderAtm($objQuery, $arrRet, $arrConfig);

    } elseif ($arrRet['payment_type'] == PAYMENT_TYPE_BANK) {
        // 銀行ネット
        updatePaygentOrderBank($objQuery, $arrRet, $arrConfig);

    } elseif ($arrRet['payment_type'] == PAYMENT_TYPE_CREDIT) {
        // クレジット
        updatePaygentOrderCredit($objQuery, $arrRet, $arrConfig);

    } elseif ($arrRet['payment_type'] == PAYMENT_TYPE_CAREER) {
        // 携帯キャリア
        updatePaygentOrderCareer($objQuery, $arrRet, $arrConfig);

    } elseif ($arrRet['payment_type'] == PAYMENT_TYPE_CONVENI_NUM) {
        // コンビニ番号方式
        updatePaygentOrderConveniNum($objQuery, $arrRet, $arrConfig);

    } elseif ($arrRet['payment_type'] == PAYMENT_TYPE_EMONEY) {
        // 電子マネー
        updatePaygentOrderEmoney($objQuery, $arrRet, $arrConfig);

    } elseif ($arrRet['payment_type'] == PAYMENT_TYPE_VIRTUAL_ACCOUNT) {
        // 仮想口座
        updatePaygentOrderVirtualAccount($objQuery, $arrRet, $arrConfig);
    } else {
        logTrace("invalid payment_type!");
    }
}

/**
 * 受注情報テーブル（dtb_order）の更新を行う。ATM決済用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderAtm($objQuery, $arrRet, $arrConfig) {

    switch ($arrRet['payment_status']) {

        case STATUS_PRE_CLEARED: // "40"：消込済

            // 受注状態 = "6"：入金済み
            $arrVal['status'] = ORDER_PRE_END;

            // 入金日時 = 応答情報.支払日時
            if ($arrRet['payment_date'] != "") {
                $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
            }

            break;

        case STATUS_PAYMENT_EXPIRED: // "12"：支払期限切

            // 受注状態 = "3"：キャンセル
            $arrVal['status'] = ORDER_CANCEL;
            break;
    }

    update_order_common($objQuery, $arrRet, $arrConfig, $arrVal);
}

/**
 * 受注情報テーブル（dtb_order）の更新を行う。銀行ネット決済用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderBank($objQuery, $arrRet, $arrConfig) {

    switch ($arrRet['payment_status']) {

        case STATUS_PRE_CLEARED: // "40"：消込済

            // 受注状態 = "6"：入金済み
            $arrVal['status'] = ORDER_PRE_END;

            // 銀行ネット決済の場合、memo06 = 決済ID
            $arrVal['memo06'] = $arrRet['payment_id'];

            // 入金日時 = 応答情報.支払日時
            if ($arrRet['payment_date'] != "") {
                $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
            }

            break;
    }

    // memo07 = 決済ステータス
    $arrVal['memo07'] = $arrRet['payment_status'];
    // memo10 = 決済通知ID
    $arrVal['memo10'] = $arrRet['payment_notice_id'];

    // 受注情報テーブル（dtb_order）から、memo06（決済ID）を取得
    $resultMemo06 = $objQuery->getOne("SELECT memo06 FROM dtb_order WHERE order_id = ?", array($arrRet['trading_id']));
    if (empty($resultMemo06)) {
        // 決済ID が空文字の場合、memo06 = 決済ID
        $arrVal['memo06'] = $arrRet['payment_id'];
    }

    // 受注情報テーブル（dtb_order）の更新
    $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($arrRet['trading_id']));
}

/**
 * 受注情報テーブル（dtb_order）の更新を行う。クレジット決済用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderCredit($objQuery, $arrRet, $arrConfig) {

    switch ($arrRet['payment_status']) {

        case STATUS_PRE_REGISTRATION: // "10"：申込済
        case STATUS_NG_AUTHORITY: // "11"：オーソリNG

            // 処理をスキップ(これをしないと差分照会の「申込済⇒オーソリNG」のケースで、モジュール型/混合型間の挙動に差が出てしまう。)
            return;

        case STATUS_PRE_CLEARED: // "40"：消込済

            // 受注状態 = "6"：入金済み
            $arrVal['status'] = ORDER_PRE_END;

            // 入金日時 = 応答情報.支払日時
            if ($arrRet['payment_date'] != "") {
                $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
            }

            break;

        case STATUS_AUTHORITY_OK: // "20"：オーソリOK

            // 受注状態 = "2"：入金待ち
            $arrVal['status'] = ORDER_PAY_WAIT;
            break;

        case STATUS_AUTHORITY_CANCELED: // "32"：オーソリ取消済
        case STATUS_AUTHORITY_EXPIRED: // "33"：オーソリ期限切
        case STATUS_PRE_SALES_CANCELLATION: // "60"：売上取消済

            // 受注状態 = "3"：キャンセル
            $arrVal['status'] = ORDER_CANCEL;
            break;
    }

    update_order_common($objQuery, $arrRet, $arrConfig, $arrVal);
}

/**
 * 受注情報テーブル（dtb_order）の更新を行う。携帯キャリア決済用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderCareer($objQuery, $arrRet, $arrConfig) {

    switch ($arrRet['payment_status']) {

        case STATUS_PRE_CLEARED: // "40"：消込済
        case STATUS_COMPLETE_CLEARED: // "44"：消込完了

            // 受注状態 = "6"：入金済み
            $arrVal['status'] = ORDER_PRE_END;

            // 入金日時 = 応答情報.支払日時
            if ($arrRet['payment_date'] != "") {
                $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
            }

            break;

        case STATUS_AUTHORITY_OK: // "20"：オーソリOK
        case STATUS_AUTHORITY_COMPLETED:  // "21"：オーソリ完了

            // 受注状態 = "2"：入金待ち
            $arrVal['status'] = ORDER_PAY_WAIT;
            break;

        case STATUS_AUTHORITY_CANCELED: // "32"：オーソリ取消済
        case STATUS_AUTHORITY_EXPIRED: // "33"：オーソリ期限切
        case STATUS_PRE_SALES_CANCELLATION: // "60"：売上取消済

            // 受注状態 = "3"：キャンセル
            $arrVal['status'] = ORDER_CANCEL;
            break;
    }

    update_order_common($objQuery, $arrRet, $arrConfig, $arrVal);
}

/**
 * 受注情報テーブル（dtb_order）の更新を行う。コンビニ番号方式決済用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderConveniNum($objQuery, $arrRet, $arrConfig) {

    switch ($arrRet['payment_status']) {
        case STATUS_PRE_CLEARED: // "40"：消込済
        case STATUS_PRELIMINARY_PRE_DETECTION: // "43"：速報検知済

            // 受注状態 = "6"：入金済み
            $arrVal['status'] = ORDER_PRE_END;

            // 入金日時 = 応答情報.支払日時
            if ($arrRet['payment_date'] != "") {
                $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
            }

            break;

        case STATUS_PAYMENT_EXPIRED: // "12"：支払期限切
        case STATUS_AUTHORITY_CANCELED: // "32"：オーソリ取消済
        case STATUS_PRELIMINARY_CANCELLATION: // "61"：速報取消済

            // 受注状態 = "3"：キャンセル
            $arrVal['status'] = ORDER_CANCEL;
            break;
    }

    update_order_common($objQuery, $arrRet, $arrConfig, $arrVal);
}

/**
 * 受注情報テーブル（dtb_order）の更新を行う。電子マネー決済用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderEmoney($objQuery, $arrRet, $arrConfig) {

    switch ($arrRet['payment_status']) {

        case STATUS_PRE_CLEARED: // "40"：消込済

            // 受注状態 = "6"：入金済み
            $arrVal['status'] = ORDER_PRE_END;

            // 入金日時 = 応答情報.支払日時
            if ($arrRet['payment_date'] != "") {
                $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
            }

            break;

        case STATUS_PRE_SALES_CANCELLATION: // "60"：売上取消済

            // 受注状態 = "3"：キャンセル
            $arrVal['status'] = ORDER_CANCEL;
            break;
    }

    update_order_common($objQuery, $arrRet, $arrConfig, $arrVal);
}

/**
 * 受注情報テーブル（dtb_order）の更新を行う。仮想口座決済用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderVirtualAccount($objQuery, $arrRet, $arrConfig) {

    if ($arrRet['trading_id'] != "") {
        // 決済ステータスごとに処理を分岐
        switch ($arrRet['payment_status']) {

            case STATUS_PRE_CLEARED: // "40"：消込済

                // 受注状態 = "6"：入金済み
                $arrVal['status'] = ORDER_PRE_END;

                $payment_id = $objQuery->getOne("SELECT memo06 FROM dtb_order WHERE order_id = ?", array($arrRet['trading_id']));
                if (($arrRet['clear_detail'] == null && ($payment_id == null || $payment_id != $arrRet['payment_id']))
                    || $arrRet['clear_detail'] == '03'
                    || $arrRet['clear_detail'] == '04'
                    || $arrRet['clear_detail'] == '05'
                ) {
                    // 仮想口座決済で入金処理詳細が異常入金のパターンの場合、
                    // エラーメールを送信してDB更新はスキップする。
                    // ここに入らないパターンは通常通り更新する。
                    sendVirtualAccountErrorMail($arrRet);
                    return;
                }

                // 更新時にペイジェント状況を設定する
                $clear_detail = empty($arrRet['clear_detail']) ? "01" : $arrRet['clear_detail'];
                $arrVal['memo09'] = PAYGENT_VIRTUAL_ACCOUNT . '_' . $clear_detail;

                // 入金日時 = 応答情報.支払日時
                if ($arrRet['payment_date'] != "") {
                    $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
                }

                break;

            case STATUS_PAYMENT_EXPIRED: // "12"：支払期限切
            case STATUS_PAYMENT_INVALIDITY_NO_CLEAR: // "16"：支払期限切（消込対象外）

                // 受注状態 = "3"：キャンセル
                $arrVal['status'] = ORDER_CANCEL;

                // 仮想口座決済はペイジェント状況を設定する
                $arrVal['memo09'] = PAYGENT_VIRTUAL_ACCOUNT . '_' . '99';

                break;
        }

        update_order_common($objQuery, $arrRet, $arrConfig, $arrVal);

    } else if ($arrRet['clear_detail'] == '05') {
        // $arrRet['clear_detail'] == '05' 消込対象請求なし の場合、取引ID == null がありえる
        sendVirtualAccountErrorMail($arrRet);
    }
}

/**
 * 受注情報テーブル（dtb_order）更新の共通処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 * @param $arrVal 更新後の値
 */
function update_order_common($objQuery, $arrRet, $arrConfig, $arrVal) {

    // memo07 = 決済ステータス
    $arrVal['memo07'] = $arrRet['payment_status'];
    // memo10 = 決済通知ID
    $arrVal['memo10'] = $arrRet['payment_notice_id'];

    if ($arrConfig['settlement_division'] == SETTLEMENT_MIX) {
        // システム種別が "3"：混合型 の場合、
        // 受注情報テーブル（dtb_order）から、memo06（決済ID）を取得
        $resultMemo06 = $objQuery->getOne("SELECT memo06 FROM dtb_order WHERE order_id = ?", array($arrRet['trading_id']));
        if (empty($resultMemo06) || in_array($arrRet['payment_status'], getPaymentIdUpdateStatus())) {
            // 「決済IDが空文字」もしくは「決済IDが更新されるステータス」の場合、memo06 = 決済ID
            // 混合型でペイジェントオンラインからオーソリ変更/売上変更して決済が新規作成された場合に、ここでmemo06を新決済IDに更新してやらないといけない。
            // 後払いオーソリNG⇒コンビニ速報検知済のケースも決済IDが更新される。
            $arrVal['memo06'] = $arrRet['payment_id'];
            // 決済ID を更新条件（update 文の where 句）に含めないようにする
            unset($arrRet['payment_id']);
        }
    }

    // 受注情報テーブル（dtb_order）の更新
    if ($arrRet['payment_id'] != '') {
        $objQuery->update("dtb_order", $arrVal, "order_id = ? AND memo06 = ?", array($arrRet['trading_id'], $arrRet['payment_id']));
    } else {
        $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($arrRet['trading_id']));
    }
}

/**
 * KSシステムからの決済情報差分通知、または決済情報差分照会の応答情報を受けて、
 * 受注情報テーブル（dtb_order）の更新を行う。後払い決済用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderLaterPayment($objQuery, $arrRet, $arrConfig) {

    // 受注状態
    $arrOrder = $objQuery->select("status,memo07,memo09", "dtb_order", "order_id = ?", array($arrRet['trading_id']));
    switch ($arrRet['payment_status']) {
        case STATUS_AUTHORIZE_NG:
            // 10：オーソリNG
            $arrVal['status'] = ORDER_CANCEL;
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_NG;
            break;
        case STATUS_AUTHORIZED_BEFORE_PRINT:
            // 19：オーソリOK(印字データ取得前)
            if ($arrOrder[0]['memo09'] == PAYGENT_LATER_PAYMENT_ST_CLEAR_REQ_FIN) {
                // 35 → 19 の変遷は 35 が差分通知対象外なことによる巻き戻りなので処理対象外
                return;
            }
            $arrVal['status'] = ORDER_NEW;
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT;
            break;
        case STATUS_AUTHORIZED:
            // 20：オーソリOK
            if ($arrOrder[0]['memo09'] == PAYGENT_LATER_PAYMENT_ST_CLEAR_REQ_FIN) {
                // 35 → 20 の変遷は 35 が差分通知対象外なことによる巻き戻りなので処理対象外
                return;
            }
            $arrVal['status'] = ORDER_NEW;
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZED;
            break;
        case STATUS_AUTHORIZE_CANCEL:
            // 32：オーソリ取消済
            $arrVal['status'] = ORDER_CANCEL;
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_CANCEL;
            break;
        case STATUS_AUTHORIZE_EXPIRE:
            // 33：オーソリ期限切
            $arrVal['status'] = ORDER_CANCEL;
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_EXPIRE;
            break;
        case STATUS_SALES_RESERVE:
            // 36：売上保留
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_SALES_RESERVE;
            break;
        case STATUS_CLEAR:
            // 40：消込済
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_CLEAR;
            // 入金日時 = 応答情報.支払日時
            if ($arrRet['payment_date'] != "") {
                $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
            }
            break;
        case STATUS_CLEAR_SALES_CANCEL_INVALIDITY:
            // 41：消込済（取消期限切）
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_CLEAR_SALES_CANCEL_INVALIDITY;
            break;
        case STATUS_SALES_CANCEL:
            // 60：売上取消済
            $arrVal['status'] = ORDER_CANCEL;
            $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_SALES_CANCEL;
            break;
    }

    // memo07 = 決済ステータス
    $arrVal['memo07'] = $arrRet['payment_status'];
    // memo10 = 決済通知ID
    $arrVal['memo10'] = $arrRet['payment_notice_id'];
    // memo02 = null;
    $arrVal['memo02'] = "";
    // memo05 = null;
    $arrVal['memo05'] = "";

    if ($arrConfig['settlement_division'] == SETTLEMENT_MIX) {
        $resultMemo06 = $objQuery->getOne("SELECT memo06 FROM dtb_order WHERE order_id = ?", array($arrRet['trading_id']));
        if (empty($resultMemo06)) {
            // 決済ID が空文字の場合、memo06 = 決済ID
            $arrVal['memo06'] = $arrRet['payment_id'];
        }
    }

    $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($arrRet['trading_id']));

    if ($arrOrder[0]['memo09'] == PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_RESERVE
        && ($arrVal['memo09'] == PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_NG || $arrVal['memo09'] == PAYGENT_LATER_PAYMENT_ST_AUTHORIZED)) {
        // オーソリ保留からオーソリNG/オーソリOKに変更
        // モジュール以外ではEC-CUBE上のステータスはオーソリ保留にならないのでこの処理には入らない
        $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '" . PAY_PAYGENT_LATER_PAYMENT . "'");
        $arrOtherParam = unserialize($arrPaymentDB[0]['other_param']);
        if ($arrOtherParam['exam_result_notification_type'] == "1") {
            return;
        }
        // 審査結果を通知する
        $arrLaterPaymentStatus = getArrLaterPaymentExampResult();
        $header = "お待たせいたしました。" . "\r\n"
                . "以下のご注文の後払い決済（アトディーネ）審査結果がでました。" . "\r\n" . "\r\n"
                . $arrLaterPaymentStatus[$arrVal['memo09']];
        $objHelperMail = new SC_Helper_Mail_Ex();
        $objHelperMail->sfSendOrderMail(
            $arrRet['trading_id']
            , "1"
            , "後払い審査結果のお知らせ"
            , $header);
    }
}

/**
 * 受注情報テーブル（dtb_order）の更新を行う。PAIDY用処理。
 *
 * @param $objQuery SC_Query
 * @param $arrRet 応答情報
 * @param $arrConfig モジュール設定情報
 */
function updatePaygentOrderPaidy($objQuery, $arrRet, $arrConfig) {
    // 受注情報 取得
    $arrOrder = $objQuery->select("payment_id,status,memo06,memo07,memo09,payment_total,memo02", "dtb_order", "order_id = ?", array($arrRet['trading_id']));
    if (count($arrOrder[0]) <= 0) {
        // 存在しなかった場合
        logTrace("マーチャント取引ID ->" . $arrRet['trading_id'] . "が一致するデータは受注情報に存在しません。");
        return;
    }
    // Paidy用パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '". PAY_PAYGENT_PAIDY . "'");

    // 決済IDと取引ID取得
    $arrInput = array(
            'payment_id'   => $arrRet['payment_id'],
            'trading_id'   => $arrRet['trading_id'],
            'payment_db'   => $arrPaymentDB[0]
    );
    // 汎用項目2に格納されているシリアライズ配列を配列に変換
    $arrMemo02 = unserialize($arrOrder[0]['memo02']);
    // 既にオーソリOKにしている注文IDの場合、オーソリ取消にする。
    if ($arrRet['payment_status'] == STATUS_AUTHORIZED && isset($arrMemo02['ecOrderData']['paidy_payment_id'])) {
        // オーソリキャンセル
        $arrInput['telegram_kind'] = PAYGENT_PAIDY_AUTH_CANCELED;
        $arrRetCancel = sendPaygentTelegramCommon($arrInput);

        if ($arrRetCancel['result'] == 0) {
            GC_Utils::gfPrintLog("既にオーソリOKにしている受注IDに、別決済IDのオーソリ通知が来ているためペイジェント側の注文のオーソリ取消を行いました。", PAYGENT_LOG_PATH);
        }

        return;
    }
    // 決済金額照合 差分通知_POST.決済金額 と 受注情報.汎用項目2決済金額を比較する。
    if ($arrRet['payment_amount'] != $arrMemo02['ecOrderData']['payment_total']) {
        if ($arrRet['payment_status'] == STATUS_AUTHORIZED) {
            // 決済情報照会
            $arrInput['telegram_kind'] = PAYGENT_SETTLEMENT_DETAIL;
            $arrRetSettlement = sendPaygentTelegramCommon($arrInput);

            if ($arrRetSettlement['result'] == 0) {
                // 電文が正常
                if ( $arrRetSettlement['paidy_payment_id'] != $arrRet['paidy_payment_id']) {
                    // 応答電文.Paidy決済IDとPOST.Paidy決済IDが違う時
                    // 改竄検知のログを出力して終了
                    GC_Utils::gfPrintLog("ペイジェントに注文が存在しないため処理を中断しました。", PAYGENT_LOG_PATH);
                    return;
                }
            } else {
                // 電文が異常
                return;
            }

            // 決済金額照合 受注情報.汎用項目2 決済金額 と決済情報照会電文_応答電文.決済金額を比較する。
            if ($arrMemo02['ecOrderData']['payment_total'] == $arrRetSettlement['payment_amount']) {
                // curl攻撃検知
                logTrace("決済ID -> " . $arrRet['payment_id'] . "マーチャント取引ID ->" . $arrRet['trading_id'] .
                        "決済金額 -> ". $arrRet['payment_amount'] . "が一致するデータは受注情報に存在しません。");
            } else {
                // 金額改竄アラートフラグを立てる
                $arrMemo02['ecOrderData']['payment_total_check_status'] = PAYMENT_AMOUNT_UNMATCH;
                // 汎用項目2 汎用項目2.決済金額照合ステータス
                $arrVal['memo02'] = serialize($arrMemo02);
                // 受注情報テーブル（dtb_order）の更新
                $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($arrRet['trading_id']));
                // 金額改竄通知メール送信
                sendPaidySettlementAmountMismatchMail($arrRet,$arrMemo02['ecOrderData']['payment_total']);
            }
        } else {
            // curl攻撃検知
            logTrace("決済ID -> " . $arrRet['payment_id'] . "マーチャント取引ID ->" . $arrRet['trading_id'] .
                    "決済金額 -> ". $arrRet['payment_amount'] . "が一致するデータは受注情報に存在しません。");
        }
        return;
    }
    switch ($arrRet['payment_status']) {

        case STATUS_AUTHORIZED: // "20" : オーソリOK
            if ($arrOrder[0]['status'] == ORDER_CANCEL) {
                // オーソリキャンセル
                $arrInput['telegram_kind'] = PAYGENT_PAIDY_AUTH_CANCELED;
                $arrRetCancel = sendPaygentTelegramCommon($arrInput);

                // 取得した値をログに保存する。
                if ($arrRetCancel['result'] == 0) {
                    GC_Utils::gfPrintLog("EC-CUBE側で注文がキャンセルされているためペイジェント側の注文のオーソリ取消を行いました。", PAYGENT_LOG_PATH);
                }

                return;
            }
            // 注文受付メールを送信する。
            $objHelperMail = new SC_Helper_Mail_Ex();
            $objHelperMail->sfSendOrderMail($arrRet['trading_id'],"1");

            // 受注状態 = "2"：入金待ち
            $arrVal['status'] = ORDER_PAY_WAIT;
            // 汎用項目2.決済金額照合ステータス = 0 : 決済金額一致
            $arrMemo02['ecOrderData']['payment_total_check_status'] = PAYMENT_AMOUNT_MATCH;
            // 汎用項目2.paidy決済ID
            $arrMemo02['ecOrderData']['paidy_payment_id'] = $arrRet['paidy_payment_id'];
            // 汎用項目2 汎用項目2.決済金額照合ステータス
            $arrVal['memo02'] = serialize($arrMemo02);
            // 汎用項目6 決済ID
            $arrVal['memo06'] = $arrRet['payment_id'];
            // 汎用項目9 オーソリOK
            $arrVal['memo09'] = PAYGENT_PAIDY_AUTHORIZED;

            break;

        case STATUS_AUTHORIZE_CANCEL: // "32" : オーソリ取消済

            // 受注状態 = "3"：キャンセル
            $arrVal['status'] = ORDER_CANCEL;

            // オーソリ取消済
            $arrVal['memo09'] = PAYGENT_PAIDY_AUTH_CANCELED;
            break;

        case STATUS_AUTHORIZE_EXPIRE: // "33" : オーソリ期限切

            // 受注状態 = "3"：キャンセル
            $arrVal['status'] = ORDER_CANCEL;
            // オーソリ期限切れ
            $arrVal['memo09'] = PAYGENT_PAIDY_AUTH_EXPIRED;
            break;

        case STATUS_PRE_CLEARED: // "40"：消込済

            // 受注状態 = "6"：入金済み
            $arrVal['status'] = ORDER_PRE_END;
            $arrVal['memo09'] = PAYGENT_PAIDY_COMMIT;
            // 入金日時 = 応答情報.支払日時
            if ($arrRet['payment_date'] != "") {
                $arrVal['payment_date'] = date("Y-m-d H:i:s", strtotime($arrRet['payment_date']));
            }

            // 新しく決済ID割り振られた場合は更新する。
            if ($arrOrder[0]['memo06'] != $arrRet['payment_id'] ) {
                $arrVal['memo06'] = $arrRet['payment_id'];
            }

            break;

        case STATUS_CLEAR_SALES_CANCEL_INVALIDITY: // "41"：消込済（売上取消期限切）

            $arrVal['memo09'] = PAYGENT_PAIDY_COMMIT_EXPIRED;
            break;

        case STATUS_SALES_CANCEL: // 60：売上取消済

            $arrVal['memo09'] = PAYGENT_PAIDY_COMMIT_CANCELED;
            if (0 < $arrMemo02['ecOrderData']['payment_total']) {
                // 決済金額0円より大きければ、キャンセルとする
                $arrVal['status'] = ORDER_CANCEL;
            }
            break;
    }

    // memo07 = 決済ステータス
    $arrVal['memo07'] = $arrRet['payment_status'];
    // memo10 = 決済通知ID
    $arrVal['memo10'] = $arrRet['payment_notice_id'];
    // 受注情報テーブル（dtb_order）の更新
    $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($arrRet['trading_id']));
}

/**
 * 関数名：sfPaygentOrder($paygent_type)
 * 処理内容：受注連携
 * 戻り値：取得結果
 */
function sfPaygentOrder($paygent_type, $order_id, $payment_id = '', $beforeStatus = '', $arrRequest = array()) {
    $arrDispKind = getDispKind();

    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $objPaygent = new PaygentB2BModule();
    $objPaygent->init();

    // 設定パラメータの取得
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE);

    $objQuery =& SC_Query_Ex::getSingletonInstance();

    // 処理分岐
    switch($paygent_type) {
    case 'change_auth':
    case 'change_commit_auth':
        $kind = PAYGENT_CREDIT;
        break;
    case 'auth_cancel':
    case 'change_auth_cancel':
        $kind = PAYGENT_AUTH_CANCEL;
        break;
    case 'card_commit':
    case 'change_commit':
        $kind = PAYGENT_CARD_COMMIT;
        break;
    case 'card_commit_cancel':
    case 'change_commit_cancel':
        $kind = PAYGENT_CARD_COMMIT_CANCEL;
        break;
    case 'career_commit':
    	$arrTelegram = array(PAYGENT_CAREER_COMMIT);
    	$kind = $arrTelegram[0];
        break;
    case 'change_carrer_auth':
    	// 売上変更ボタンが押下された場合
    	$arrTelegram = sfCheckRevice($objQuery, $order_id);
        $kind = PAYGENT_CAREER_COMMIT_REVICE;
        break;
    case 'career_commit_cancel':
        $kind = PAYGENT_CAREER_COMMIT_CANCEL;
        break;
    case 'emoney_cancel':
        $kind = PAYGENT_EMONEY_COMMIT_CANCEL;
        break;
    case 'change_emoney':
        // 売上変更ボタンが押下された場合
    	$arrTelegram = sfCheckRevice($objQuery, $order_id);
        $kind = PAYGENT_EMONEY_COMMIT_REVICE;
        break;
    case 'yahoowallet_commit':
    	$kind = PAYGENT_YAHOOWALLET_COMMIT;
    	break;
    case 'yahoowallet_cancel':
    	$kind = PAYGENT_YAHOOWALLET_COMMIT_CANCEL;
    	break;
	case 'change_yahoowallet':
		// 売上変更ボタンが押下された場合
    	$arrTelegram = sfCheckRevice($objQuery, $order_id);
        $kind = PAYGENT_YAHOOWALLET_COMMIT_REVICE;
    	break;
    case 'later_payment_print':
        // 後払い請求書印字データ出力
        $kind =PAYGENT_LATER_PAYMENT_PRINT;
        break;
    case 'later_payment_reduction':
        // 後払い決済 オーソリ変更
        $kind =PAYGENT_LATER_PAYMENT_REDUCTION;
        break;
    case 'later_payment_bill_reissue':
        // 後払い決済 請求書再発行
        $kind = PAYGENT_LATER_PAYMENT_BILL_REISSUE;
        break;
    case 'later_payment_clear':
        // 後払い決済 売上
        $kind = PAYGENT_LATER_PAYMENT_CLEAR;
        break;
    case 'later_payment_cancel':
        // 後払い決済 取消
        $kind = PAYGENT_LATER_PAYMENT_CANCEL;
        break;
    case 'paidy_commit':
        // Paidy 売上
        $kind = PAYGENT_PAIDY_COMMIT;
        break;
    case 'change_paidy':
        // Paidy 売上変更
        $kind = PAYGENT_PAIDY_REFUND;
        break;
    }

    if(count($arrReturn) === 0) {
	    // ペイジェントステータスのチェック
	    $status = $objQuery->get("memo09", "dtb_order", "order_id = ?", array($order_id));
	    if ((($status == PAYGENT_AUTH_CANCEL || $status == PAYGENT_CARD_COMMIT_CANCEL
	        || $status == PAYGENT_CARD_COMMIT_REVICE_PROCESSING || $status == PAYGENT_CREDIT_PROCESSING) && $beforeStatus === '')
	        || (($status == PAYGENT_CARD_COMMIT || $status == PAYGENT_CARD_COMMIT_REVICE) && $paygent_type == 'change_auth')) {
	        // ステータスが取消または売上変更処理中になっている、
	        // またはステータスが売上or売上変更になっているときのオーソリ変更は処理を中断
	        if ($paygent_type == change_commit) {
	            $arrReturn['kind'] = PAYGENT_CARD_COMMIT_REVICE;
	        } else {
	            $arrReturn['kind'] = $kind;
	        }

	        $arrReturn['return'] = false;
	        $arrReturn['response'] = "ステータス矛盾エラー";
	        return $arrReturn;
	    }
	    // 決済IDの取得
	    if(strlen($payment_id) === 0) {
	        $payment_id = $objQuery->get("memo06", "dtb_order", "order_id = ?", array($order_id));
	    }
	    // 共通データの取得
	    $arrSend = sfGetPaygentShare($kind, $order_id, $arrPaymentDB[0], $payment_id);
	    // $arrSendに個別詳細情報を付け加える
	    switch($paygent_type) {
	    case 'change_auth':
	    case 'change_commit_auth':
	        if ($paygent_type == 'change_auth') {
	            // ステータスをオーソリ変更処理中に変更
	            $arrVal['memo09'] = PAYGENT_CREDIT_PROCESSING;
	            $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($order_id));
	        }
	        $arrOrder = $objQuery->select("payment_total, quick_memo", "dtb_order", "order_id = ?", array($order_id));
	        $arrSend['payment_amount'] = $arrOrder[0]['payment_total'];
	        $arrSend['ref_trading_id'] = $order_id;
	        $arrPaymentParam = unserialize($arrOrder[0]['quick_memo']);
	        $arrSend['payment_class'] = isset($arrPaymentParam['payment_class']) ? $arrPaymentParam['payment_class'] : '';
	        $arrSend['split_count'] = isset($arrPaymentParam['split_count']) ? $arrPaymentParam['split_count'] : '';
	        $arrSend['3dsecure_ryaku'] = '1';
	        unset($arrSend['payment_id']);
	        break;
	    case 'change_carrer_auth':
		    // 携帯キャリア決済補正売上要求電文の場合
		    $arrSend['amount'] = $arrTelegram[0];
		    break;
	    case 'change_emoney':
	    	// 電子マネー決済補正売上要求電文の場合
		    $arrSend['amount'] = $arrTelegram[0];
		    break;
	    case 'change_yahoowallet':
	    	// Yahoo!ウォレット決済金額変更要求電文の場合
		    $arrSend['payment_amount'] = $arrTelegram[0];
		    break;
	    case 'change_commit':
	        // ステータスを売上変更処理中に変更
	        $arrVal['memo09'] = PAYGENT_CARD_COMMIT_REVICE_PROCESSING;
	        $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($order_id));
	        // 新規オーソリ処理
	        $arrRetAuth = sfPaygentOrder('change_commit_auth', $order_id, $payment_id, $status);
	        // オーソリ失敗
	        if($arrRetAuth['return'] == false) {
	            $arrRetAuth['kind'] = PAYGENT_CARD_COMMIT_REVICE;
	            return $arrRetAuth;
	        } else {
	            // 決済IDを更新
	            $arrSend['payment_id'] = $arrRetAuth['payment_id'];
	        }
	        break;
        case 'later_payment_reduction':
            // 後払い決済 オーソリ変更
            $arrSend += $arrRequest;
            $objPurchase = new SC_Helper_Purchase_Ex();
            $arrShippings = $objPurchase->getShippings($order_id, false);
            if (count($arrShippings) > 1) {
                // 配送先が複数件ある場合は後払い決済不可
                $arrReturn['kind'] = $kind;
                $arrReturn['return'] = false;
                $arrReturn['response'] = "後払い決済は複数配送先をご指定いただいた場合はご利用いただけません。<br>別の決済手段をご検討ください。";
                return $arrReturn;
            }
            $arrSend += sfGetPaygentLaterPaymentModule($order_id);
            //「請求書送付方法が同梱」かつ「注文者と配送先が同じ」場合
            if ($arrRequest['invoice_send_type'] == INVOICE_SEND_TYPE_INCLUDE && isSameOrderShip($order_id)) {
                //配送先をクリア(JACCSの仕様に準拠するため)
                $arrSend = clearShipParam($arrSend);
            }

            break;
        case 'later_payment_bill_reissue':
            // 後払い決済 請求書再発行
            $arrSend += $arrRequest;
            $arrSend['invoice_to'] = "1";
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($order_id));
            $arrSend['zip_code'] = $arrOrder[0]['order_zip01'] . $arrOrder[0]['order_zip02'];
            $masterData = new SC_DB_MasterData_Ex();
            $arrPref = $masterData->getMasterData('mtb_pref');
            $arrSend['address'] = $arrPref[$arrOrder[0]['order_pref']] . $arrOrder[0]['order_addr01'] . $arrOrder[0]['order_addr02'];
            break;
        case 'later_payment_clear':
            // 後払い決済 売上
            $arrSend += $arrRequest;
            break;
        case 'paidy_commit':
            // Paidy決済 売上
            $arrSend['trading_id'] = $order_id;
            $arrSend['payment_id'] = $payment_id;
            break;
        case 'paidy_cancel':
            // Paidy決済 取消
            $arrSend['trading_id'] = $order_id;
            $arrSend['payment_id'] = $payment_id;
            // 電文種別の設定
            if($status == PAYGENT_PAIDY_AUTHORIZED) {
                $arrSend['telegram_kind'] = PAYGENT_PAIDY_AUTH_CANCELED;
            } else if ($status == PAYGENT_PAIDY_COMMIT) {
                $arrSend['telegram_kind'] = PAYGENT_PAIDY_REFUND;
            } else {
                // 取消ボタン押下後、画面リロードを行う時
                $arrReturn['return'] = false;
                $arrReturn['kind'] = $status;
                $arrReturn['response'] = "ステータス矛盾エラー";
                return $arrReturn;
            }
            break;
        case 'change_paidy':
            // Paidy決済 売上変更
            $arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($order_id));
            $arrMemo2 = unserialize($arrOrder[0]['memo02']);
            if ($arrMemo2['ecOrderData']['payment_total'] <= $arrOrder[0]['payment_total']) {
                // 決済金額が増加する変更は、Paidy決済不可
                $arrReturn['kind'] = PAYGENT_PAIDY_COMMIT_REVICE;
                $arrReturn['return'] = false;
                $arrReturn['paidy_error'] = "エラー：決済金額が変更されていないか増加しています。";
                return $arrReturn;
            }

            $arrSend['trading_id'] = $order_id;
            $arrSend['payment_id'] = $payment_id;

            // 返金額
            $arrSend['amount'] = $arrMemo2['ecOrderData']['payment_total'] - $arrOrder[0]['payment_total'];
            break;
	    }

        if ($paygent_type === 'later_payment_reduction'
            || $paygent_type === 'later_payment_bill_reissue') {
            // 全角文字を送信する電文はSJISに変換する
            foreach($arrSend as $key => $val) {
                $enc_val = mb_convert_encoding($val, CHAR_CODE_KS, CHAR_CODE);

                if (!reverseCheck($val, $enc_val)) {
                    return createErrorReturnAdmin($key, $kind);
                }

                $arrSend[$key] = $enc_val;
            }
        }

	    // 電文の送付
	    foreach($arrSend as $key => $val) {
	        $objPaygent->reqPut($key, $val);
	    }
	    $objPaygent->post();

	    // レスポンスの取得
	    while($objPaygent->hasResNext()) {
	        # データが存在する限り、取得
	        $arrRes[] = $objPaygent->resNext(); # 要求結果取得
	    }
	    $arrRes[0]['result'] = $objPaygent->getResultStatus(); # 処理結果 0=正常終了, 1=異常終了

	    foreach($arrRes[0] as $key => $val) {
	        // Shift-JISで応答があるので、エンコードする。
	        $arrRes[0][$key] = mb_convert_encoding($val, CHAR_CODE, "Shift-JIS");
	        if ($arrRes[0]['result'] == 1) {
	            GC_Utils::gfPrintLog($key."->".$arrRes[0][$key], PAYGENT_LOG_PATH);
	        }
	    }

	    $arrReturn['kind'] = $kind;

	    // Paidy決済 取消 or 売上変更の時、取引結果表示用の取引名IDをセットする。
	    // Paidyでは電文IDと取引名IDが一致しないため。
	    if ($paygent_type == 'paidy_cancel') {
	        if($status == PAYGENT_PAIDY_AUTHORIZED) {
	           $arrReturn['kind'] = PAYGENT_PAIDY_AUTH_CANCELED;
	        } else if ($status == PAYGENT_PAIDY_COMMIT) {
	           $arrReturn['kind'] = PAYGENT_PAIDY_COMMIT_CANCELED;
	        }
	    } else if ($paygent_type == 'change_paidy') {
	        $arrReturn['kind'] = PAYGENT_PAIDY_COMMIT_REVICE;
	    }

	    $arrVal = array();
	    // 正常終了
	    if($arrRes[0]['result'] === '0') {
	        // オーソリ変更
	        switch($paygent_type) {
	        case 'change_commit_auth':
	            $arrReturn['payment_id'] = $arrRes[0]['payment_id'];
	            break;
	        case 'change_auth_cancel':
	        case 'change_commit_cancel':
	            break;
	        case 'change_auth':
	            // オーソリ変更前の決済に対してオーソリキャンセル電文を送信
	            $arrRetCancel = sfPaygentOrder('change_auth_cancel', $order_id, $payment_id, $status);
	            // オーソリキャンセル失敗
	            if($arrRetCancel['return'] == false) {
	                $arrVal['memo09'] = $kind;
	                $arrVal['memo06'] = $arrRes[0]['payment_id'];
	                $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($order_id));
	                return $arrRetCancel;
	            } else {
	                $arrVal['memo09'] = $kind;
	                $arrVal['memo06'] = $arrRes[0]['payment_id'];
	                $arrVal['memo05'] = '';
	            }
	            break;
	        // 売上変更
	        case 'change_commit':
	            // 売上変更前の決済に対して売上キャンセル電文を送信
	            $arrRetCancel = sfPaygentOrder('change_commit_cancel', $order_id, $payment_id, $status);
	            // 売上キャンセル失敗
	            if($arrRetCancel['return'] == false) {
	                $arrRetCancel['kind'] = PAYGENT_CARD_COMMIT_REVICE;
	                $arrVal['memo09'] = PAYGENT_CARD_COMMIT_REVICE;
	                $arrVal['memo06'] = $arrRes[0]['payment_id'];
	                $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($order_id));
	                return $arrRetCancel;
	            } else {
	                $arrReturn['kind'] = PAYGENT_CARD_COMMIT_REVICE;
	                $arrVal['memo09'] = PAYGENT_CARD_COMMIT_REVICE;
	                $arrVal['memo06'] = $arrRes[0]['payment_id'];
	                $arrVal['memo05'] = '';
	            }
	            break;
            case 'later_payment_print':
                // 後払い請求書印字データ出力

                //請求書印字データをCSV出力
                outputPrintCsv($arrRes[0]);

                $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZED;
                $arrVal['memo06'] = $arrRes[0]['payment_id'];
                $arrVal['memo05'] = '';

                //処理終了前にDB更新
                $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($order_id));

                //処理終了(これがないとCSVにHTMLが出力される)
                exit;

                break;
            case 'later_payment_reduction':
                // 後払いオーソリ変更
                if ($arrRequest['invoice_send_type'] == INVOICE_SEND_TYPE_INCLUDE) {
                    $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZED_BEFORE_PRINT;
                } else {
                    $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZED;
                }
                $arrVal['invoice_send_type'] = $arrRequest['invoice_send_type'];
                $arrVal['memo06'] = $arrRes[0]['payment_id'];
                $arrVal['memo05'] = '';
                break;
            case 'later_payment_cancel':
                // 後払い取消し
                $arrVal['memo06'] = $arrRes[0]['payment_id'];
                $arrVal['memo05'] = '';
                if ($status === PAYGENT_LATER_PAYMENT_ST_CLEAR) {
                    $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_SALES_CANCEL;
                } else {
                    $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_CANCEL;
                }
                break;
            case 'later_payment_clear':
                // 後払い決済 売上
                $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_CLEAR_REQ_FIN;
                $arrVal['memo06'] = $arrRes[0]['payment_id'];
                $arrVal['memo05'] = '';
                break;
            case 'later_payment_bill_reissue':
                // 後払い決済 請求書再発行
                $arrVal['memo06'] = $arrRes[0]['payment_id'];
                $arrVal['memo05'] = '';
                break;
            case 'paidy_commit':
                // Paidy決済 売上
                $arrVal['memo09'] = PAYGENT_PAIDY_COMMIT;
                $arrVal['memo06'] = $arrRes[0]['payment_id'];
                $arrVal['memo05'] = '';
                break;
            case 'paidy_cancel':
                // Paidy決済 取消
                if($status == PAYGENT_PAIDY_AUTHORIZED) {
                    $arrVal['memo09']  = PAYGENT_PAIDY_AUTH_CANCELED;
                } else if ($status == PAYGENT_PAIDY_COMMIT) {
                    $arrVal['memo09']  = PAYGENT_PAIDY_COMMIT_CANCELED;
                }
                $arrVal['memo06'] = $arrRes[0]['payment_id'];
                $arrVal['memo05'] = '';
                break;
            case 'change_paidy':
                // Paidy決済 売上変更
                $arrVal['memo06'] = $arrRes[0]['payment_id'];
                $arrVal['memo05'] = '';

                $arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($order_id));
                $arrMemo2 = unserialize($arrOrder[0]['memo02']);
                $arrMemo2['ecOrderData']['payment_total'] = $arrOrder[0]['payment_total'];
                $arrVal['memo02'] = serialize($arrMemo2);
                break;
	        default:
	            $arrVal['memo09'] = $kind;
	            $arrVal['memo06'] = $arrRes[0]['payment_id'];
	            $arrVal['memo05'] = '';
	            break;
	        }

	        $arrReturn['return'] = true;
	    } else {
	        $arrReturn['return'] = false;
	        $responseCode = $objPaygent->getResponseCode(); # 異常終了時、レスポンスコードが取得できる

	        if ($beforeStatus != '' && $paygent_type != 'change_auth_cancel') {
	           $arrVal['memo09'] = $beforeStatus;
	        } else {
	           $arrVal['memo09'] = $status;
	        }

	        switch($paygent_type) {
	        case 'change_commit':
	            // 売上変更に失敗
	            $arrVal['memo05'] = "変更後の金額での売上に失敗しました。（" . $responseCode . "）<br />取引ID:" . $order_id . ", 決済ID:" . $arrSend['payment_id'];
	            break;
	        // 売上変更時のオーソリに失敗
	        case 'change_commit_auth':
	            $arrVal['memo05'] = "新規のオーソリ確保に失敗しました。（" . $responseCode . "）<br />ペイジェントオンラインから売上変更してください。";
	            break;
	        // 売上変更時の売上キャンセルに失敗
	        case 'change_commit_cancel':
	            $arrVal['memo05'] = "変更後の金額による売上が成功しましたが、変更前の売上取消に失敗しました。（" . $responseCode . "）<br />同一取引IDで複数の売上が発生しているため、取引ID:" . $order_id . ", 決済ID:" . $payment_id . "の売上をペイジェントオンラインから取り消してください。";
	            break;
	        // オーソリ変更時のオーソリキャンセルに失敗
	        case 'change_auth_cancel':
	            $arrVal['memo05'] = "変更後の金額によるオーソリが成功しましたが、変更前のオーソリ取消に失敗しました。（" . $responseCode . "）<br />同一取引IDで複数のオーソリが発生しているため、取引ID:" . $order_id . ", 決済ID:" . $payment_id . "のオーソリをペイジェントオンラインから取り消してください。";
	            break;
            case 'later_payment_reduction':
                // 後払いオーソリ変更
                $responseDetail = $objPaygent->getResponseDetail(); # 異常終了時、レスポンス詳細が取得できる
                $responseDetail = mb_convert_encoding($responseDetail, CHAR_CODE, "Shift-JIS");

                if ($responseCode === '15013') {
                    $arrVal['memo05'] = "請求書送付方法を同梱にする場合は、お届け先情報を注文者情報に合わせて下さい。" . " エラーコード" . $responseCode;
                } else {
                    $arrVal['memo05'] = "エラー詳細 : ".$responseDetail . "エラーコード" . $responseCode;
                }

                if ($responseCode === '15007') {
                    // 保留
                    $arrReturn['return'] = true;
                    $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_RESERVE;
                    $arrVal['invoice_send_type'] = $arrRequest['invoice_send_type'];
                } else if ($responseCode === '15006') {
                    // NG
                    $arrVal['memo09'] = PAYGENT_LATER_PAYMENT_ST_AUTHORIZE_NG;
                    $arrVal['invoice_send_type'] = $arrRequest['invoice_send_type'];
                }
                break;
            case 'later_payment_print':
            case 'later_payment_cancel':
            case 'later_payment_bill_reissue':
            case 'later_payment_clear':
                $responseDetail = $objPaygent->getResponseDetail(); # 異常終了時、レスポンス詳細が取得できる
                $responseDetail = mb_convert_encoding($responseDetail, CHAR_CODE, "Shift-JIS");
                $arrVal['memo05'] = "エラー詳細 : ".$responseDetail . "エラーコード" . $responseCode;
                unset($arrVal['memo09']);
                break;
	        default:
	            $responseDetail = $objPaygent->getResponseDetail(); # 異常終了時、レスポンス詳細が取得できる
	            $responseDetail = mb_convert_encoding($responseDetail, CHAR_CODE, "Shift-JIS");
	            $arrVal['memo05'] = "エラー詳細 : ".$responseDetail . "エラーコード" . $responseCode;
	            if (preg_match('/^[P|E]/', $responseCode) <= 0) {
	                $arrReturn['response'] = $responseDetail. "（". $responseCode. "）";
	            } elseif (strlen($responseCode) > 0) {
	                $arrReturn['response'] = "（". $responseCode. "）";
	            } else {
	                $arrReturn['response'] = "";
	            }
	            break;
	        }
	    }

	    if(0 < count($arrVal)) {
	        $objQuery->update("dtb_order", $arrVal, "order_id = ?", array($order_id));
	    }
    }
    return $arrReturn;
}

/**
 * 関数名：outputPrintCsv
 * 処理内容：請求書印字データ出力
 */
function outputPrintCsv($arrPrintData) {

    //出力項目を定義
    $arrColumn = array(
        array("zip","郵便番号"),
        array("address1","住所1"),
        array("address2","住所2"),
        array("companyName","会社名"),
        array("sectionName","部署名"),
        array("name","氏名"),
        array("siteNameTitle","加盟店名タイトル"),
        array("siteName","請求書記載店舗名"),
        array("shopOrderIdTitle","加盟店取引IDタイトル"),
        array("shopOrderId","ご購入店受注番号"),
        array("descriptionText1","請求書記載事項1"),
        array("descriptionText2","請求書記載事項2"),
        array("descriptionText3","請求書記載事項3"),
        array("descriptionText4","請求書記載事項4"),
        array("descriptionText5","請求書記載事項5"),
        array("billServiceName","請求書発行元企業名"),
        array("billServiceInfo1","請求書発行元情報1"),
        array("billServiceInfo2","請求書発行元情報2"),
        array("billServiceInfo3","請求書発行元情報3"),
        array("billServiceInfo4","請求書発行元情報4"),
        array("billState1","請求書ステータス"),
        array("billFirstGreet1","宛名欄挨拶文欄1"),
        array("billFirstGreet2","宛名欄挨拶文欄2"),
        array("billFirstGreet3","宛名欄挨拶文欄3"),
        array("billFirstGreet4","宛名欄挨拶文欄4"),
        array("expand1","予備項目1"),
        array("expand2","予備項目2"),
        array("expand3","予備項目3"),
        array("expand4","予備項目4"),
        array("expand5","予備項目5"),
        array("expand6","予備項目6"),
        array("expand7","予備項目7"),
        array("expand8","予備項目8"),
        array("expand9","予備項目9"),
        array("expand10","予備項目10"),
        array("billedAmountTitle","請求金額タイトル"),
        array("billedAmount","請求金額"),
        array("billedFeeTax","請求金額消費税"),
        array("billOrderdayTitle","注文日タイトル"),
        array("shopOrderDate","注文日"),
        array("billSendDateTitle","請求書発行日タイトル"),
        array("billSendDate","請求書発行日"),
        array("billDeadlineDateTitle","お支払期限日タイトル"),
        array("billDeadlineDate","お支払期限日"),
        array("transactionIdTitle","お問い合せ番号タイトル"),
        array("transactionId","お問い合せ番号"),
        array("billBankInfomation","銀行振込注意文言"),
        array("bankNameTitle","銀行名タイトル"),
        array("bankName","銀行名漢字"),
        array("bankCode","銀行コード"),
        array("branchNameTitle","支店名タイトル"),
        array("branchName","支店名漢字"),
        array("branchCode","支店コード"),
        array("bankAccountNumberTitle","口座番号タイトル"),
        array("bankAccountKind","預金種別"),
        array("bankAccountNumber","口座番号"),
        array("bankAccountNameTitle","口座名義タイトル"),
        array("bankAccountName","銀行口座名義"),
        array("receiptBillDeadlineDate","払込取扱用支払期限日"),
        array("receiptName","払込取扱用購入者氏名"),
        array("invoiceBarcode","バーコード情報"),
        array("receiptCompanyTitle","収納代行会社名タイトル"),
        array("receiptCompany","収納代行会社名"),
        array("docketbilledAmount","請求金額"),
        array("docketCompanyName","受領証用購入者会社名"),
        array("docketSectionName","受領証用購入者部署名"),
        array("docketName","受領証用購入者氏名"),
        array("docketTransactionIdTitle","お問い合せ番号タイトル"),
        array("docketTransactionId","お問い合せ番号"),
        array("voucherCompanyName","払込受領書用購入者会社名"),
        array("voucherSectionName","払込受領書用購入者部署名"),
        array("voucherCustomerFullName","払込受領書用購入者氏名"),
        array("voucherTransactionIdTitle","払込受領書用お問い合せ番号タイトル"),
        array("voucherTransactionId","払込受領書用お問い合せ番号"),
        array("voucherBilledAmount","払込受領書用請求金額"),
        array("voucherBilledFeeTax","払込受領書用消費税金額"),
        array("revenueStampRequired","収入印紙文言"),
        array("goodsTitle","明細内容タイトル"),
        array("goodsAmountTitle","注文数タイトル"),
        array("goodsPriceTitle","単価タイトル"),
        array("goodsSubtotalTitle","金額タイトル"),
        array("goods1","明細内容1"),
        array("goodsAmount1","注文数1"),
        array("goodsPrice1","単価1"),
        array("goodsSubtotal1","金額1"),
        array("goodsExpand1","金額消費税1"),
        array("goods2","明細内容2"),
        array("goodsAmount2","注文数2"),
        array("goodsPrice2","単価2"),
        array("goodsSubtotal2","金額2"),
        array("goodsExpand2","金額消費税2"),
        array("goods3","明細内容3"),
        array("goodsAmount3","注文数3"),
        array("goodsPrice3","単価3"),
        array("goodsSubtotal3","金額3"),
        array("goodsExpand3","金額消費税3"),
        array("goods4","明細内容4"),
        array("goodsAmount4","注文数4"),
        array("goodsPrice4","単価4"),
        array("goodsSubtotal4","金額4"),
        array("goodsExpand4","金額消費税4"),
        array("goods5","明細内容5"),
        array("goodsAmount5","注文数5"),
        array("goodsPrice5","単価5"),
        array("goodsSubtotal5","金額5"),
        array("goodsExpand5","金額消費税5"),
        array("goods6","明細内容6"),
        array("goodsAmount6","注文数6"),
        array("goodsPrice6","単価6"),
        array("goodsSubtotal6","金額6"),
        array("goodsExpand6","金額消費税6"),
        array("goods7","明細内容7"),
        array("goodsAmount7","注文数7"),
        array("goodsPrice7","単価7"),
        array("goodsSubtotal7","金額7"),
        array("goodsExpand7","金額消費税7"),
        array("goods8","明細内容8"),
        array("goodsAmount8","注文数8"),
        array("goodsPrice8","単価8"),
        array("goodsSubtotal8","金額8"),
        array("goodsExpand8","金額消費税8"),
        array("goods9","明細内容9"),
        array("goodsAmount9","注文数9"),
        array("goodsPrice9","単価9"),
        array("goodsSubtotal9","金額9"),
        array("goodsExpand9","金額消費税9"),
        array("goods10","明細内容10"),
        array("goodsAmount10","注文数10"),
        array("goodsPrice10","単価10"),
        array("goodsSubtotal10","金額10"),
        array("goodsExpand10","金額消費税10"),
        array("goods11","明細内容11"),
        array("goodsAmount11","注文数11"),
        array("goodsPrice11","単価11"),
        array("goodsSubtotal11","金額11"),
        array("goodsExpand11","金額消費税11"),
        array("goods12","明細内容12"),
        array("goodsAmount12","注文数12"),
        array("goodsPrice12","単価12"),
        array("goodsSubtotal12","金額12"),
        array("goodsExpand12","金額消費税12"),
        array("goods13","明細内容13"),
        array("goodsAmount13","注文数13"),
        array("goodsPrice13","単価13"),
        array("goodsSubtotal13","金額13"),
        array("goodsExpand13","金額消費税13"),
        array("goods14","明細内容14"),
        array("goodsAmount14","注文数14"),
        array("goodsPrice14","単価14"),
        array("goodsSubtotal14","金額14"),
        array("goodsExpand14","金額消費税14"),
        array("goods15","明細内容15"),
        array("goodsAmount15","注文数15"),
        array("goodsPrice15","単価15"),
        array("goodsSubtotal15","金額15"),
        array("goodsExpand15","金額消費税15"),
        array("detailInfomation","明細注意事項"),
        array("expand11","予備項目11"),
        array("expand12","予備項目12"),
        array("expand13","予備項目13"),
        array("expand14","予備項目14"),
        array("expand15","予備項目15"),
        array("expand16","予備項目16"),
        array("expand17","予備項目17"),
        array("expand18","予備項目18"),
        array("expand19","予備項目19"),
        array("expand20","予備項目20"),
    );

    $arrHeader = array();
    $arrData = array();

    foreach ($arrColumn AS $column) {
        $arrHeader[] = '"'.$column[1].'"';
        $arrData[] = '"'.$arrPrintData[$column[0]].'"';
    }

    $csv_data = implode(",",$arrHeader)."\r\n";
    $csv_data .= implode(",",$arrData)."\r\n";

    //ファイル名を設定
    $csv_file = "atodene_print_". date( "YmdHis" ) .'.csv';

    $csv_data = mb_convert_encoding($csv_data, CHAR_CODE_KS, 'UTF-8');

    header("Content-Type: application/octet-stream; charset=Shift_JIS");
    header("Content-Disposition: attachment; filename={$csv_file}");
    header("Cache-Control: private");
    header("Pragma: private");

    // データの出力
    echo($csv_data);
}

/**
 * 受注情報から金額を取得する
 * @param $objQuery
 * @param $order_id 受注ID
 * @return $arrTelegram 補正後金額
 */
function sfCheckRevice($objQuery, $order_id) {
    $col = "payment_total";
    $from = "dtb_order";
    $where = "order_id = ?";
    $arrRet = $objQuery->select($col, $from, $where, array($order_id));
    $payment_total = $arrRet[0]['payment_total'];

    $arrTelegram = array($payment_total);
    return $arrTelegram;

}

/**
 * 関数名：getOrderInfo
 * 処理内容：引数で指定されたパラメータから
 * 受注情報を取得します。
 *
 * @param $order_id
 * @param $payment_id
 * @param $payment_amount
 *
 * @return $arrOrder
 */
function getOrderInfo($order_id) {

	$objQuery =& SC_Query_Ex::getSingletonInstance();
	$col = "payment_total, memo06, memo08, memo09";
	$table = "dtb_order";
    $where = "order_id = ?";
    $arrOrder = $objQuery->select($col, $table, $where, array($order_id));

    return $arrOrder;
}

/**
 * 関数名：sfPaygentTest
 * 処理内容：接続テスト
 * 戻り値：取得結果
 */
function sfPaygentTest(&$arrParam) {
    $objQuery =& SC_Query_Ex::getSingletonInstance();

    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $objPaygent = new PaygentB2BModule();
    $objPaygent->init();

    // 共通データの取得
    $arrSend = sfGetPaygentShare(PAYGENT_REF, '0', $arrParam);
    $arrSend['payment_notice_id'] = '0';

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $objPaygent->reqPut($key, $val);
    }
    $objPaygent->post();

    // 処理結果取得（共通）
    $resultStatus = $objPaygent->getResultStatus(); # 処理結果 0=正常終了, 1=異常終了

    if($resultStatus === "0") {
        return true;
    } else {
    	$arrParam['result_message'] = '';
    	if (method_exists($objPaygent, 'getResultMessage')) {
    		$arrParam['result_message'] = mb_convert_encoding($objPaygent->getResultMessage(), CHAR_CODE, 'Shift_JIS');
    		GC_Utils::gfPrintLog($arrParam['result_message'], PAYGENT_LOG_PATH);
    	}
    	return false;
    }
}

/**
 * 関数名：sendVirtualAccountErrorMail
 * 処理内容：異常入金通知メール送信処理
 * 戻り値：なし
 */
function sendVirtualAccountErrorMail($arrRet) {
    global $PAYGENT_BATCH_DIR;
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '" . PAY_PAYGENT_VIRTUAL_ACCOUNT . "'");
    $objMail = new SC_SendMail();
    $objMailTemplate = new SC_SiteView();
    $objSiteInfo = SC_Helper_DB_Ex::sfGetBasisData();

    $objMail->setTo($objSiteInfo['email04']);
    $objMail->setFrom("pg-support@paygent.co.jp");
    $objMail->setSubject("【ペイジェント】異常入金のお知らせ");

    $objMailTemplate->assign("merchant_id", $arrPaymentDB[0]['merchant_id']);
    $objMailTemplate->assign("payment_id", $arrRet['payment_id']);
    $objMailTemplate->assign("trading_id", $arrRet['trading_id']);
    $objMailTemplate->assign("payment_amount", $arrRet['payment_amount']);
    if ($arrRet['clear_detail'] == '03') {
        $clear_detail = '入金額不足';
    } else if ($arrRet['clear_detail'] == '04') {
        $clear_detail = '入金額過多';
    } else if ($arrRet['clear_detail'] == '05') {
        $clear_detail = '消込対象なし';
    }
    $objMailTemplate->assign("clear_detail", $clear_detail);
    $body = $objMailTemplate->fetch(MODULE_REALDIR . MDL_PAYGENT_CODE
        . '/templates/default/paygent_virtual_account_error_mail.tpl');
    $objMail->setBody($body);
    $objMail->sendMail();
}

/**
 * 関数名：getLaterPaymentDetailMsg
 * 処理内容：後払い決済向けの詳細エラーメッセージを生成
 *
 * @param $response_code
 * @param $response_detail
 * @param $module_type
 *
 * @return 詳細エラーメッセージ
 */
function getLaterPaymentDetailMsg($response_code, $response_detail, $module_type) {

    // ユーザー入力項目のうち電文に渡す項目を定義
    // 「customer_name」等は部分一致で「customer_family_name_kana」に引っかからないように最長のものから定義すること
    if ($module_type === SETTLEMENT_MODULE) {
        $input_values = array(
            'customer_name_kanji'=>         array('name'=>'ご注文者お名前','length'=>21),
            'customer_name_kana'=>          array('name'=>'ご注文者お名前(フリガナ)','length'=>25),
            'customer_zip_code'=>           array('name'=>'ご注文者郵便番号','length'=>7),
            'customer_address'=>            array('name'=>'ご注文者住所','length'=>55),
            'customer_tel'=>                array('name'=>'ご注文者電話番号','length'=>15),
            'customer_email'=>              array('name'=>'ご注文者メールアドレス','length'=>100),
            'ship_name_kanji'=>             array('name'=>'お届け先お名前','length'=>21),
            'ship_name_kana'=>              array('name'=>'お届け先お名前(フリガナ)','length'=>25),
            'ship_zip_code'=>               array('name'=>'お届け先郵便番号','length'=>7),
            'ship_address'=>                array('name'=>'お届け先住所','length'=>55),
            'ship_tel'=>                    array('name'=>'お届け先電話番号','length'=>15),
        );
    } else {
        $input_values = array(
            'customer_family_name_kana'=>   array('name'=>'ご注文者お名前(フリガナ)姓','length'=>12),
            'customer_name_kana'=>          array('name'=>'ご注文者お名前(フリガナ)名','length'=>12),
            'customer_family_name'=>        array('name'=>'ご注文者お名前(姓)','length'=>6),
            'customer_name'=>               array('name'=>'ご注文者お名前(名)','length'=>6),
            'customer_zip_code'=>           array('name'=>'ご注文者郵便番号','length'=>7),
            'customer_address'=>            array('name'=>'ご注文者住所','length'=>55),
            'customer_tel_hyphen'=>         array('name'=>'ご注文者電話番号','length'=>15),
            'customer_tel'=>                array('name'=>'ご注文者電話番号','length'=>11),
            'customer_email'=>              array('name'=>'ご注文者メールアドレス','length'=>100),
            'ship_family_name_kana'=>       array('name'=>'お届け先お名前(フリガナ)姓','length'=>12),
            'ship_name_kana'=>              array('name'=>'お届け先お名前(フリガナ)名','length'=>12),
            'ship_family_name'=>            array('name'=>'お届け先お名前(姓)','length'=>6),
            'ship_name'=>                   array('name'=>'お届け先お名前(名)','length'=>6),
            'ship_zip_code'=>               array('name'=>'お届け先郵便番号','length'=>7),
            'ship_address'=>                array('name'=>'お届け先住所','length'=>55),
            'ship_tel'=>                    array('name'=>'お届け先電話番号','length'=>15)
        );
    }

    $msg_templates = array(
        'P008'=>'%name%は形式が正しくないか使用できない文字が含まれています。',
        'P009'=>'%name%は%length%文字以内を設定してください。',
        'P010'=>'%name%は不正な値です。',
    );

    if (!array_key_exists($response_code, $msg_templates)) {
        return NO_MAPPING_MESSAGE;
    }

    $str_detail_msg = "";

    foreach ($input_values AS $key_input_values=>$input_value) {

        $pos = strpos($response_detail, $key_input_values);

        if ($pos !== false) {

            $mapping = array(
                '%name%'=>$input_values[$key_input_values]['name'],
                '%length%'=>$input_values[$key_input_values]['length'],
            );

            $search = array_keys($mapping);
            $replace = array_values($mapping);

            $str_detail_msg = str_replace($search, $replace, $msg_templates[$response_code]);
            break;
        }
    }

    if ($str_detail_msg) {
        return $str_detail_msg;
    } else {
        return NO_MAPPING_MESSAGE;
    }
}

/**
 * 関数名：getCommonDetailMsg
 * 処理内容：各種決済手段のフォーマットエラー時のエラーメッセージを生成
 *
 * @param $response_code
 * @param $response_detail
 * @param $telegram_kind
 *
 * @return 詳細エラーメッセージ
 */
function getCommonDetailMsg($response_code, $response_detail, $telegram_kind) {

    if ($response_code != "P008") {
        return NO_MAPPING_MESSAGE;
    }

    switch ($telegram_kind) {
        case PAYGENT_ATM:
        case PAYGENT_CONVENI_NUM:
        case PAYGENT_BANK:
            $input_values = array(
            'customer_family_name'=>'利用者姓',
            'customer_name'=>'利用者名'
                    );
            break;
        case PAYGENT_VIRTUAL_ACCOUNT:
            $input_values = array(
            'billing_name'=>'利用者'
                    );
            break;
    }

    $str_detail_msg = "";

    foreach ($input_values AS $key_input_values=>$input_value) {

        $pos = strpos($response_detail, $key_input_values);

        if ($pos !== false) {
            $str_detail_msg = $input_value."は形式が正しくないか使用できない文字が含まれています。";
            break;
        }
    }

    if ($str_detail_msg) {
        return $str_detail_msg;
    } else {
        return NO_MAPPING_MESSAGE;
    }
}

/**
 * 決済モジュールのアップデート日時を取得
 * @return モジュールのアップデート日時(YYYYMMDDHHMMSS)
 */
function getModuleUpdateDate() {
    $objQuery =& SC_Query_Ex::getSingletonInstance();
    $arrRet = $objQuery->select("update_date", "dtb_module", "module_code = 'mdl_paygent'");
    return date('YmdHis', strtotime($arrRet[0]['update_date']));
}

/**
 * 関数名：createPaygentHash
 * 処理内容：セキュリティ対策にペイジェント固有のハッシュを生成
 *
 * @param Array $order 受注データの配列
 *
 * @return String ハッシュ文字列
 */
 function createPaygentHash ($order) {
    $hash = '';
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE);
    $hash = $order['order_id'] . $order['create_date'] . $arrPaymentDB['connect_id'] . $arrPaymentDB['connect_password'];
    for ($i=0; $i<3; $i++) {
        $hash = hash('sha256', $hash);
    }

    return $hash;
}

/**
 * 関数名：sendPaygentTelegramCommon
 * 処理内容：共通電文の送信
 * 戻り値：取得結果
 */
function sendPaygentTelegramCommon($arrInput) {
    // 接続モジュールのインスタンス取得 (コンストラクタ)と初期化
    $p = new PaygentB2BModule();
    $p->init();

    // 共通データの取得
    $arrSend = sfGetPaygentShare($arrInput['telegram_kind'], $arrInput['trading_id'], $arrInput['payment_db'], $arrInput['payment_id']);

    // 電文の送付
    foreach($arrSend as $key => $val) {
        $enc_val = mb_convert_encoding($val, CHAR_CODE_KS, CHAR_CODE);

        if (!reverseCheck($val, $enc_val)) {
            return createErrorReturnFront($key);
        }
        $p->reqPut($key, $enc_val);
    }
    $p->post();
    // 応答を処理
    $objPurchase = new SC_Helper_Purchase_Ex();
    $objCartSess = new SC_CartSession_Ex();

    // 処理結果取得（共通）
    $resultStatus = $p->getResultStatus(); # 処理結果 0=正常終了, 1=異常終了
    $responseCode = $p->getResponseCode(); # 異常終了時、レスポンスコードが取得できる
    $responseDetail = $p->getResponseDetail(); # 異常終了時、レスポンス詳細が取得できる
    $responseDetail = mb_convert_encoding($responseDetail, CHAR_CODE, "Shift-JIS");

    // 取得した値をログに保存する。
    if ($resultStatus == 1) {
        $arrResOther['result'] = $resultStatus;
        $arrResOther['code'] = $responseCode;
        $arrResOther['detail'] = $responseDetail;
        foreach($arrResOther as $key => $val) {
            GC_Utils::gfPrintLog($key."->".$val, PAYGENT_LOG_PATH);
        }
    }

    // レスポンスの取得
    while($p->hasResNext()) {
        # データが存在する限り、取得
        $arrRes[] = $p->resNext(); # 要求結果取得
    }

    return $arrRes[0];
}
/**
 * 関数名：sendPaidySettlementAmountMismatchMail
 * 処理内容：決済金額改竄検知通知メール送信処理
 * 戻り値：なし
 */
function sendPaidySettlementAmountMismatchMail($arrRet,$payment_amount) {
    global $PAYGENT_BATCH_DIR;
    $arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE, "AND memo03 = '" . PAY_PAYGENT_PAIDY . "'");
    $objMail = new SC_SendMail();
    $objMailTemplate = new SC_SiteView();
    $objSiteInfo = SC_Helper_DB_Ex::sfGetBasisData();

    $objMail->setTo($objSiteInfo['email04']);
    $objMail->setFrom("pg-support@paygent.co.jp");
    $objMail->setSubject("【ペイジェント】Paidy決済金額改竄検知");

    $objMailTemplate->assign("merchant_id", $arrPaymentDB[0]['merchant_id']);
    $objMailTemplate->assign("payment_id", $arrRet['payment_id']);
    $objMailTemplate->assign("trading_id", $arrRet['trading_id']);
    $objMailTemplate->assign("payment_amount", $payment_amount);
    $objMailTemplate->assign("paidy_amount", $arrRet['payment_amount']);

    $body = $objMailTemplate->fetch(MODULE_REALDIR . MDL_PAYGENT_CODE . '/templates/default/paygent_paidy_mismatch_mail.tpl');

    $objMail->setBody($body);
    $objMail->sendMail();
}
?>
