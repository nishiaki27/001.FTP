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
require_once $PAYGENT_BATCH_DIR . "/../../../../html/require.php";
require_once $PAYGENT_BATCH_DIR . '/LC_Page_Mdl_Paygent_Config.php';
ob_end_clean();

/** payment_notice_id を格納しておくファイル. */
define('PAYMENT_NOTICE_IDS_CACHE', DATA_REALDIR . 'cache/paygent_notice_id.log');

if (!file_exists(PAYMENT_NOTICE_IDS_CACHE)) {
    touch(PAYMENT_NOTICE_IDS_CACHE);
}

$objQuery = new SC_Query();
$objPaygent = new PaygentB2BModule();
$objPaygent->init();

// 設定パラメータの取得
$arrPaymentDB = sfGetPaymentDB(MDL_PAYGENT_CODE);

// 共通データの取得
$arrRequest = sfGetPaygentShare(PAYGENT_REF, 0, $arrPaymentDB[0]);

// エラーメール送信ID
$arrErrMailIds = array();
// 今回受信した payment_notice_id
$arrCurrentPaymentNoticeIds = array();

line();
logTrace("PAYGENT BATCH START!");
line();

// 前回実行した payment_notice_id を取得
line();
logTrace("Get the previous payment_notice_ids...");
$arrPreviousNoticeIds = getPaymentNoticeIds();
foreach ($arrPreviousNoticeIds as $id) {
    GC_Utils::gfPrintLog($id, PAYGENT_LOG_PATH);
    echo $id;
    ln();
}
line();

/*
 * 前回実行した payment_notice_id の最大値を取得
 * キャッシュファイルが空の場合は DB より取得
 *
 * キャッシュファイルが空で既存の受注データが大量に存在する場合,
 * 余計な処理を避けるため
 */
$max_payment_notice_id = 0;
if (!empty($arrPreviousNoticeIds)) {
    $max_payment_notice_id = (int) max($arrPreviousNoticeIds);
    logTrace("Max payment_notice_id by $max_payment_notice_id");
} else {
    if (DB_TYPE == "pgsql") {
        $col = "cast(memo10 AS int4)";
    } elseif (DB_TYPE == "mysql") {
        $col = "cast(memo10 AS SIGNED)";
    }
    $max_payment_notice_id = $objQuery->max($col, "dtb_order", "memo01 = ?", array(MDL_PAYGENT_CODE));
    logTrace("Max payment_notice_id by $max_payment_notice_id");
}

/*
 * payment_notice_id を指定せずに送信
 * success_code = 1 を受け取るまで再帰的に実行
 */
// 通信リクエスト回数を決めておかないと、再帰で無限ループに陥ってしまうため、通信リクエスト回数を設定する。
$requestCount = 0;
$result = requestPaygent($objPaygent, $arrRequest, array());
// ここで result = 1 を受け取っても, 後続の処理を実行する


// 今回実行時に, 空となった payment_notice_id を検索
logTrace("Find lost payment_notice_ids...");
$arrNoticeIds = getLostPaymentNoticeIds($max_payment_notice_id,
                                        $arrCurrentPaymentNoticeIds);
foreach ($arrNoticeIds as $id) {
    GC_Utils::gfPrintLog($id, PAYGENT_LOG_PATH);
    echo $id;
    ln();
}

// payment_notice_id のキャッシュに追加
logTrace("added payment_notice_ids by current ids...");
foreach ($arrCurrentPaymentNoticeIds as $id) {
    GC_Utils::gfPrintLog($id, PAYGENT_LOG_PATH);
    echo $id;
    ln();
    addPaymentNoticeId($id);
}

/*
 * 連番でない payment_notice_id を指定して送信
 */
if (!empty($arrNoticeIds)) {
    $result = requestPaygent($objPaygent, $arrRequest, $arrNoticeIds);
}

// エラーメールを送信する場合は送信
if (!empty($arrErrMailIds)) {
    sendErrorMail($max_payment_notice_id, $arrErrMailIds);
}

if ($result == 0) {
    line();
    logTrace("PAYGENT BATCH FINISHED Successful!");
    line();
}

exit((int) $result);

