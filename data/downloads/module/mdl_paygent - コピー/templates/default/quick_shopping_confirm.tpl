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
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.facebox/facebox.js"></script>
<link rel="stylesheet" type="text/css" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.facebox/facebox.css" media="screen" />

<!--{if $paygent_token_js_url}-->
  <script type="text/javascript" src="<!--{$paygent_token_js_url}-->" charset="UTF-8"></script>
<!--{/if}-->

<script type="text/javascript">//<![CDATA[
var send = false;

window.onunload=function onunloadCashClear() {
    if (send) {
        send = false;
        return false;
    } else {
        return false;
    }
}

window.onload=function onloadCashClear() {
    if (send) {
        send = false;
        return false;
    } else {
        return false;
    }
}

function fnCheckSubmit() {

    if (send) {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
    send = true;

    //セキュリティーコードが入っている場合にsubmitされてしまうのを防ぐために削除する
    if (document.form1.security_code != null) {
        document.form1.security_code.removeAttribute('name');
    }

    document.form1.mode.value = "confirm";
    document.form1.submit();
    return true;
}

function fnQuickCheckSubmit() {
    if(send) {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    } else {
        send = true;
	fnModeSubmit('quick', '', '');
        return true;
    }
}

var merchant_id= "<!--{$merchant_id}-->";
var token_key= "<!--{$token_key|h}-->";
var paygent_token_connect_url= "<!--{$paygent_token_connect_url}-->";

<!--{$token_js}-->

function startCreateToken() {

    //二重注文制御
    if(send) {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    } else {
        send = true;
        document.form1.mode.value = "quick";
        callCreateTokenCvc();
    }
}

$(document).ready(function() {
    $('a.expansion').facebox({
        loadingImage : '<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.facebox/loading.gif',
        closeImage   : '<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.facebox/closelabel.png'
    });
});
//]]></script>

<!--CONTENTS-->
<div id="undercolumn">
    <div id="undercolumn_shopping">
        <p class="flow_area"><img src="<!--{$TPL_URLPATH}-->img/picture/img_flow_03.jpg" alt="購入手続きの流れ" /></p>
        <h2 class="title"><!--{$tpl_title|h}--></h2>

        <p class="information">下記ご注文内容で送信してもよろしいでしょうか？<br />
            よろしければ、「<!--{if $use_module}-->次へ<!--{else}-->ご注文完了ページへ<!--{/if}-->」ボタンをクリックしてください。
	<!--{if $quick_Flg == "1"}-->
	    <br>前回と同じお支払い内容でお支払いを行う場合は、ページ下段の<br>「クイック決済」ボタンをクリックすると、注文を完了できます。
	<!--{/if}-->
	</p>

        <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
        <input type="hidden" name="card_token" value="">

        <div class="btn_area">
            <ul>
                <li>
                    <a href="./payment.php" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg', 'back04-top')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg', 'back04-top')"><img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back04-top" id="back04-top" /></a>
                </li>
                    <!--{if $use_module}-->
                <li>
                    <input type="image" onclick="return fnCheckSubmit();" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" name="next-top" id="next-top" />
                </li>
                    <!--{else}-->
                <li>
                    <input type="image" onclick="return fnCheckSubmit();" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_order_complete_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg" alt="ご注文完了ページへ" name="next-top" id="next-top" />
                </li>
                <!--{/if}-->
            </ul>
        </div>

        <table summary="ご注文内容確認">
            <colgroup width="10%"></colgroup>
            <colgroup width="40%"></colgroup>
            <colgroup width="20%"></colgroup>
            <colgroup width="10%"></colgroup>
            <colgroup width="20%"></colgroup>
            <tr>
                <th scope="col">商品写真</th>
                <th scope="col">商品名</th>
                <th scope="col">単価</th>
                <th scope="col">数量</th>
                <th scope="col">小計</th>
            </tr>
            <!--{foreach from=$arrCartItems item=item}-->
                <tr>
                    <td class="alignC">
                        <a
                            <!--{if $item.productsClass.main_image|strlen >= 1}--> href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$item.productsClass.main_image|sfNoImageMainList|h}-->" class="expansion" target="_blank"
                            <!--{/if}-->
                        >
                            <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=65&amp;height=65" alt="<!--{$item.productsClass.name|h}-->" /></a>
                    </td>
                    <td>
                        <ul>
                            <li><strong><!--{$item.productsClass.name|h}--></strong></li>
                            <!--{if $item.productsClass.classcategory_name1 != ""}-->
                            <li><!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--></li>
                            <!--{/if}-->
                            <!--{if $item.productsClass.classcategory_name2 != ""}-->
                            <li><!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}--></li>
                            <!--{/if}-->
                        </ul>
                    </td>
                    <td class="alignR">
                        <!--{$item.price|sfCalcIncTax|number_format}-->円
                    </td>
                    <td class="alignR"><!--{$item.quantity|number_format}--></td>
                    <td class="alignR"><!--{$item.total_inctax|number_format}-->円</td>
                </tr>
            <!--{/foreach}-->
            <tr>
                <th colspan="4" class="alignR" scope="row">小計</th>
                <td class="alignR"><!--{$tpl_total_inctax[$cartKey]|number_format}-->円</td>
            </tr>
            <!--{if $smarty.const.USE_POINT !== false}-->
                <tr>
                    <th colspan="4" class="alignR" scope="row">値引き（ポイントご使用時）</th>
                    <td class="alignR">
                        <!--{assign var=discount value=`$arrForm.use_point*$smarty.const.POINT_VALUE`}-->
                        -<!--{$discount|number_format|default:0}-->円</td>
                </tr>
            <!--{/if}-->
            <tr>
                <th colspan="4" class="alignR" scope="row">送料</th>
                <td class="alignR"><!--{$arrForm.deliv_fee|number_format}-->円</td>
            </tr>
            <tr>
                <th colspan="4" class="alignR" scope="row">手数料</th>
                <td class="alignR"><!--{$arrForm.charge|number_format}-->円</td>
            </tr>
            <tr>
                <th colspan="4" class="alignR" scope="row">合計</th>
                <td class="alignR"><span class="price"><!--{$arrForm.payment_total|number_format}-->円</span></td>
            </tr>
        </table>

        <!--{* ログイン済みの会員のみ *}-->
        <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
            <table summary="ポイント確認" class="delivname">
            <colgroup width="30%"></colgroup>
            <colgroup width="70%"></colgroup>
                <tr>
                    <th scope="row">ご注文前のポイント</th>
                    <td><!--{$tpl_user_point|number_format|default:0}-->Pt</td>
                </tr>
                <tr>
                    <th scope="row">ご使用ポイント</th>
                    <td>-<!--{$arrForm.use_point|number_format|default:0}-->Pt</td>
                </tr>
                <!--{if $arrForm.birth_point > 0}-->
                <tr>
                    <th scope="row">お誕生月ポイント</th>
                    <td>+<!--{$arrForm.birth_point|number_format|default:0}-->Pt</td>
                </tr>
                <!--{/if}-->
                <tr>
                    <th scope="row">今回加算予定のポイント</th>
                    <td>+<!--{$arrForm.add_point|number_format|default:0}-->Pt</td>
                </tr>
                <tr>
                <!--{assign var=total_point value=`$tpl_user_point-$arrForm.use_point+$arrForm.add_point`}-->
                    <th scope="row">加算後のポイント</th>
                    <td><!--{$total_point|number_format}-->Pt</td>
                </tr>
            </table>
        <!--{/if}-->
        <!--{* ログイン済みの会員のみ *}-->

        <!--お届け先ここから-->
        <!--{* 販売方法判定（ダウンロード販売のみの場合はお届け先を表示しない） *}-->
        <!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
        <!--{foreach item=shippingItem from=$arrShipping name=shippingItem}-->
        <h3>お届け先<!--{if $is_multiple}--><!--{$smarty.foreach.shippingItem.iteration}--><!--{/if}--></h3>
        <!--{if $is_multiple}-->
            <table summary="ご注文内容確認">
                <colgroup width="10%"></colgroup>
                <colgroup width="60%"></colgroup>
                <colgroup width="20%"></colgroup>
                <colgroup width="10%"></colgroup>
                <tr>
                    <th scope="col">商品写真</th>
                    <th scope="col">商品名</th>
                    <th scope="col">単価</th>
                    <th scope="col">数量</th>
                    <!--{* XXX 購入小計と誤差が出るためコメントアウト
                    <th scope="col">小計</th>
                    *}-->
                </tr>
                <!--{foreach item=item from=$shippingItem.shipment_item}-->
                    <tr>
                        <td class="alignC">
                            <a
                                <!--{if $item.productsClass.main_image|strlen >= 1}--> href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$item.productsClass.main_image|sfNoImageMainList|h}-->" class="expansion" target="_blank"
                                <!--{/if}-->
                            >
                                <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=65&amp;height=65" alt="<!--{$item.productsClass.name|h}-->" /></a>
                        </td>
                        <td><!--{* 商品名 *}--><strong><!--{$item.productsClass.name|h}--></strong><br />
                            <!--{if $item.productsClass.classcategory_name1 != ""}-->
                                <!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--><br />
                            <!--{/if}-->
                            <!--{if $item.productsClass.classcategory_name2 != ""}-->
                                <!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}-->
                            <!--{/if}-->
                        </td>
                        <td class="alignR">
                            <!--{$item.price|sfCalcIncTax|number_format}-->円
                        </td>
                        <td class="alignC"><!--{$item.quantity}--></td>
                        <!--{* XXX 購入小計と誤差が出るためコメントアウト
                        <td class="alignR"><!--{$item.total_inctax|number_format}-->円</td>
                        *}-->
                    </tr>
                <!--{/foreach}-->
            </table>
        <!--{/if}-->

        <table summary="お届け先確認" class="delivname">
            <colgroup width="30%"></colgroup>
            <colgroup width="70%"></colgroup>
            <tbody>
                <tr>
                    <th scope="row">お名前</th>
                    <td><!--{$shippingItem.shipping_name01|h}--> <!--{$shippingItem.shipping_name02|h}--></td>
                </tr>
                <tr>
                    <th scope="row">お名前(フリガナ)</th>
                    <td><!--{$shippingItem.shipping_kana01|h}--> <!--{$shippingItem.shipping_kana02|h}--></td>
                </tr>
                <tr>
                    <th scope="row">郵便番号</th>
                    <td>〒<!--{$shippingItem.shipping_zip01|h}-->-<!--{$shippingItem.shipping_zip02|h}--></td>
                </tr>
                <tr>
                    <th scope="row">住所</th>
                    <td><!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01|h}--><!--{$shippingItem.shipping_addr02|h}--></td>
                </tr>
                <tr>
                    <th scope="row">電話番号</th>
                    <td><!--{$shippingItem.shipping_tel01}-->-<!--{$shippingItem.shipping_tel02}-->-<!--{$shippingItem.shipping_tel03}--></td>
                </tr>
                <tr>
                    <th scope="row">FAX番号</th>
                    <td>
                        <!--{if $shippingItem.shipping_fax01 > 0}-->
                            <!--{$shippingItem.shipping_fax01}-->-<!--{$shippingItem.shipping_fax02}-->-<!--{$shippingItem.shipping_fax03}-->
                        <!--{/if}-->
                    </td>
                </tr>
            <!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
                <tr>
                    <th scope="row">お届け日</th>
                    <td><!--{$shippingItem.shipping_date|default:"指定なし"|h}--></td>
                </tr>
                <tr>
                    <th scope="row">お届け時間</th>
                    <td><!--{$shippingItem.shipping_time|default:"指定なし"|h}--></td>
                </tr>
            <!--{/if}-->
            </tbody>
        </table>
	<p class="alignC"><a href="<!--{$smarty.const.DELIV_URLPATH}-->">お届け先を変更</a></p>
        <!--{/foreach}-->
        <!--{/if}-->
        <!--お届け先ここまで-->

        <h3>配送方法・お支払方法・その他お問い合わせ</h3>
        <table summary="配送方法・お支払方法・その他お問い合わせ" class="delivname">
            <colgroup width="30%"></colgroup>
            <colgroup width="70%"></colgroup>
            <tbody>
            <tr>
                <th scope="row">配送方法</th>
                <td><!--{$arrDeliv[$arrForm.deliv_id]|h}--></td>
            </tr>
            <tr>
                <th scope="row">お支払方法</th>
                <td><!--{$arrForm.payment_method|h}--></td>
            </tr>
	    <!--{if $quick_Flg == "1"}-->
	    <!--{if $memo03 == PAY_PAYGENT_CREDIT}-->
	    <tr>
		<th scope="row">支払回数</th>
		<td><!--{$paymentDivision|h}--></td>
	    </tr>
	    <tr>
		<th scope="row">カード番号</th>
		<td><!--{$card_info.CardNo|h}--></td>
	    </tr>
	    <!--{if $security_code_flg == 1}-->
	      <tr id="card_security">
		<th scope="row">セキュリティコード</th>
		<td>
		 <!--{assign var=key1 value="security_code"}-->
		 <input type="text" name="<!--{$key1}-->" value="<!--{$security_code}-->" maxlength="4" style="ime-mode: disabled;"  size="4">
		 <span class="attention">※セキュリティコードを入力してください</span>
		</td>
	     </tr>
	    <!--{/if}-->
	    <tr>
		<th scope="row">有効期限</th>
		<td><!--{$card_info.Expire|substr:0:2}-->月/<!--{$card_info.Expire|substr:2:4}-->年</td>
	    </tr>
	    <tr>
		<th scope="row">カード名義人</th>
		<td><!--{$card_info.HolderName|h}--></td>
	    </tr>
	    <!--{/if}-->
	    <!--{if $memo03 == PAY_PAYGENT_CONVENI_NUM}-->
	    <tr>
		<th scope="row">コンビニ選択</th>
		<td><!--{$convenience|h}--></td>
	    </tr>
	    <tr>
		<th scope="row">利用者（姓名）</th>
		<td><!--{$quick_memo.customer_family_name|h}--> <!--{$quick_memo.customer_name|h}--></td>
	    </tr>
	    <tr>
		<th scope="row">利用者（カナ）</th>
		<td><!--{$quick_memo.customer_family_name_kana|h}--> <!--{$quick_memo.customer_name_kana|h}--></td>
	    </tr>
	    <tr>
		<th scope="row">お電話番号</th>
		<td><!--{$quick_memo.customer_tel|h}--></td>
	    </tr>
	    <!--{/if}-->
	    <!--{if $memo03 == PAY_PAYGENT_ATM || $memo03 == PAY_PAYGENT_BANK}-->
	    <tr>
		<th scope="row">利用者（姓名）</th>
		<td><!--{$quick_memo.customer_family_name|h}--> <!--{$quick_memo.customer_name|h}--></td>
	    </tr>
	    <tr>
		<th scope="row">利用者（カナ）</th>
		<td><!--{$quick_memo.customer_family_name_kana|h}--> <!--{$quick_memo.customer_name_kana|h}--></td>
	    </tr>
	    <!--{/if}-->
		<!--{if $memo03 == PAY_PAYGENT_EMONEY}-->
			<tr>
				<th scope="row">利用決済選択</th>
				<td><!--{$emoney|h}--></td>
			</tr>
		<!--{/if}-->
            <!--{/if}-->
            <tr>
                <th scope="row">その他お問い合わせ</th>
                <td><!--{$arrForm.message|h|nl2br}--></td>
            </tr>
	    <!--{if $quick_Flg == "1"}-->
	    <!--{if $memo03 == PAY_PAYGENT_CONVENI_NUM}-->
	    <tr>
		<td colspan="2">ご選択いただきましたコンビニエンスストアでのお支払いが可能です（各支払い方法は下記一覧をご確認ください）。
		<br>なお、商品はお支払い後のご提供となります。</td>
	    </tr>
	    <!--{/if}-->
	    <!--{if $memo03 == PAY_PAYGENT_ATM}-->
	    <tr>
		<td colspan="2">ゆうちょ銀行など「ペイジー」マークのあるATMで支払番号を入力してお支払いいただけます。
		<br>なお、商品はお支払い後のご提供となります。</td>
	    </tr>
            <!--{/if}-->
	    <!--{if $memo03 == PAY_PAYGENT_BANK}-->
	    <tr>
		<td colspan="2">三菱UFJ銀行などメガバンク、JNB、楽天銀行、ゆうちょ銀行など<br>全国1,400行以上のネットバンキングでお支払いが可能です。
		<br>なお、商品はお支払い後のご提供となります。</td>
	    </tr>
            <!--{/if}-->
        <!--{if $memo03 == PAY_PAYGENT_EMONEY}-->
	    <tr>
		<td colspan="2">プリペイドカードおよびウォレットにチャージされている電子マネーからお支払いいただけます。
		<br>（各支払い方法は下記一覧をご確認ください）</td>
	    </tr>
            <!--{/if}-->
	    <!--{/if}-->
            </tbody>
        </table>
	<!--{if $memo03 != PAY_PAYGENT_CAREER}-->
	<!--{if $memo03 != PAY_PAYGENT_YAHOOWALLET}-->
	<!--{if $quick_Flg == "1"}-->
	<p class="information">上記のお支払い内容でお支払いを行う場合は、「クイック決済」ボタンをクリックすると、注文を完了できます。<br>
	お支払い内容を変更する場合は、「次へ」ボタンをクリックし、決済情報入力画面で、入力をお願い致します。
	</p>
	<!--{/if}-->
	<!--{/if}-->
	<!--{/if}-->

        <div class="btn_area">
            <ul>
                <li>
                    <a href="./payment.php" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg','back<!--{$key}-->');" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg','back<!--{$key}-->');"><img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" name="back<!--{$key}-->" /></a>
                </li>
                <!--{if $use_module}-->
                <li>
                    <input type="image" onclick="return fnCheckSubmit();" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" name="next" id="next" />
                </li>
        <!--{if $memo03 != PAY_PAYGENT_CAREER}-->
        <!--{if $memo03 != PAY_PAYGENT_YAHOOWALLET}-->
		<!--{if $quick_Flg == "1"}-->
                <li>
                    <!--{if $security_code_flg && $token_pay == 1}-->
                    <input type="image" onclick="startCreateToken();return false" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_quickkessai_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_quickkessai_off.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_quickkessai_off.jpg" alt="クイック決済" name="quick" id="quick" />
                    <!--{else}-->
                    <input type="image" onclick="return fnQuickCheckSubmit();" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_quickkessai_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_quickkessai_off.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_quickkessai_off.jpg" alt="クイック決済" name="quick" id="quick" />
                    <!--{/if}-->
                </li>
		<!--{/if}-->
		<!--{/if}-->
		<!--{/if}-->
                <!--{else}-->
                <li>
                    <input type="image" onclick="return fnCheckSubmit();" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_order_complete_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg" alt="ご注文完了ページへ"  name="next" id="next" />
                </li>
                <!--{/if}-->
        </ul>

        </div>

	<!--{if $quick_Flg == "1"}-->
	<!--{if $memo03 == PAY_PAYGENT_CONVENI_NUM}-->
	<h3>お支払方法説明</h3>
	<table>
	    <tbody>
	      <tr>
		<th>セブン-イレブン</th>
	        <td class="lefttd">セブン-イレブンのレジ店頭にてお支払いが可能です。</td>
	      </tr>
	      <tr>
		<th>ファミリーマート</th>
	        <td class="lefttd">ファミリーマート店内に設置されている「Famiポート」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。</td>
	      </tr>
	      <tr>
		<th>ローソン、ミニストップ</th>
	        <td class="lefttd">ローソン、ミニストップ店内に設置されている「Loppi」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。</td>
	      </tr>
	      <tr>
		<th>デイリーヤマザキ</th>
	        <td class="lefttd">デイリーヤマザキのレジ店頭にてお支払いが可能です。</td>
	      </tr>
	      <tr>
		<th>セイコーマート</th>
	        <td class="lefttd">セイコーマート店内に設置されている「クラブステーション」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。</td>
	      </tr>
	    </tbody>
	</table>
    <!--{/if}-->
    <!--{if $memo03 == PAY_PAYGENT_EMONEY}-->
	<h3>お支払方法説明</h3>
	<table>
	    <tbody>
	      <tr>
		<th>WebMoney</th>
	        <td class="lefttd">プリペイドカードご利用の場合はカード記載の番号、ウォレットご利用の場合はウォレットID/パスワードに続いてセキュアパスワードを入力することによりお支払が可能です。</td>
	      </tr>
	    </tbody>
	</table>
        <!--{/if}-->
	<!--{/if}-->

        </form>
    </div>
</div>
