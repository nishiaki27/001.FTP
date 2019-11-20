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
var api_key = "<!--{$api_key}-->";
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
<!--{*** EC-CUBE 2.11.1以前用と2.11.2以降用の2種類のテンプレートを定義しています。 ***}-->
<!--{if preg_match('/^2\.11\.[0-1]$/', $smarty.const.ECCUBE_VERSION)}-->
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<!--▼CONTENTS-->
<div id="under02column">
    <div id="under02column_shopping">
        <h2 class="title"><!--{$tpl_payment_method}--><h2>
        <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="next" />
            <input type="hidden" name="amount" value="" id="amount" />
            <input type="hidden" name="currency" value="" id="currency" />
            <input type="hidden" name="created_at" value="" id="created_at" />
            <input type="hidden" name="id" value="" id="id" />
            <input type="hidden" name="status" value="" id="status" />
            <div id="blackout"></div>
            <table summary="お支払詳細入力" class="entryform">
                <tbody>
                    <!--{if $tpl_shipping_error != ""}-->
                    <tr>
                        <td class="lefttd" colspan="2">
                            <!--{$tpl_shipping_error}-->
                        </td>
                    </tr>
                    <!--{/if}-->
                    <tr>
                        <td class="lefttd">
                            <center><img src="<!--{$TPL_URLPATH}-->img/banner/banner_paidy_checkout_all.png" alt="Paidy翌月払い（コンビニ/銀行）" /></center><br/><br/>
                            <br>
                            <b>メールアドレスと携帯電話番号だけ</b>でご利用いただける決済方法です。事前登録・クレジットカードは必要ありません。<br/>
                            月に何回お買い物をしても、<b>お支払いは翌月にまとめて１回</b>。1ヶ月分のご利用金額は、翌月1日に確定し、メールとSMSでお知らせします。<br/>
                            <br>
                            下記のお支払い方法がご利用いただけます。
                            <ul>
                            <li><b>口座振替</b>(支払手数料：無料)</li>
                            <li><b>コンビニ</b>(支払手数料：350円税込)</li>
                            <li><b>銀行振込</b>(支払手数料：金融機関により異なります)</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="tblareabtn">
                <!--{if $tpl_shipping_error == ""}-->
                <p><div id="next"><a rel="external" href="javascript:checkPaidyPay('next');" class="spbtn spbtn-shopping" id="next">次へ</a></div></p>    
                <!--{/if}-->
                <p><div id="back"><a rel="external" href="javascript:checkPaidyPay('return');" class="spbtn spbtn-medeum" id="back03">戻る</a></div></p>
            </div>
        </form>
    </div>
</div>
<!--{else}-->
<!--▼CONTENTS-->
<section id="undercolumn">
    <h2 class="title"><!--{$tpl_title|h}--></h2>
    
    <div id="blackout"></div>

    <tr>
        <td class="lefttd" colspan="2">
            <!--{$tpl_shipping_error}-->
        </td>
    </tr>
    <div class="information">
        <p>
            <center><img src="<!--{$TPL_URLPATH}-->img/banner/banner_paidy_checkout_all.png" alt="Paidy翌月払い（コンビニ/銀行）" /></center><br/><br/>
            <b>メールアドレスと携帯電話番号だけ</b>でご利用いただける決済方法です。事前登録・クレジットカードは必要ありません。<br/>
            月に何回お買い物をしても、<b>お支払いは翌月にまとめて１回</b>。1ヶ月分のご利用金額は、翌月1日に確定し、メールとSMSでお知らせします。<br/>
            <br/>
            下記のお支払い方法がご利用いただけます。
            <ul>
            <li><b>口座振替</b>(支払手数料：無料)</li>
            <li><b>コンビニ</b>(支払手数料：350円税込)</li>
            <li><b>銀行振込</b>(支払手数料：金融機関により異なります)</li>
            </ul>
        </p>
    </div>

    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="next" />
        <input type="hidden" name="amount" value="" id="amount" />
        <input type="hidden" name="currency" value="" id="currency" />
        <input type="hidden" name="created_at" value="" id="created_at" />
        <input type="hidden" name="id" value="" id="id" />
        <input type="hidden" name="status" value="" id="status" />
        <div class="btn_area">
            <ul class="btn_btm">
                <!--{if $tpl_shipping_error == ""}-->
                <li><div id="next"><a rel="external" href="javascript:checkPaidyPay('next');" class="btn" id="next">次へ</a></div></li>
                <!--{/if}-->
                <li><div id="back"><a rel="external" href="javascript:checkPaidyPay('return');" class="btn btn_back" id="back03">戻る</a></div></li>
            </ul>
        </div>
    </form>
</section>
<!--{/if}-->