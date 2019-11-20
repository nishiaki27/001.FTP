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
define('MDL_PG_MULPAY', true);
define('MDL_PG_MULPAY_VERSION', '2.2.4');
define('MDL_PG_MULPAY_PATH', MODULE_REALDIR . 'mdl_pg_mulpay/');
define('MDL_PG_MULPAY_CLASS_REALDIR', MDL_PG_MULPAY_PATH . 'class/');
define('MDL_PG_MULPAY_TEMPLATE_PATH', MDL_PG_MULPAY_PATH . 'templates/');
define('MDL_PG_MULPAY_RETURL', HTTPS_URL . 'shopping/load_payment_module.php');
define('MDL_PG_MULPAY_3D_RETURL', HTTPS_URL . 'shopping/load_payment_module.php');
define('MDL_PG_MULPAY_PAYPAL_REDIRECT_URL', HTTPS_URL . 'shopping/load_payment_module.php');
define('MDL_PG_MULPAY_WEBMONEY_REDIRECT_URL', HTTPS_URL . 'shopping/load_payment_module.php');
define('MDL_PG_MULPAY_NETID_REDIRECT_URL', HTTPS_URL . 'shopping/load_payment_module.php');

// 自由項目3
define('MDL_PG_MYLPAY_CLIENT_FIELD3', 'EC-CUBE '.MDL_PG_MULPAY_VERSION);
// TenantNo
define('MDL_PG_MULPAY_TENANTNO', 'TenantNo');
// SeqMode
define('MDL_PG_MULPAY_SEQMODE', '0');
// カード登録件数
define('MDL_PG_MULPAY_CARDCOUNT', 5);

// クレジット分割回数
$GLOBALS['arrPayMethod'] = array(
    '1-0' => '一括払い',
    '2-2' => '分割2回払い',
    '2-3' => '分割3回払い',
    '2-4' => '分割4回払い',
    '2-5' => '分割5回払い',
    '2-6' => '分割6回払い',
    '2-7' => '分割7回払い',
    '2-8' => '分割8回払い',
    '2-9' => '分割9回払い',
    '2-10' => '分割10回払い',
    '2-11' => '分割11回払い',
    '2-12' => '分割12回払い',
    '2-13' => '分割13回払い',
    '2-14' => '分割14回払い',
    '2-15' => '分割15回払い',
    '2-16' => '分割16回払い',
    '2-17' => '分割17回払い',
    '2-18' => '分割18回払い',
    '2-19' => '分割19回払い',
    '2-20' => '分割20回払い',
    '2-21' => '分割21回払い',
    '2-22' => '分割22回払い',
    '2-23' => '分割23回払い',
    '2-24' => '分割24回払い',
    '2-26' => '分割26回払い',
    '2-30' => '分割30回払い',
    '2-32' => '分割32回払い',
    '2-34' => '分割34回払い',
    '2-36' => '分割36回払い',
    '2-37' => '分割37回払い',
    '2-40' => '分割40回払い',
    '2-42' => '分割42回払い',
    '2-48' => '分割48回払い',
    '2-50' => '分割50回払い',
    '2-54' => '分割54回払い',
    '2-60' => '分割60回払い',
    '2-72' => '分割72回払い',
    '2-84' => '分割84回払い',
    '3-0' => 'ボーナス一括',
    '4-2' => 'ボーナス分割2回払い',
    '5-0' => 'リボ払い',
);

// 処理区分
$GLOBALS['arrJobCd'] = array(
    'AUTH',
    'CHECK',
    'CAPTURE'
);

// カード番号桁数
define('CREDIT_NO_MIN_LEN', 10);
define('CREDIT_NO_MAX_LEN', 16);
// セキュリティコード桁数
define('SECURITY_CODE_LEN', 4);

