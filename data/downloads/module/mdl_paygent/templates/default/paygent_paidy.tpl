<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2007 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
<script type="text/javascript" src="https://apps.paidy.com"></script>
<script type="text/javascript">//<![CDATA[
var api_key  = "<!--{$api_key}-->";
var logo_url = "<!--{$logo_url}-->";
var payload = <!--{$json_paidy|default:"no_data"}-->;
<!--{$paidy_js}-->
//]]></script>
<style>
div.blackout {
    position: absolute;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 100%;
    margin: 0px 0px 0px 0px;
    background-color:black;
    opacity: 0.5;
    display: flex;
    align-items: center;
    justify-content: center;
}
div.blackout span {
    display: flex;
    width: 100px;
    height: 2px;
}
</style>
<!--▼CONTENTS-->
<div id="under02column">
<div id="blackout"></div>
<div id="under02column_shopping">
  <p class="flow_area"><img src="<!--{$TPL_URLPATH}-->img/picture/img_flow_03.jpg" alt="購入手続きの流れ" /></p>

  <h2 class="title1"><!--{$tpl_payment_method}--></h2>

<!-- <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off"> -->
  <form name="form1" id="form1" method="post" action="?">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <input type="hidden" name="mode" value="next">
  <input type="hidden" name="amount" value="" id="amount" />
  <input type="hidden" name="currency" value="" id="currency" />
  <input type="hidden" name="created_at" value="" id="created_at" />
  <input type="hidden" name="id" value="" id="id" />
  <input type="hidden" name="status" value="" id="status" />
  <table summary="お支払詳細入力" class="delivname">
    <tbody>
      <!--{if $tpl_shipping_error != "" }-->
      <tr>
        <td class="lefttd">
          <!--{$tpl_shipping_error}-->
        </td>
      </tr>
      <!--{/if}-->
      <!--{if $tpl_shipping_error == ""}-->
      <tr>
        <td class="lefttd">
          <img src="<!--{$TPL_URLPATH}-->img/banner/banner_paidy_checkout_all.png" alt="Paidy翌月払い（コンビニ/銀行）" /><br/><br/>
            <b>メールアドレスと携帯電話番号だけ</b>でご利用いただける決済方法です。事前登録・クレジットカードは必要ありません。<br/>
            月に何回お買い物をしても、<b>お支払いは翌月にまとめて１回</b>。1ヶ月分のご利用金額は、翌月1日に確定し、メールとSMSでお知らせします。<br/>
            <br/>
            下記のお支払い方法がご利用いただけます。
            <ul>
            <li><b>口座振替</b>(支払手数料：無料)</li>
            <li><b>コンビニ</b>(支払手数料：350円税込)</li>
            <li><b>銀行振込</b>(支払手数料：金融機関により異なります)</li>
            </ul>
        </td>
      </tr>
      <!--{/if}-->
    </tbody>
  </table>
  <div class="btn_area">
    <ul>
      <li>
        <input type="image" onclick="checkPaidyPay('return');return false" class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_keep_shopping.png" alt="戻る" name="back03" id="back03" />
      </li>
      <!--{if $tpl_shipping_error == ""}-->
      <li>
        <input type="image" onclick="checkPaidyPay('next');return false" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" border="0" name="next" id="next" />
      </li>
      <!--{/if}-->
    </ul>
  </div>
  </form>
</div>
</div>
<!--▲CONTENTS-->