/**
 * ペイジェントサーバーに電文を送信してステータスの変更処理を行う.
 *
 * $arrNoticeIds にて payment_notice_id を指定して実行した場合, payment_notice_id
 * をペイジェントサーバーに送信する. $arrNoticeIds に保持する payment_notice_id が
 * 無くなるまで再帰的に実行する.
 *
 * $arrNoticeIds を指定しない場合は, ペイジェントサーバーより success_code = 1 が
 * 返却されるまで, 再帰的に実行する.
 *
 * ペイジェントサーバーより, success_code = 0 が返却された場合は, 受注ステータス
 * を更新する.
 * success_code = 2 が返却された場合は, 差分照会エラー通知メールを送信する.
 *
 * @param PaygentB2BModule $objPaygent ペイジェントB2Bモジュールクラス
 * @param array $arrRequest ペイジェントサーバーに送信するリクエスト
 * @param array $arrNoticeIds payment_notice_id の配列
 * @global array $arrErrMailIds 差分照会エラー通知メールを送信する payment_notice_id の配列
 * @global array $arrCurrentPaymentNoticeIds 現在のバッチで取得した payment_notice_id の配列
 * @return integer 正常終了時は 0, 異常終了時は 1 を返す
 *
 */
function requestPaygent(&$objPaygent, $arrRequest, $arrNoticeIds = array()) {
    global $arrErrMailIds;
    global $arrCurrentPaymentNoticeIds;
    global $requestCount;

    /*
     * payment_notice_id が指定されている場合は, 最初のIDを付与
     */
    if (!empty($arrNoticeIds)) {
        $arrRequest['payment_notice_id'] = array_shift($arrNoticeIds);
    }

    line();
    logTrace("BEGEN PAYGENT REQUEST!");
    line();

    // 電文の送付
    foreach($arrRequest as $key => $val) {
        logTrace("$key => $val");
        $objPaygent->reqPut($key, $val);
    }
    $objPaygent->post();

    line();
    logTrace("END PAYGENT REQUEST!");
    line();

    line();
    logTrace("RETURN PAYGENT RESPONSE!");
    line();

    // レスポンスの取得
    while($objPaygent->hasResNext()) {
        // データが存在する限り、取得
        foreach ($objPaygent->resNext() as $key => $val) {
            $arrResponse[$key] = $val;
            $convertedKey = mb_convert_encoding($key, CHAR_CODE, "SJIS-win");
            $convertedVal = mb_convert_encoding($val, CHAR_CODE, "SJIS-win");
            logTrace("$convertedKey => $convertedVal");
        }
    }

    line();
    logTrace("END PAYGENT RESPONSE!");
    line();

    // 処理結果 0=正常終了, 1=異常終了
    $result = $objPaygent->getResultStatus();
    // payment_notice_id を空にする
    $arrRequest['payment_notice_id'] = '';

    /*
     * 通知が指定回数以上、ＫＳ側からのタイムアウトエラーが起きたら、再帰処理を終了する。
     * クライアント証明書の有効期限切れ等でペイジェントのアプリケーションサーバーに到達しないケース等
     */
    if (empty($arrResponse)) {
        if ($requestCount > PAYGENT_REF_LOOP) {
            line();
            logTrace("PAYGENT BATCH Error because there is no response from the server for more than the specified number of times!!");
            line();
            return 1;
        } else {
            $requestCount++;
        }
    } else {
        // 通信エラーが改善された場合は、試行制限回数をクリアする。
        $requestCount = 0;
    }

    /*
     * 処理結果 = 1の場合は異常終了
     */
    if ($result == 1) {
        line();
        logTrace("PAYGENT BATCH FAILURE!!");
        logTrace("Result by $result");
        logTrace("response_code by " . $arrResponse['response_code']);
        logTrace("response_detail by " . $arrResponse['response_detail']);
        line();
        return (int) $result;
    }

    /*
     * 返却データなし(success_code = 1) 又は payment_notice_id が無くなるまで
     * 再帰的に実行する.
     */
    switch ($arrResponse['success_code']) {

    // success_code = 1 の場合は終了. $arrNoticeIds を指定している場合は再帰する
    case 1:
        if (!empty($arrNoticeIds)) {
            $result = requestPaygent($objPaygent, $arrRequest, $arrNoticeIds);
        }
        break;

    // success_code = 2 の場合はエラー通知の payment_notice_id を追加
    case 2:
        $arrErrMailIds[] = $arrResponse['payment_notice_id'];
        // ここでは break しない
        logTrace("[Notice] success_code = 2 added Notice Mail by "
                 . $arrResponse['payment_notice_id']);

    case 0:
        // 決済通知IDをキャッシュに追加
        logTrace("added payment_notice_id by "
                 . $arrResponse['payment_notice_id']);
        $arrCurrentPaymentNoticeIds[] = $arrResponse['payment_notice_id'];

        // 入金ステータスを更新する
        $objConfig = new LC_Page_Mdl_Paygent_Config();
        $arrConfig = $objConfig->getConfig();
        sfUpdatePaygentOrder(new SC_Query(), $arrResponse, $arrConfig);

        $result = requestPaygent($objPaygent, $arrRequest, $arrNoticeIds);
        break;
    default:
    }
    return (int) $result;
}

/**
 * 罫線を出力する.
 */