//コンビニの種類
define('CONVENI_LOSON', '00001');
define('CONVENI_FAMILYMART', '00002');
define('CONVENI_SUNKUS', '00003');
define('CONVENI_CIRCLEK', '00004');
define('CONVENI_MINISTOP', '00005');
define('CONVENI_DAILYYAMAZAKI', '00006');
define('CONVENI_SEVENELEVEN', '00007');
$GLOBALS['arrCONVENI'] = array(
    CONVENI_LOSON => 'ローソン',
    CONVENI_FAMILYMART => 'ファミリーマート',
    CONVENI_SUNKUS => 'サンクス',
    CONVENI_CIRCLEK => 'サークルK',
    CONVENI_MINISTOP => 'ミニストップ',
    CONVENI_DAILYYAMAZAKI => 'デイリーヤマザキ',
    CONVENI_SEVENELEVEN => 'セブンイレブン'
);

// 入力パラメータ桁数
define('PAYMENT_TERM_DAY_LEN', 2);
define('PAYMENT_TERM_DAY_MAX', 30);
define('PAYMENT_TERM_SEC_LEN', 5);
define('PAYMENT_TERM_SEC_MAX', 86400);
define('PAYMENT_TERM_MIN', 300);
define('PAYMENT_TERM_MAX', 30);
define('REGISTER_DISP_LEN', 16);
define('RECEIPT_DISP_LEN', 30);
define('SUICA_ADDINFO_LEN', 256);
define('EDY_ADDINFO1_LEN', 180);
define('EDY_ADDINFO2_LEN', 320);
define('RECEIPT_DISP11_LEN', 42);
define('RECEIPT_DISP12_LEN', 4);
define('RECEIPT_DISP13_LEN', 2);
define('CLIENT_FIELD_LEN', 100);
define('RECEIPT_DISP12_TOTAL_LEN_MAX', 11);
define('SUICA_ITEM_NAME_LEN', 40);
define('CUSTOMER_NAME_LEN', 40);
define('CUSTOMER_KANA_LEN', 40);
define('TEL_NO_LEN', 13);
define('EMAIL_LEN', 245);
define('EMAIL_ALL_LEN', 256);
define('PAYPAL_ITEM_NAME', 64);
define('WEBMONEY_ITEM_NAME', 40);
define('NETID_ITEM_NAME', 40);
define('AU_ITEM_NAME', 48);
define('AU_SERVICE_NAME_LEN', 48);
define('AU_SERVICE_TEL_LEN', 15);

// 携帯メールドメイン
$GLOBALS['arrMobileMailDomain'] = array(
    'docomo.ne.jp' => "docomo.ne.jp",
    'ezweb.ne.jp' => "ezweb.ne.jp",
    'softbank.ne.jp' => "softbank.ne.jp",
    'disney.ne.jp' => "disney.ne.jp",
    't.vodafone.ne.jp' => "t.vodafone.ne.jp",
    'd.vodafone.ne.jp' => "d.vodafone.ne.jp",
    'h.vodafone.ne.jp' => "h.vodafone.ne.jp",
    'c.vodafone.ne.jp' => "c.vodafone.ne.jp",
    'k.vodafone.ne.jp' => "k.vodafone.ne.jp",
    'r.vodafone.ne.jp' => "r.vodafone.ne.jp",
    'n.vodafone.ne.jp' => "n.vodafone.ne.jp",
    's.vodafone.ne.jp' => "s.vodafone.ne.jp",
    'q.vodafone.ne.jp' => "q.vodafone.ne.jp"
);

// お問合せ時間（時）
$GLOBALS['arrHour'] = array(
    '00' => '00',
    '01' => '01',
    '02' => '02',
    '03' => '03',
    '04' => '04',
    '05' => '05',
    '06' => '06',
    '07' => '07',
    '08' => '08',
    '09' => '09',
    '10' => '10',
    '11' => '11',
    '12' => '12',
    '13' => '13',
    '14' => '14',
    '15' => '15',
    '16' => '16',
    '17' => '17',
    '18' => '18',
    '19' => '19',
    '20' => '20',
    '21' => '21',
    '22' => '22',
    '23' => '23'
);

// お問合せ時間（分）
$GLOBALS['arrMinutes'] = array(
    '00' => '00',
    '05' => '05',
    '10' => '10',
    '15' => '15',
    '20' => '20',
    '25' => '25',
    '30' => '30',
    '35' => '35',
    '40' => '40',
    '45' => '45',
    '50' => '50',
    '55' => '55'
);

