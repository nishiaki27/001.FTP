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
require_once("../../../../require.php");
require_once(MODULE_REALDIR . 'mdl_paygent/include.php');

// }}}
// {{{ generate page
// タイトル出力
lfOutputTitle($_POST['mode']);
// 一括受注連携
list($success_cnt, $fail_cnt) = lfPaygentAllOrder($_POST[$_POST['mode']]);
// 結果出力
lfOutputResult($success_cnt, $fail_cnt);
// 検索条件保持
$arrHidden = lfSearchCondition();


// タイトル出力
function lfOutputTitle($mode) {
    // タイトル出力
    if ($mode == 'paygent_commit') $output = "■ 一括売上<br />\n";
    elseif ($mode == 'paygent_cancel') $output = "■ 一括取消<br />\n";
    SC_Utils_Ex::sfFlush($output);
}

// 一括受注連携
function lfPaygentAllOrder($arr_paygent_commit) {
    // 初期設定
    $max = count($arr_paygent_commit);
    $cnt = 1;
    $success_cnt = 0;
    $fail_cnt = 0;
    $arrDispKind = getDispKind();

    // 受注連携
    foreach ($arr_paygent_commit as $val) {
        // 連携種別と受注ID
        $paygent_commit = split(",", $val);
        SC_Utils_Ex::sfFlush("$cnt/$max 受注番号：$paygent_commit[1] → ");
        // 連携
        $res = sfPaygentOrder($paygent_commit[0], $paygent_commit[1]);
        // 結果出力
        if ($res['return'] === true) {
            $output = $arrDispKind[$res['kind']]. "成功<br />\n";
            $success_cnt++;
        } else {
            if (strlen($res['revice_price_error']) <= 0) {
                $output = $arrDispKind[$res['kind']]. "失敗 ". $res['response']. "<br />\n";
            } else {
                $output = "失敗 ". $res['revice_price_error']. "<br />\n";
            }
            $fail_cnt++;
        }
        SC_Utils_Ex::sfFlush($output);
        $cnt++;
    }

    return array($success_cnt, $fail_cnt);
}

// 結果を表示
function lfOutputResult($success_cnt, $fail_cnt) {
    // 結果表示
    $output = "<br />\n■ 処理結果<br />\n";
    $output .= $success_cnt. "件が成功しました。<br />\n";
    $output .= $fail_cnt. "件が失敗しました。<br />\n";
    $output .= "<br />\n";
    $output .= "メイン画面のリロードを行い、受注一覧を更新してください。<br />\n";
    SC_Utils_Ex::sfFlush($output);
}

// 検索条件の保持
function lfSearchCondition() {

    $objFormParam = new SC_FormParam_Ex();
    lfInitParam($objFormParam);
    $objFormParam->setParam($_REQUEST);
    $objFormParam->convParam();

    $arrSearchHidden = $objFormParam->getSearchArray();

    return $arrSearchHidden;
}

/**
 * パラメータ情報の初期化を行う.
 *
 * @param SC_FormParam $objFormParam SC_FormParam インスタンス
 * @return void
 */
function lfInitParam(&$objFormParam) {
	$objFormParam->addParam("注文番号1", "search_order_id1", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("注文番号2", "search_order_id2", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("対応状況", "search_order_status", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("顧客名", "search_order_name", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
    $objFormParam->addParam("顧客名(カナ)", "search_order_kana", STEXT_LEN, 'KVCa', array("KANA_CHECK","MAX_LENGTH_CHECK"));
    $objFormParam->addParam("性別", "search_order_sex", INT_LEN, 'n', array("MAX_LENGTH_CHECK"));
    $objFormParam->addParam("年齢1", "search_age1", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("年齢2", "search_age2", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("メールアドレス", "search_order_email", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
    $objFormParam->addParam('TEL', "search_order_tel", STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
    $objFormParam->addParam("支払い方法", "search_payment_id", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("購入金額1", "search_total1", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("購入金額2", "search_total2", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("表示件数", "search_page_max", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    // 受注日
    $objFormParam->addParam("開始年", "search_sorderyear", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("開始月", "search_sordermonth", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("開始日", "search_sorderday", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了年", "search_eorderyear", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了月", "search_eordermonth", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了日", "search_eorderday", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    // 更新日
    $objFormParam->addParam("開始年", "search_supdateyear", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("開始月", "search_supdatemonth", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("開始日", "search_supdateday", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了年", "search_eupdateyear", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了月", "search_eupdatemonth", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了日", "search_eupdateday", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    // 生年月日
    $objFormParam->addParam("開始年", "search_sbirthyear", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("開始月", "search_sbirthmonth", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("開始日", "search_sbirthday", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了年", "search_ebirthyear", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了月", "search_ebirthmonth", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("終了日", "search_ebirthday", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("購入商品","search_product_name",STEXT_LEN,'KVa',array("MAX_LENGTH_CHECK"));
    $objFormParam->addParam("ページ送り番号","search_pageno", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    $objFormParam->addParam("受注ID", "order_id", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHAR_CODE; ?>">
<meta http-equiv="content-script-type" content="text/javascript" />
<script type="text/javascript" src="<?php echo ROOT_URLPATH ?>js/site.js"></script>
<script>
<!--
window.onbeforeunload = function ()
{
//  fnChangeAction('<?php echo ADMIN_ORDER_URLPATH; ?>');
//  document.form1.submit();
//  return '';
}
//-->
</script>

</head>

<body>
<br />
<form name="form1" id="form1" method="post" action="" target="main">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="<?php echo TRANSACTION_ID_NAME ?>" value="<?php echo $_REQUEST[TRANSACTION_ID_NAME] ?>" />
<?php foreach ($arrHidden as $key => $item) {
if (is_array($item)) {
    foreach ($item as $c_item) {
        echo "<input type=\"hidden\" name=\"{$key}[]\" value=\"$c_item\">\n";
	}
} else {
    echo "<input type=\"hidden\" name=\"$key\" value=\"$item\">\n";
}
}?>
<input type="button" value="閉じる" onclick="window.close();">
</form>
</body>
</html>