function line() {
    $log = "-----------------------------------------------------------";
    GC_Utils::gfPrintLog($log, PAYGENT_LOG_PATH);
    echo $log;
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
    echo $log;
}

/**
 * ログのプレフィクスを出力する.
 */
function logPrefix() {
    $log = "[";
    $log .= date("Y-m-d H:i:s");
    $log .= "] ";
    GC_Utils::gfPrintLog($log, PAYGENT_LOG_PATH);
    echo $log;
}

/**
 * トレースログを出力する.
 */
function logTrace($log) {
    logPrefix();
    GC_Utils::gfPrintLog($log, PAYGENT_LOG_PATH);
    echo $log;
    ln();
}

/**
 * キャッシュにある payment_notice_id を配列で取得する.
 */
function getPaymentNoticeIds() {
    $contents = file_get_contents(PAYMENT_NOTICE_IDS_CACHE);
    if ($contents === false) {
        return array();
    } else {
        $result = unserialize($contents);
        $result = is_array($result) ? $result : array();
        sort($result, SORT_NUMERIC);
        return $result;
    }
}

/**
 * payment_notice_id のキャッシュをクリアする.
 */
function clearPaymentNoticeIds() {
    $fp = fopen(PAYMENT_NOTICE_IDS_CACHE, 'r+b');
    if ($fp !== false) {
        ftruncate($fp, 0);
        fclose($fp);
    }
}

/**
 * キャッシュの payment_notice_id を引数のIDの配列で置換する.
 */
function replacePaymentNoticeIds(&$arrResultNoticeIds) {
    clearPaymentNoticeIds();
    if (!empty($arrResultNoticeIds)) {
        foreach ($arrResultNoticeIds as $val) {
            addPaymentNoticeId($val);
        }
    }
}

/**
 * payment_notice_id をキャッシュに追加する.
 */
function addPaymentNoticeId($payment_notice_id) {
    $ids = getPaymentNoticeIds();
    $ids[] = $payment_notice_id;
    $fp = fopen(PAYMENT_NOTICE_IDS_CACHE, 'w+');
    if ($fp !== false) {
        fwrite($fp, serialize($ids));
        fclose($fp);
    }
}

/**
 * payment_notice_id の配列から, 連番ではない, 空になった payment_notice_id
 * を取得します.
 *
 * @param integer $min_payment_notice_id 前回のバッチ実行で取得した
 *   payment_notice_id の最大値. この関数では連番の開始値として扱う.
 * @param array $arrNoticeIds 今回のバッチ実行で取得した payment_notice_id の配列
 */
function getLostPaymentNoticeIds($min_payment_notice_id, &$arrNoticeIds) {
    $results = array();

    if (empty($arrNoticeIds)) {
        return $results;
    }
    sort($arrNoticeIds, SORT_NUMERIC);

    $min = (int) $min_payment_notice_id;
    $max = (int) max($arrNoticeIds);

    // 連番を走査し, 見つからなければ結果に追加
    for ($i = $min; $i < $max; $i++) {
        if ($i == $min_payment_notice_id) {
            continue;
        }
        if (!in_array($i, $arrNoticeIds)) {
            $results[] = $i;
        }
    }
    return $results;
}

/**
 * 入金検知バッチエラーメールを送信する.
 *
 * 前回実行時の payment_notice_id の最大値 + 1 から
 * success_code = 2 を受け取った payment_notice_id の最大値 - 1 までが,
 * パージ対象の payment_notice_id とする.
 */
function sendErrorMail($max_payment_notice_id, $arrErrMailIds) {
    global $PAYGENT_BATCH_DIR;

    if (empty($arrErrMailIds)) {
        return;
    }

    $objMail = new SC_SendMail();
    $objMailTemplate = new SC_SiteView();
    $objSiteInfo = SC_Helper_DB_Ex::sfGetBasisData();

    $objMail->setTo($objSiteInfo['email04']);
    $objMail->setFrom($objSiteInfo['email04']);
    $objMail->setSubject("ペイジェント決済入金検知バッチエラー");

    sort($arrErrMailIds, SORT_NUMERIC);

    // 前回実行時の最大値 + 1
    $from = $max_payment_notice_id + 1;
    // success_code = 2 を受け取った payment_notice_id の最大値 - 1
    $to = max($arrErrMailIds) - 1;

    $objMailTemplate->assign("id_from", $from);
    $objMailTemplate->assign("id_to", $to);
    $objMailTemplate->assign("id_total", $to - $from + 1);
    $body = $objMailTemplate->fetch($PAYGENT_BATCH_DIR
                                    . '/templates/default/paygent_batch_error_mail.tpl');
    $objMail->setBody($body);
	$objMail->sendMail();
    logTrace("Notice Error Mail by payment_notice_id on $from => $to...");
}
?>