// 支払上限金額
define('CONVENI_RULE_MAX', 299999);
define('SUICA_RULE_MAX', 20000);
define('EDY_RULE_MAX', 50000);
define('PAYEASY_RULE_MAX', 999999);
define('PAYPAL_RULE_MAX', 999999);
define('WEBMONEY_RULE_MAX', 999999);
define('NETID_RULE_MAX', 999999);
define('AU_RULE_MAX', 9999999);

// 結果通知プログラムURL
define('RESULT_RECEIVE_PATHNAME', "pg_mulpay/receive.php");

// SuicaサイトURL
define('SUICA_PC_SITE_LINK', "http://www.jreast.co.jp/mobileSuica/index.html");
// EdyサイトURL
define('EDY_PC_SITE_LINK', "http://www.edy.jp/");

// 決済結果通知 決済方法(PayType)
// 参照: プロトコルタイプ(マルチ決済_インターフェイス仕様).pdf 結果通知プログラム
define('MDL_PG_MULPAY_CREDIT_PAY_TYPE', 0);
define('MDL_PG_MULPAY_SUICA_PAY_TYPE', 1);
define('MDL_PG_MULPAY_EDY_PAY_TYPE', 2);
define('MDL_PG_MULPAY_CONVENI_PAY_TYPE', 3);
define('MDL_PG_MULPAY_PAYEASY_PAY_TYPE', 4);
define('MDL_PG_MULPAY_PAYPAL_PAY_TYPE', 5);
define('MDL_PG_MULPAY_NETID_PAY_TYPE', 6);
define('MDL_PG_MULPAY_WEBMONEY_PAY_TYPE', 7);
define('MDL_PG_MULPAY_AU_PAY_TYPE', 8);
$arrPayType = array(
    MDL_PG_MULPAY_CREDIT_PAY_TYPE  => 'クレジット',
    MDL_PG_MULPAY_SUICA_PAY_TYPE   => 'モバイルSuica',
    MDL_PG_MULPAY_EDY_PAY_TYPE     => 'Mobile Edy',
    MDL_PG_MULPAY_CONVENI_PAY_TYPE => 'コンビニ決済',
    MDL_PG_MULPAY_PAYEASY_PAY_TYPE => 'Pay-easy(ATM決済、ネットバンキング決済)',
    MDL_PG_MULPAY_PAYPAL_PAY_TYPE  => 'PayPal',
    MDL_PG_MULPAY_NETID_PAY_TYPE      => 'iDネット',
    MDL_PG_MULPAY_WEBMONEY_PAY_TYPE => 'WebMoney',
    MDL_PG_MULPAY_AU_PAY_TYPE => 'auかんたん決済',
);
// ネットバンキング決済金融機関選択画面誘導画面URL
define('MDL_PG_MULPAY_NETBUNK_PC_LINK_URL', "");
define('MDL_PG_MULPAY_NETBUNK_MOBILE_LINK_URL', "");

// 決済結果受信スリープ時間(秒)
define('MDL_PG_MULPAY_RECEIVE_WAIT_TIME', 2);

