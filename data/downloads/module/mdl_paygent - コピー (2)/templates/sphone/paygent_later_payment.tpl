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
<!--{*** EC-CUBE 2.11.1以前用と2.11.2以降用の2種類のテンプレートを定義しています。 ***}-->
<!--{if preg_match('/^2\.11\.[0-1]$/', $smarty.const.ECCUBE_VERSION)}-->
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<script type="text/javascript">//<![CDATA[
var send = true;

function fnCheckSubmit(mode) {
    if(send) {
        send = false;
    fnModeSubmit(mode,'','');
        return true;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
}

function next(now, next) {
    if (now.value.length >= now.getAttribute('maxlength')) {
    next.focus();
    }
}
//]]>
</script>

<!--▼CONTENTS-->
<div id="under02column">
<div id="under02column_shopping">

  <h2 class="title"><!--{$tpl_payment_method}--><h2>

    <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="next">
  <table summary="お支払詳細入力" class="entryform">
    <tbody>
      <!--{if $tpl_error != ""}-->
      <tr>
        <td class="lefttd" colspan="2">
          <span class="attention"><!--{$tpl_error}--></span><br>
          <span class="attention"><!--{$tpl_error_detail}--></span>
        </td>
      </tr>
      <!--{/if}-->

      <!--{if $show_attention}-->
      <tr>
        <td class="lefttd" colspan="2">
          <br />
          <div class="attention">
          <!--{if $tpl_login}-->
            ご注文者情報を変更する場合は、MYページの「会員内容変更」から登録内容を変更してください。<br />
            お届け先情報を変更する場合は、「お届け先の指定」まで戻って登録内容を変更してください。<br />
          <!--{else}-->
            「お客様情報入力」まで戻って登録内容を変更してください。<br />
          <!--{/if}-->
          </div>
        </td>
      </tr>
      <!--{/if}-->

      <!--{if $tpl_shipping_error != ""}-->
      <tr>
          <td class="lefttd" colspan="2">
              <!--{$tpl_shipping_error}-->
          </td>
      </tr>
      <!--{/if}-->
      <!--{if $tpl_exam_error != ""}-->
      <tr>
          <td class="lefttd" colspan="2">
              <!--{$tpl_exam_error}-->
          </td>
      </tr>
      <!--{/if}-->
      <!--{if $tpl_shipping_error == "" && $tpl_exam_error == "" }-->
      <tr>
        <td class="lefttd">
            <!--{if $invoice_send_type == 3 }-->
                <a href="http://c.atodene.jp/d-rule/" target="_blank"><img src="<!--{$TPL_URLPATH}-->img/banner/banner_atodene_sp.gif" alt"後払い決済サービス「アトディーネ」" /></a><br />
                ジャックス・ペイメント・ソリューションズ株式会社が提供する後払い決済サービスです。<br />
                購入商品の到着を確認してからお支払できる安心・簡単な決済方法です。<br />
                請求書は商品に同封してお送りいたします（配送先がお客様住所と異なる場合を除きます。）。<br />
                請求書に記載されておりますお支払期限日までにコンビニエンスストア・金融機関からお支払ください。<br />
            <!--{else}-->
                <a href="http://c.atodene.jp/rule/" target="_blank"><img src="<!--{$TPL_URLPATH}-->img/banner/banner_atodene_sp.gif" alt"後払い決済サービス「アトディーネ」" /></a><br />
                ジャックス・ペイメント・ソリューションズ株式会社が提供する後払い決済サービスです。<br />
                購入商品の到着を確認してから、コンビニエンスストア・金融機関で後払いできる安心・簡単な決済方法です。<br />
                請求書は、商品とは別に郵送されますので、発行から14日以内にお支払ください。<br />
            <!--{/if}-->
                <br />
                後払い決済手数料：<span style="font-weight:bold;"><!--{$tpl_charge|default:0|number_format}-->円（税込）</span><br />
                ご利用限度額：<span style="color:#ff0000;">累計残高で税込54,000円迄（他店舗含む）</span><br />
                <br />
                <span style="color:#ff0000;">お客様は上記バナーをクリックし「注意事項」及び「個人情報の取扱いについて」に記載の内容をご確認・ご承認の上、<br />
                本サービスのお申し込みを行うものとします。<br />
                ※ご承認いただけない場合は本サービスのお申し込みをいただけませんので、ご了承ください。</span><br /><br />
                ※以下の場合サービスをご利用いただけません。予めご了承ください。<br />
                ・郵便局留め・運送会社営業所留め（営業所での引き取り）<br />
                ・商品の転送<br />
                ・コンビニ店頭での受け渡し<br />
        </td>
      </tr>
      <!--{/if}-->
    </tbody>
  </table>
  <div class="tblareabtn">
      <!--{if $tpl_shipping_error == "" && $tpl_exam_error == "" }-->
      <p><input type="submit" onclick="return fnCheckSubmit('next');" value="次へ" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="next" id="next" /></p>
      <!--{/if}-->
      <p><input type="submit" onclick="return fnCheckSubmit('return');" value="戻る" class="spbtn spbtn-medeum" alt="戻る" name="return" id="return" /></p>
  </div>
  </form>

  <form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="mode" value="return">
  </form>
</div>
</div>
<!--▲CONTENTS-->
<!--{else}-->
<script type="text/javascript">//<![CDATA[
var send = true;

window.onunload=function(){
}
window.onload=function onloadCashClear() {
	if (send) {
		return false;
	} else {
		sendo = true;
		return false;
	}
}

function fnCheckSubmit(mode) {
    if(send) {
        send = false;
        fnModeSubmit(mode,'','');
        return false;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
}

function next(now, next) {
    if (now.value.length >= now.getAttribute('maxlength')) {
    next.focus();
    }
}
//]]>
</script>

<!--▼コンテンツここから -->
<section id="undercolumn">

    <h2 class="title"><!--{$tpl_payment_method}--></h2>

    <!--★インフォメーション★-->
    <div class="information end">
        <!--{if $tpl_error != ""}-->
        <p><span class="attention"><!--{$tpl_error}--></span></p>
        <p><span class="attention"><!--{$tpl_error_detail}--></span></p>
        <!--{/if}-->

        <!--{if $show_attention}-->
          <br />
          <div class="attention">
          <!--{if $tpl_login}-->
            ご注文者情報を変更する場合は、MYページの「会員内容変更」から登録内容を変更してください。<br />
            お届け先情報を変更する場合は、「お届け先の指定」まで戻って登録内容を変更してください。<br />
          <!--{else}-->
            「お客様情報入力」まで戻って登録内容を変更してください。<br />
          <!--{/if}-->
          </div>
        <!--{/if}-->

        <!--{if $tpl_shipping_error != ""}-->
        <p><!--{$tpl_shipping_error}--></p>
        <!--{/if}-->
        <!--{if $tpl_exam_error != ""}-->
        <p><!--{$tpl_exam_error}--></p>
        <!--{/if}-->
    </div>

    <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="next">

        <!--{if $tpl_shipping_error == "" && $tpl_exam_error == "" }-->
        <dl class="form_entry">
            <dd id="customer_dd">
                <!--{if $invoice_send_type == 3 }-->
                    <a href="http://c.atodene.jp/d-rule/" target="_blank"><img src="<!--{$TPL_URLPATH}-->img/banner/banner_atodene_sp.gif" alt"後払い決済サービス「アトディーネ」" /></a><br />
                    ジャックス・ペイメント・ソリューションズ株式会社が提供する後払い決済サービスです。<br />
                    購入商品の到着を確認してからお支払できる安心・簡単な決済方法です。<br />
                    請求書は商品に同封してお送りいたします（配送先がお客様住所と異なる場合を除きます。）。<br />
                    請求書に記載されておりますお支払期限日までにコンビニエンスストア・金融機関からお支払ください。<br />
                <!--{else}-->
                    <a href="http://c.atodene.jp/rule/" target="_blank"><img src="<!--{$TPL_URLPATH}-->img/banner/banner_atodene_sp.gif" alt"後払い決済サービス「アトディーネ」" /></a><br />
                    ジャックス・ペイメント・ソリューションズ株式会社が提供する後払い決済サービスです。<br />
                    購入商品の到着を確認してから、コンビニエンスストア・金融機関で後払いできる安心・簡単な決済方法です。<br />
                    請求書は、商品とは別に郵送されますので、発行から14日以内にお支払ください。<br />
                <!--{/if}-->
                    <br />
                    後払い決済手数料：<span style="font-weight:bold;"><!--{$tpl_charge|default:0|number_format}-->円（税込）</span><br />
                    ご利用限度額：<span style="color:#ff0000;">累計残高で税込54,000円迄（他店舗含む）</span><br />
                    <br />
                    <span style="color:#ff0000;">お客様は上記バナーをクリックし「注意事項」及び「個人情報の取扱いについて」に記載の内容をご確認・ご承認の上、<br />
                    本サービスのお申し込みを行うものとします。<br />
                    ※ご承認いただけない場合は本サービスのお申し込みをいただけませんので、ご了承ください。</span><br /><br />
                    ※以下の場合サービスをご利用いただけません。予めご了承ください。<br />
                    ・郵便局留め・運送会社営業所留め（営業所での引き取り）<br />
                    ・商品の転送<br />
                    ・コンビニ店頭での受け渡し<br />
            </dd>
        </dl>
        <!--{/if}-->

        <div class="btn_area">
            <ul class="btn_btm">
                <!--{if $tpl_shipping_error == "" && $tpl_exam_error == "" }-->
                <li><a href="javascript:fnCheckSubmit('next');" class="btn">次へ</a></li>
                <!--{/if}-->
                <li><a href="javascript:fnCheckSubmit('return');" class="btn_back">戻る</a></li>
            </ul>
        </div>

        <iframe style="height:0px;width:0px;visibility:hidden" src="about:blank">
            this frame prevents back forward cache
        </iframe>

    </form>
</section>
<!--{/if}-->