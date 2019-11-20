<!--{*
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
*}-->
<form name="form1" method="post" action="<!--{$smarty.server.PHP_SELF|h}-->" >
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="next">
<input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">

<!--{if $tpl_error != ""}-->
<font color="#ff0000"><!--{$tpl_error}--><br><!--{$tpl_error_detail}--></font><br><br>
<!--{/if}-->

<!--{if $show_attention}-->
<br><br>
<font color="#ff0000">
ご注文者情報を変更する場合は、MYページの「登録内容変更」から登録内容を変更してください。<br>
お届け先情報を変更する場合は、「お届け先の指定」まで戻って登録内容を変更してください。<br>
</font>
<!--{/if}-->

<!--{if $tpl_shipping_error != ""}-->
    <!--{$tpl_shipping_error}-->
<!--{/if}-->
<!--{if $tpl_exam_error != ""}-->
    <!--{$tpl_exam_error}-->
<!--{/if}-->
<!--{if $tpl_shipping_error == "" && $tpl_exam_error == "" }-->
<a href="http://c.atodene.jp/rule/" target="_blank"><img src="<!--{$TPL_URLPATH}-->img/banner/banner_atodene_m.gif"></a><br>
ジャックス・ペイメント・ソリューションズ株式会社が提供する後払い決済サービスです。<br>
購入商品の到着を確認してから、コンビニエンスストア・金融機関で後払いできる安心・簡単な決済方法です。<br>
請求書は、商品とは別に郵送されますので、発行から14日以内にお支払ください。<br>
<br>
後払い決済手数料：<span style="font-weight:bold;">0円（税込）</span><br>
ご利用限度額：<span style="color:#ff0000;">累計残高で54,000円（税込）迄（他店舗含む）</span><br>
<br>
<span style="color:#ff0000;">
    お客様は上記バナーをクリックし「注意事項」に記載の内容をご確認・ご承諾の上、<br>
    本サービスのお申し込みを行うものとします。
</span>
<br>
<br>
<center><input type="submit" value="次へ"></center>
<!--{/if}-->
</form>
<form action="./load_payment_module.php" method="post">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="return">
<center><input type="submit" value="戻る"></center>
</form>