define('SUICA_MEG_TITLE', "モバイルSuicaでのお支払い");
define('SUICA_MEG_SUB_TITLE', "＜＜お支払い方法＞＞");
define('SUICA_MEG_BODY', "
1．ご入力されたメールアドレスに「決済依頼メール」が届きますので、メールの案内に従って支払い画面へ進んでください。
2．パスワード入力画面が表示されますので、パスワードを入力してログインしてください。
3．決済内容をご確認のうえ、「決済実行」を選択してください。
※詳細はモバイルSuica のサイト（http://www.jreast.co.jp/mobilesuica/use/sf/ecomars.html）をご覧ください。");

define('EDY_MEG_TITLE', "Mobile Edy でのお支払い");
define('EDY_MEG_SUB_TITLE', "＜＜お支払い方法＞＞");
define('EDY_MEG_BODY', "
1．ご入力されたメールアドレスに「Edy 決済開始メール」が届きますので、メールの案内に従って支払い画面へ進んでください。
2．取引内容をご確認のうえ、「お支払いはこちら」を選択してください。
3．Edy アプリのガイダンスに従って、内容をご確認のうえ、よろしければお支払いを実行してください。
※詳細はEdy のサイト（http://www.edy.jp/howto/pay/site.html）をご覧ください。");

define('ATM_MEG_TITLE', "銀行ＡＴＭ（ペイジー）でのお支払い");
define('ATM_MEG_SUB_TITLE', "－お支払いの前にお読みください－");
define('ATM_MEG_BODY', "
●以下の金融機関のATM でお支払いいただけます。
「みずほ銀行」、「りそな銀行」、「埼玉りそな銀行」、「三井住友銀行」、「ゆうちょ銀行」、「ちばぎん」
※一部時間外手数料が発生する金融機関がございます。詳しくはお取引の金融機関にお問合せください。
※法令改正のため、2007 年1 月4 日より、ATM から10 万円を超える現金の振込はできなくなりました。
●お支払いの際、収納機関番号、お客様番号、確認番号が必要です。
メモを取るか、このページを印刷してお持ちください。
●ご利用明細票が領収書となりますので、お支払い後必ずお受け取りください。
\n
＜＜お支払い方法＞＞
1. 上記の金融機関のATM で、「税金・料金払込み」を選択してください。
2. 収納機関番号 を入力し、「確認」を選択してください。
3. お客様番号 を入力し、「確認」を選択してください。
4. 確認番号 を入力し、「確認」を選択してください。
5. 表示される内容を確認のうえ、「確認」を選択してください。
6. 「現金」または「キャッシュカード」を選択し、お支払いください。
7. ご利用明細票を必ずお受け取りください。");

define('NETBANK_MEG_TITLE', "ネットバンキング（ペイジー）でのお支払い");
define('NETBANK_MEG_SUB_TITLE', "－お支払いについて－");
define('NETBANK_MEG_BODY', "
【ネットバンキング】
●金融機関にあらかじめ口座をお持ちの場合のみご利用いただけます。
＜＜お支払い方法＞＞
1. ご利用の金融機関の案内に従って、ペイジーでのお支払いにお進みください。
2. 収納機関番号、お客様番号、確認番号を入力してください。
3. お支払い内容を確認のうえ、料金をお支払いください。");

$GLOBALS['arrConveniMegTitle'] = array (
    CONVENI_LOSON         => "ローソンでのお支払い",
    CONVENI_FAMILYMART    => "ファミリーマートでのお支払い",
    CONVENI_SUNKUS        => "サークルＫサンクスでのお支払い",
    CONVENI_CIRCLEK       => "サークルＫサンクスでのお支払い",
    CONVENI_MINISTOP      => "ミニストップでのお支払い",
    CONVENI_DAILYYAMAZAKI => "デイリーヤマザキでのお支払い",
    CONVENI_SEVENELEVEN   => "セブンイレブンでのお支払い"
);

$GLOBALS['arrConveniMegSubTitle'] = array (
    CONVENI_LOSON         => "－お支払いの前にお読みください－",
    CONVENI_FAMILYMART    => "－お支払いの前にお読みください－",
    CONVENI_SUNKUS        => "－お支払いの前にお読みください－",
    CONVENI_CIRCLEK       => "－お支払いの前にお読みください－",
    CONVENI_MINISTOP      => "－お支払いの前にお読みください－",
    CONVENI_DAILYYAMAZAKI => "－お支払いの前にお読みください－",
    CONVENI_SEVENELEVEN   => "－お支払いの前にお読みください－"
);

$GLOBALS['arrConveniMegBody'] = array (
    CONVENI_LOSON         => "
●Loppi のあるローソン全店でお支払いいただけます。
Loppiで申込券を発行してから30 分以内にレジでお支払いください。
●お支払いの際、お客様番号と確認番号が必要です。
メモを取るか、このページを印刷して、コンビニまでお持ちください。
●取扱明細兼受領書が領収書となりますので、お支払い後必ずお受け取りください。
※30 万円を超えるお支払いはできません。
※コンビニ店頭でのお支払いにはEdy はご利用いただけません。現金でお支払いください。
\n
＜＜お支払い方法＞＞
1．トップページより「各種代金お支払い」を選択してください。
2．ジャンルより「各種代金お支払い」を選択してください。
3．「各種代金お支払い」のページで「マルチペイメントサービス」を選択してください。
4．お客様番号 を入力し、「次へ」ボタンをタッチしてください。
5．確認番号 を入力し、「次へ」ボタンをタッチしてください。
6．表示される内容を確認のうえ、「了解」ボタンをタッチしてください。
7．印刷された申込券をレジに渡し、30 分以内に現金でお支払いください。
8．お支払い後、「取扱明細兼受領書」を必ずお受け取りください。",
    CONVENI_FAMILYMART    => "
●Fami ポートのあるファミリーマート全店でお支払いいただけます。
Fami ポートで申込券を発行してから30 分以内にレジでお支払いください。
●お支払いの際、お客様番号と確認番号が必要です。
メモを取るか、このページを印刷して、コンビニまでお持ちください。
●取扱明細兼受領書が領収書となりますので、お支払い後必ずお受け取りください。
※30 万円を超えるお支払いはできません。
※コンビニ店頭でのお支払いにはEdy はご利用いただけません。現金でお支払いください。
\n
＜＜お支払い方法＞＞
1．トップページより「コンビニでお支払い」を選択してください。
2．ジャンルより「各種代金お支払い」を選択してください。
3．「各種代金お支払い」のページで「マルチペイメントサービス」を選択してください。
4．お客様番号 を入力し、「次へ」ボタンをタッチしてください。
5．確認番号 を入力し、「次へ」ボタンをタッチしてください。
6．表示される内容を確認のうえ、「了解」ボタンをタッチしてください。
7．印刷された申込券をレジに渡し、30 分以内に現金でお支払いください。
8．お支払い後、「取扱明細兼受領書」を必ずお受け取りください。",
    CONVENI_SUNKUS        => "
●サークルＫサンクス全店でお支払いいただけます。
●「オンライン決済」と店員にお伝えください。
●お支払いの際、オンライン決済番号が必要です。
メモを取るか、このページを印刷して、コンビニまでお持ちください。
※30 万円を超えるお支払いはできません。
※コンビニ店頭でのお支払いにはEdy はご利用いただけません。現金でお支払いください。
\n
＜＜お支払い方法＞＞
1．コンビニのレジスタッフに、上記オンライン決済番号をご提示して頂き、「オンライン決済」希望とお伝えください。
2．スタッフがレジを操作後に、入力画面が表示されますので、お客様がオンライン決済番号をご入力ください。
3．お支払い内容が表示されますので、内容が正しいことをご確認のうえ、「確定」ボタンを押してください。
4．現金で商品代金をお支払いください。
5．領収書(レシート形式)が発行されますので、必ずお受け取りください。",
    CONVENI_CIRCLEK       => "
●サークルＫサンクス全店でお支払いいただけます。
●「オンライン決済」と店員にお伝えください。
●お支払いの際、オンライン決済番号が必要です。
メモを取るか、このページを印刷して、コンビニまでお持ちください。
※30 万円を超えるお支払いはできません。
※コンビニ店頭でのお支払いにはEdy はご利用いただけません。現金でお支払いください。
\n
＜＜お支払い方法＞＞
1．コンビニのレジスタッフに、上記オンライン決済番号をご提示して頂き、「オンライン決済」希望とお伝えください。
2．スタッフがレジを操作後に、入力画面が表示されますので、お客様がオンライン決済番号をご入力ください。
3．お支払い内容が表示されますので、内容が正しいことをご確認のうえ、「確定」ボタンを押してください。
4．現金で商品代金をお支払いください。
5．領収書(レシート形式)が発行されますので、必ずお受け取りください。",
    CONVENI_MINISTOP      => "
●ミニストップ全店でお支払いいただけます。
●「オンライン決済」と店員にお伝えください。
●お支払いの際、オンライン決済番号が必要です。
メモを取るか、このページを印刷して、コンビニまでお持ちください。
※30 万円を超えるお支払いはできません。
※コンビニ店頭でのお支払いにはEdy はご利用いただけません。現金でお支払いください。
\n
＜＜お支払い方法＞＞
1．コンビニのレジスタッフに、上記オンライン決済番号をご提示して頂き、「オンライン決済」希望とお伝えください。
2．スタッフがレジを操作後に、入力画面が表示されますので、お客様がオンライン決済番号をご入力ください。
3．お支払い内容が表示されますので、内容が正しいことをご確認のうえ、「確定」ボタンを押してください。
4．現金で商品代金をお支払いください。
5．領収書(レシート形式)が発行されますので、必ずお受け取りください。",
    CONVENI_DAILYYAMAZAKI => "
●デイリーヤマザキ全店でお支払いいただけます。
●「オンライン決済」と店員にお伝えください。
●お支払いの際、オンライン決済番号が必要です。
メモを取るか、このページを印刷して、コンビニまでお持ちください。
※30 万円を超えるお支払いはできません。
※コンビニ店頭でのお支払いにはEdy はご利用いただけません。現金でお支払いください。
\n
＜＜お支払い方法＞＞
1．コンビニのレジスタッフに、上記オンライン決済番号をご提示して頂き、「オンライン決済」希望とお伝えください。
2．スタッフがレジを操作後に、入力画面が表示されますので、お客様がオンライン決済番号をご入力ください。
3．お支払い内容が表示されますので、内容が正しいことをご確認のうえ、「確定」ボタンを押してください。
4．現金で商品代金をお支払いください。
5．領収書(レシート形式)が発行されますので、必ずお受け取りください。",
    CONVENI_SEVENELEVEN => "
１ 払込票でのお支払い
払込票：メール等で通知される払込票ＵＲＬをクリックすると、払込票が表示されます。
\n
■−お支払いの前にお読みください−
●セブンイレブン全店でお支払いいただけます。
●メール等で通知される払込票ＵＲＬを表示して、そのページをプリントアウトして下さい。
●直接、レジにプリントアウトした払込票をご提示下さい。
※30 万円を超えるお支払いはできません。
※コンビニ店頭でのお支払いにはクレジットカード・プリペイドカードはご利用いただけません。現金でお支払いください。
\n
＜＜お支払い方法＞＞
1．メール等で通知される払込票ＵＲＬを表示して、そのページをプリントアウトして下さい。
2．直接、レジにプリントアウトした払込票をご提示下さい。
3．現金で商品代金をお支払いください。
4．インターネットショッピング払込受領証が発行されますので、必ずお受け取りください。
\n
２ 払込票番号でのお支払い
■払込票番号：%RECEIPT_NO%
\n
−お支払いの前にお読みください−
●セブンイレブン全店でお支払いいただけます。
●「インターネット支払い」と店員にお伝えください。
●お支払いの際、払込票番号が必要です。
メモを取るか、このページを印刷して、コンビニまでお持ちください。
※30 万円を超えるお支払いはできません。
※コンビニ店頭でのお支払いにはクレジットカード・プリペイドカードはご利用いただけません。現金でお支払いください。
\n
＜＜お支払い方法＞＞
1．コンビニのレジスタッフに、上記払込票番号をご提示して頂き、「インターネット支払い」希望とお伝えください。
2．現金で商品代金をお支払いください。
3．インターネットショッピング払込受領証が発行されますので、必ずお受け取りください。"
);

// 禁止半角記号
$GLOBALS['arrProhiditedKigo'] = array('^','`','{','|','}','~','&','<','>','"','\'');

// 完了メールテンプレートのID
define('MDL_PG_MULPAY_ORDER_MAIL_USE_CLASS_PURCHASE', true);
define('MDL_PG_MULPAY_ORDER_MAIL_DEFAULT_TEMPLATE', 1);
define('MDL_PG_MULPAY_ORDER_MAIL_SPHONE_TEMPLATE', 1);
define('MDL_PG_MULPAY_ORDER_MAIL_MOBILE_TEMPLATE', 1);

// 受注情報のロールバック時に、使用ポイントをロールバックするかどうか
define('MDL_PG_MULPAY_ROLLBACK_USE_POINT', true);

?>
