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

<!--{if $paygent_token_js_url}-->
  <script type="text/javascript" src="<!--{$paygent_token_js_url}-->" charset="UTF-8"></script>
<!--{/if}-->

<!--{*** EC-CUBE 2.11.1以前用と2.11.2以降用の2種類のテンプレートを定義しています。 ***}-->
<!--{if preg_match('/^2\.11\.[0-1]$/', $smarty.const.ECCUBE_VERSION)}-->
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

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
    $('a.expansion').fancybox();
});
//]]></script>

<!--▼CONTENTS-->
<div id="under02column">
    <div id="under02column_shopping">
         <h2 class="title"><!--{$tpl_title|h}--></h2>

        <p>下記ご注文内容で送信してもよろしいでしょうか？<br />
            よろしければ、「<!--{if $use_module}-->次へ<!--{else}-->ご注文完了ページへ<!--{/if}-->」ボタンをクリックしてください。</p>

        <form name="form1" id="form1" method="post" action="?">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="confirm" />
            <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
            <input type="hidden" name="card_token" value="">
            <table summary="ご注文内容確認" class="entryform">
                <tr>
                    <th class="alignC confirm_ph valignM">商品写真</th>
                    <th class="alignC valignM">商品名</th>
                    <th class="alignC valignM">数量</th>
                    <th class="alignC valignM">小計</th>
                </tr>
                <!--{foreach from=$arrCartItems item=item}-->
                <tr>
                    <td class="phototd">
                        <a
                            <!--{if $item.productsClass.main_image|strlen >= 1}-->
                                href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$item.productsClass.main_image|sfNoImageMainList|h}-->"
                                class="expansion"
                                target="_blank"
                            <!--{/if}-->
                        >
                            <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=40&amp;height=40" alt="<!--{$item.productsClass.name|h}-->" /></a>
                    </td>
                    <td class="detailtdName"><strong><!--{$item.productsClass.name|h}--></strong>
                            <!--{if $item.productsClass.classcategory_name1 != ""}-->
                            <!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--><br />
                            <!--{/if}-->
                            <!--{if $item.productsClass.classcategory_name2 != ""}-->
                            <!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}-->
                            <!--{/if}--><br />
<!--{$item.productsClass.price02|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|number_format}-->円
                 </td>
                 <td class="detailtdNumber"><!--{$item.quantity|number_format}--></td>
                 <td class="alignR"><!--{$item.total_inctax|number_format}-->円</td>
             </tr>
             <!--{/foreach}-->
                <tr>
                    <th colspan="3" class="resulttd">小計</th>
                    <td class="alignR"><!--{$tpl_total_inctax[$cartKey]|number_format}-->円</td>
                </tr>
                <!--{if $smarty.const.USE_POINT !== false}-->
                    <tr>
                        <th colspan="3" class="resulttd">値引き（ポイントご使用時）</th>
                        <td class="alignR">
                        <!--{assign var=discount value=`$arrForm.use_point*$smarty.const.POINT_VALUE`}-->
                         -<!--{$discount|number_format|default:0}-->円</td>
                    </tr>
                <!--{/if}-->
                <tr>
                    <th colspan="3" class="resulttd">送料</th>
                    <td class="pricetd"><!--{$arrForm.deliv_fee|number_format}-->円</td>
                </tr>
                <tr>
                    <th colspan="3" class="resulttd">手数料</th>
                    <td class="pricetd"><!--{$arrForm.charge|number_format}-->円</td>
                </tr>
                <tr>
                    <th colspan="3" class="resulttd">合計</th>
                    <td class="pricetd"><em><!--{$arrForm.payment_total|number_format}-->円</em></td>
                </tr>
            </table>

            <!--{* ログイン済みの会員のみ *}-->
            <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
                <table summary="ポイント確認" class="entryform">
                    <tr>
                        <th class="trpoint">ご注文前のポイント</th>
                        <td class="alignR"><!--{$tpl_user_point|number_format|default:0}-->Pt</td>
                    </tr>
                    <tr>
                        <th class="trpoint">ご使用ポイント</th>
                        <td class="alignR">-<!--{$arrForm.use_point|number_format|default:0}-->Pt</td>
                    </tr>
                    <!--{if $arrForm.birth_point > 0}-->
                    <tr>
                        <th class="trpoint">お誕生月ポイント</th>
                        <td class="alignR">+<!--{$arrForm.birth_point|number_format|default:0}-->Pt</td>
                    </tr>
                    <!--{/if}-->
                    <tr>
                        <th class="trpoint">今回加算予定のポイント</th>
                        <td class="alignR">+<!--{$arrForm.add_point|number_format|default:0}-->Pt</td>
                    </tr>
                    <tr>
                    <!--{assign var=total_point value=`$tpl_user_point-$arrForm.use_point+$arrForm.add_point`}-->
                        <th class="trpoint">加算後のポイント</th>
                        <td class="alignR"><!--{$total_point|number_format}-->Pt</td>
                    </tr>
                </table>
            <!--{/if}-->
            <!--{* ログイン済みの会員のみ *}-->

            <!--お届け先ここから-->
            <!--{* 販売方法判定（ダウンロード販売のみの場合はお届け先を表示しない） *}-->
            <!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
            <!--{foreach item=shippingItem from=$arrShipping name=shippingItem}-->
            <h2>お届け先<!--{if $is_multiple}--><!--{$smarty.foreach.shippingItem.iteration}--><!--{/if}--></h2>
           <!--{if $is_multiple}-->
            <table summary="ご注文内容確認" class="entryform">
              <tr>
                <th class="alignC valignM">商品写真</th>
                <th class="alignC valignM">商品名</th>
                <th class="alignC valignM">単価</th>
                <th class="alignC valignM">数量</th>
                <!--{* XXX 購入小計と誤差が出るためコメントアウト
                <th>小計</th>
                *}-->
              </tr>
              <!--{foreach item=item from=$shippingItem.shipment_item}-->
                  <tr>
                      <td class="phototd">
                        <a
                            <!--{if $item.productsClass.main_image|strlen >= 1}-->
                                href="<!--{$smarty.const.IMAGE_SAVE_URL}--><!--{$item.productsClass.main_image|sfNoImageMainList|h}-->"
                                class="expansion"
                                target="_blank"
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
                          <!--{$item.productsClass.price02|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|number_format}-->円
                      </td>
                      <td class="detailtdNumber"><!--{$item.quantity}--></td>
                      <!--{* XXX 購入小計と誤差が出るためコメントアウト
                      <td class="alignR"><!--{$item.total_inctax|number_format}-->円</td>
                      *}-->
                  </tr>
              <!--{/foreach}-->
            </table>
           <!--{/if}-->

            <table summary="お届け先確認" class="entryform">
                <tbody>
                    <tr>
                        <th>お名前</th>
                        <td><!--{$shippingItem.shipping_name01|h}--> <!--{$shippingItem.shipping_name02|h}--></td>
                    </tr>
                    <tr>
                        <th>お名前(フリガナ)</th>
                        <td><!--{$shippingItem.shipping_kana01|h}--> <!--{$shippingItem.shipping_kana02|h}--></td>
                    </tr>
                    <tr>
                        <th>郵便番号</th>
                        <td>〒<!--{$shippingItem.shipping_zip01|h}-->-<!--{$shippingItem.shipping_zip02|h}--></td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td><!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01|h}--><!--{$shippingItem.shipping_addr02|h}--></td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td><!--{$shippingItem.shipping_tel01}-->-<!--{$shippingItem.shipping_tel02}-->-<!--{$shippingItem.shipping_tel03}--></td>
                    </tr>
                <!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
                    <tr>
                        <th>お届け日</th>
                        <td><!--{$shippingItem.shipping_date|default:"指定なし"|h}--></td>
                    </tr>
                    <tr>
                       <th>お届け時間</th>
                        <td><!--{$shippingItem.shipping_time|default:"指定なし"|h}--></td>
                    </tr>
                <!--{/if}-->
                </tbody>
            </table>
        <p><a href="<!--{$smarty.const.DELIV_URLPATH}-->">お届け先を変更</a></p>
            <!--{/foreach}-->
            <!--{/if}-->
            <!--お届け先ここまで-->
<h2>その他指定・お問い合わせ</h2>
            <table summary="配送方法・お支払方法・お届け日時の指定・その他お問い合わせ" class="entryform">
                <tbody>
                <tr>
                    <th>配送方法</th>
                    <td><!--{$arrDeliv[$arrForm.deliv_id]|h}--></td>
                </tr>
                <tr>
                    <th>お支払方法</th>
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
                    <th>その他お問い合わせ</th>
                    <td><!--{$arrForm.message|h|nl2br}--></td>
                </tr>
        <!--{if $quick_Flg == "1"}-->
        <!--{if $memo03 == PAY_PAYGENT_CONVENI_NUM}-->
        <tr>
            <td colspan="2" class="lefttd">ご選択いただきましたコンビニエンスストアでのお支払いが可能です（各支払い方法は下記一覧をご確認ください）。
            <br>なお、商品はお支払い後のご提供となります。</td>
        </tr>
        <!--{/if}-->
        <!--{if $memo03 == PAY_PAYGENT_ATM}-->
        <tr>
            <td colspan="2" class="lefttd">ゆうちょ銀行など「ペイジー」マークのあるATMで支払番号を入力してお支払いいただけます。
            <br>なお、商品はお支払い後のご提供となります。</td>
        </tr>
            <!--{/if}-->
        <!--{if $memo03 == PAY_PAYGENT_BANK}-->
        <tr>
            <td colspan="2" class="lefttd">三菱UFJ銀行などメガバンク、JNB、楽天銀行、ゆうちょ銀行など<br>全国1,400行以上のネットバンキングでお支払いが可能です。
            <br>なお、商品はお支払い後のご提供となります。</td>
        </tr>
            <!--{/if}-->
        <!--{if $memo03 == PAY_PAYGENT_EMONEY}-->
            <tr>
            <td colspan="2" class="lefttd">プリペイドカードおよびウォレットにチャージされている電子マネーからお支払いいただけます。
            <br>（各支払い方法は下記一覧をご確認ください）</td>
        </tr>
        <!--{/if}-->
        <!--{/if}-->
                </tbody>
            </table>
        <!--{if $memo03 != PAY_PAYGENT_CAREER}-->
        <!--{if $memo03 != PAY_PAYGENT_YAHOOWALLET}-->
        <!--{if $quick_Flg == "1"}-->
        <p>上記のお支払い内容でお支払いを行う場合は、「クイック決済」ボタンをクリックすると、注文を完了できます。<br>
        お支払い内容を変更する場合は、「次へ」ボタンをクリックし、決済情報入力画面で、入力をお願い致します。
        </p>
        <!--{/if}-->
        <!--{/if}-->
        <!--{/if}-->

            <div class="tblareabtn">
                <!--{if $use_module}--><p>
                 <input type="submit" onclick="fnCheckSubmit();return false" value="次へ" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="next" id="next" /></p>
        <!--{if $memo03 != PAY_PAYGENT_CAREER}-->
        <!--{if $memo03 != PAY_PAYGENT_YAHOOWALLET}-->
        <!--{if $quick_Flg == "1"}--><p>
            <!--{if $security_code_flg && $token_pay == 1}-->
            <input type="submit" onclick="startCreateToken();return false" value="クイック決済" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="クイック決済" id="quick" /></p>
            <!--{else}-->
            <input type="submit" onclick="return fnQuickCheckSubmit();" value="クイック決済" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="クイック決済" id="quick" /></p>
            <!--{/if}-->
        <!--{/if}-->
        <!--{/if}-->
        <!--{/if}-->
                <!--{else}-->
                 <input type="submit" value="ご注文完了ページへ" class="spbtn spbtn-shopping" width="130" height="30" alt="ご注文完了ページへ" name="next" id="next" />
                </p><!--{/if}-->
                <p><a href="./payment.php" class="spbtn spbtn-medeum">戻る</a></p>
            </div>

    <!--{if $quick_Flg == "1"}-->
    <!--{if $memo03 == PAY_PAYGENT_CONVENI_NUM}-->
    <h2>その他指定・お問い合わせ</h2>
    <table class="entryform">
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
        <th>ローソン、ミニストップ、デイリーヤマザキ</th>
            <td class="lefttd">ローソン、ミニストップは店内に設置されている「Loppi」で支払番号を入力してレジにて、それ以外のコンビニはレジ店頭でお支払いが可能です。</td>
          </tr>
          <tr>
        <th>セイコーマート</th>
            <td class="lefttd">セイコーマート店内に設置されている「クラブステーション」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。</td>
          </tr>
        </tbody>
    </table>
    <!--{/if}-->
    <!--{if $memo03 == PAY_PAYGENT_EMONEY}-->
        <h2>お支払方法説明</h2>
        <table class="entryform">
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
<!--▲CONTENTS-->
<!--{else}-->
<script>//<![CDATA[
    var send = false;

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

    //ご注文内容エリアの表示/非表示
    var speed = 1000; //表示アニメのスピード（ミリ秒）
    var stateCartconfirm = 0;
    function fnCartconfirmToggle(areaEl, imgEl) {
        areaEl.toggle(speed);
        if (stateCartconfirm == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateCartconfirm = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateCartconfirm = 0
        }
    }
    //お届け先エリアの表示/非表示
    var stateDelivconfirm = 0;
    function fnDelivconfirmToggle(areaEl, imgEl) {
        areaEl.toggle(speed);
        if (stateDelivconfirm == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateDelivconfirm = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateDelivconfirm = 0
        }
    }
    //配送方法エリアの表示/非表示
    var stateOtherconfirm = 0;
    function fnOtherconfirmToggle(areaEl, imgEl) {
        areaEl.toggle(speed);
        if (stateOtherconfirm == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateOtherconfirm = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateOtherconfirm = 0
        }
    }
//]]></script>

<!--▼コンテンツここから -->
<section id="undercolumn">

    <h2 class="title"><!--{$tpl_title|h}--></h2>

    <!--★インフォメーション★-->
    <div class="information end">
        <p>
            下記ご注文内容でよろしければ、「<!--{if $use_module}-->次へ<!--{else}-->ご注文完了ページへ<!--{/if}-->」ボタンをクリックしてください。
            <!--{if $quick_Flg == "1"}-->
                <br>前回と同じお支払い内容でお支払いを行う場合は、ページ下段の「クイック決済」ボタンをクリックすると、注文を完了できます。
            <!--{/if}-->
        </p>
    </div>

    <form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->shopping/confirm.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
        <input type="hidden" name="card_token" value="">

        <h3 class="subtitle">ご注文内容</h3>

        <section class="cartconfirm_area">
            <div class="form_area">
                <!--▼フォームボックスここから -->
                <div class="formBox">
                    <!--▼カートの中の商品一覧 -->
                    <div class="cartcartconfirmarea">
                        <!--{foreach from=$arrCartItems item=item}-->
                            <!--▼商品 -->
                            <div class="cartconfirmBox">
                                <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=80&amp;height=80" alt="<!--{$item.productsClass.name|h}-->" width="80" height="80" class="photoL" />
                                <div class="cartconfirmContents">
                                    <div>
                                        <p><em><!--{$item.productsClass.name|h}--></em><br />
                                        <!--{if $item.productsClass.classcategory_name1 != ""}-->
                                                <span class="mini"><!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--></span><br />
                                        <!--{/if}-->
                                        <!--{if $item.productsClass.classcategory_name2 != ""}-->
                                                <span class="mini"><!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}--></span>
                                        <!--{/if}-->
                                        </p>
                                    </div>
                                    <ul>
                                        <li><span class="mini">数量：</span><!--{$item.quantity|number_format}--></li>
                                        <li class="result"><span class="mini">小計：</span><!--{$item.total_inctax|number_format}-->円</li>
                                    </ul>
                                </div>
                            </div>
                            <!--▲商品 -->
                        <!--{/foreach}-->
                    </div>
                    <!--▲カートの中の商品一覧 -->

                    <!--★合計内訳★-->
                    <div class="result_area">
                        <ul>
                            <li><span class="mini">小計 ：</span><!--{$tpl_total_inctax[$cartKey]|number_format}--> 円</li>
                            <!--{if $smarty.const.USE_POINT !== false}-->
                                <li><span class="mini">値引き（ポイントご使用時）： </span><!--{assign var=discount value=`$arrForm.use_point*$smarty.const.POINT_VALUE`}-->
                                -<!--{$discount|number_format|default:0}--> 円</li>
                            <!--{/if}-->
                            <li><span class="mini">送料 ：</span><!--{$arrForm.deliv_fee|number_format}--> 円</li>
                            <li><span class="mini">手数料 ：</span><!--{$arrForm.charge|number_format}--> 円</li>
                        </ul>
                    </div>

                    <!--★合計★-->
                    <div class="total_area">
                        <span class="mini">合計：</span><span class="price fb"><!--{$arrForm.payment_total|number_format}--> 円</span>
                    </div>
                </div><!-- /.formBox -->

                <!--{* ログイン済みの会員のみ *}-->
                <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
                    <!--★ポイント情報★-->
                    <div class="formBox point_confifrm">
                        <dl>
                            <dt>ご注文前のポイント</dt><dd><!--{$tpl_user_point|number_format|default:0}-->Pt</dd>
                        </dl>
                        <dl>
                            <dt>ご使用ポイント</dt><dd>-<!--{$arrForm.use_point|number_format|default:0}-->Pt</dd>
                        </dl>
                        <!--{if $arrForm.birth_point > 0}-->
                        <dl>
                            <dt>お誕生月ポイント</dt><dd>+<!--{$arrForm.birth_point|number_format|default:0}-->Pt</dd>
                        </dl>
                        <!--{/if}-->
                        <dl>
                            <dt>今回加算予定のポイント</dt><dd>+<!--{$arrForm.add_point|number_format|default:0}-->Pt</dd>
                        </dl>
                        <dl>
                            <!--{assign var=total_point value=`$tpl_user_point-$arrForm.use_point+$arrForm.add_point`}-->
                            <dt>加算後のポイント</dt><dd><!--{$total_point|number_format}-->Pt</dd>
                        </dl>
                    </div><!-- /.formBox -->
                <!--{/if}-->
            </div><!-- /.form_area -->
        </section>

        <!--★お届け先の確認★-->
        <!--{* 販売方法判定（ダウンロード販売のみの場合はお届け先を表示しない） *}-->
        <!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
            <section class="delivconfirm_area">
                <h3 class="subtitle">お届け先</h3>

                <div class="form_area">

                    <!--{foreach item=shippingItem from=$arrShipping name=shippingItem}-->
                        <!--▼フォームボックスここから -->
                        <div class="formBox">
                            <dl class="deliv_confirm">
                                <dt>お届け先<!--{if $is_multiple}--><!--{$smarty.foreach.shippingItem.iteration}--><!--{/if}--></dt>
                                <dd>
                                    <p>〒<!--{$shippingItem.shipping_zip01|h}-->-<!--{$shippingItem.shipping_zip02|h}--><br />
                                        <!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01|h}--><!--{$shippingItem.shipping_addr02|h}--></p>
                                    <p class="deliv_name"><!--{$shippingItem.shipping_name01|h}--> <!--{$shippingItem.shipping_name02|h}--></p>
                                    <p><!--{$shippingItem.shipping_tel01}-->-<!--{$shippingItem.shipping_tel02}-->-<!--{$shippingItem.shipping_tel03}--></p>
                                    <!--{if $shippingItem.shipping_fax01 > 0}-->
                                        <p><!--{$shippingItem.shipping_fax01}-->-<!--{$shippingItem.shipping_fax02}-->-<!--{$shippingItem.shipping_fax03}--></p>
                                    <!--{/if}-->
                                </dd>
                                <!--{if $cartKey != $smarty.const.PRODUCT_TYPE_DOWNLOAD}-->
                                    <dd>
                                        <ul class="date_confirm">
                                            <li><em>お届け日：</em><!--{$shippingItem.shipping_date|default:"指定なし"|h}--></li>
                                            <li><em>お届け時間：</em><!--{$shippingItem.shipping_time|default:"指定なし"|h}--></li>
                                        </ul>
                                    </dd>
                                <!--{/if}-->
                            </dl>

                            <!--{if $is_multiple}-->
                                <!--▼カートの中の商品一覧 -->
                                <div class="cartcartconfirmarea">
                                    <!--{foreach item=item from=$shippingItem.shipment_item}-->
                                        <!--▼商品 -->
                                        <div class="cartconfirmBox">
                                            <!--{if $item.productsClass.main_image|strlen >= 1}-->
                                                <a href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$item.productsClass.main_image|sfNoImageMainList|h}-->" target="_blank">
                                                <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=80&amp;height=80" alt="<!--{$item.productsClass.name|h}-->" width="80" height="80" class="photoL" /></a>
                                            <!--{else}-->
                                                <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$item.productsClass.main_list_image|sfNoImageMainList|h}-->&amp;width=80&amp;height=80" alt="<!--{$item.productsClass.name|h}-->" width="80" height="80" class="photoL" />
                                            <!--{/if}-->
                                            <div class="cartconfirmContents">
                                                <p>
                                                    <em><!--{$item.productsClass.name|h}--></em><br />
                                                    <!--{if $item.productsClass.classcategory_name1 != ""}-->
                                                            <span class="mini"><!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--></span><br />
                                                    <!--{/if}-->
                                                    <!--{if $item.productsClass.classcategory_name2 != ""}-->
                                                            <span class="mini"><!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}--></span>
                                                    <!--{/if}-->
                                                </p>
                                                <ul>
                                                    <li><span class="mini">数量：</span><!--{$item.quantity}--></li>
                                                    <!--{* XXX デフォルトでは購入小計と誤差が出るためコメントアウト*}-->
                                                    <li class="result"><span class="mini">小計：</span><!--{$item.total_inctax|number_format}-->円</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!--▲商品 -->
                                    <!--{/foreach}-->
                                </div>
                                <!--▲カートの中の商品一覧ここまで -->
                            <!--{/if}-->
                        </div><!-- /.formBox -->
                    <!--{/foreach}-->
                </div><!-- /.form_area -->
            </section>
        <!--{/if}-->

        <!--★配送方法・お支払方法など★-->
        <section class="otherconfirm_area">
            <h3 class="subtitle">配送方法・お支払方法など</h3>

            <div class="form_area">
                <!--▼フォームボックスここから -->
                <div class="formBox">
                    <div class="innerBox">
                        <em>配送方法</em>：<!--{$arrDeliv[$arrForm.deliv_id]|h}-->
                    </div>
                    <div class="innerBox">
                        <em>お支払方法：</em><!--{$arrForm.payment_method|h}-->
                    </div>

            <!--{if $quick_Flg == "1"}-->
                <!--{if $memo03 == PAY_PAYGENT_CREDIT}-->
                    <div class="innerBox">
                        <em>支払回数：</em><!--{$paymentDivision|h}-->
                    </div>
                    <div class="innerBox">
                        <em>カード番号：</em><!--{$card_info.CardNo|h}-->
                    </div>
                  <!--{if $security_code_flg == 1}-->
                    <div class="innerBox">
                        <em>セキュリティコード：</em>
                        <!--{assign var=key1 value="security_code"}-->
                        <input type="text" name="<!--{$key1}-->" value="<!--{$security_code}-->" maxlength="4" style="ime-mode: disabled;" size="4" class="text data-role-none">
                        <br><span class="attention">セキュリティコードを入力してください</span>
                    </div>
                  <!--{/if}-->
                    <div class="innerBox">
                        <em>有効期限：</em>
                        <!--{$card_info.Expire|substr:0:2}-->月/<!--{$card_info.Expire|substr:2:4}-->年
                    </div>
                    <div class="innerBox">
                        <em>カード名義：</em><!--{$card_info.HolderName|h}-->
                    </div>
                <!--{/if}-->
                <!--{if $memo03 == PAY_PAYGENT_CONVENI_NUM}-->
                    <div class="innerBox">
                        <em>コンビニ選択：</em><!--{$convenience|h}-->
                    </div>
                    <div class="innerBox">
                        <em>利用者（姓名）：</em><!--{$quick_memo.customer_family_name|h}--> <!--{$quick_memo.customer_name|h}-->
                    </div>
                    <div class="innerBox">
                        <em>利用者（カナ）：</em><!--{$quick_memo.customer_family_name_kana|h}--> <!--{$quick_memo.customer_name_kana|h}-->
                    </div>
                    <div class="innerBox">
                        <em>お電話番号：</em><!--{$quick_memo.customer_tel|h}-->
                    </div>
                <!--{/if}-->
                <!--{if $memo03 == PAY_PAYGENT_ATM || $memo03 == PAY_PAYGENT_BANK}-->
                    <div class="innerBox">
                        <em>利用者（姓名）：</em><!--{$quick_memo.customer_family_name|h}--> <!--{$quick_memo.customer_name|h}-->
                    </div>
                    <div class="innerBox">
                        <em>利用者（カナ）：</em><!--{$quick_memo.customer_family_name_kana|h}--> <!--{$quick_memo.customer_name_kana|h}-->
                    </div>
                <!--{/if}-->
                <!--{if $memo03 == PAY_PAYGENT_EMONEY}-->
                    <div class="innerBox">
                        <em>利用決済選択：</em><!--{$emoney|h}-->
                    </div>
                <!--{/if}-->
            <!--{/if}-->

                    <div class="innerBox">
                        <em>その他お問い合わせ：</em><br />
                        <!--{$arrForm.message|h|nl2br}-->
                    </div>

            <!--{if $quick_Flg == "1"}-->
                <!--{if $memo03 == PAY_PAYGENT_CONVENI_NUM}-->
                    <div class="innerBox">
                        ご選択いただきましたコンビニエンスストアでのお支払いが可能です（各支払い方法は下記一覧をご確認ください）。
                        <br>なお、商品はお支払い後のご提供となります。
                    </div>
                <!--{/if}-->
                <!--{if $memo03 == PAY_PAYGENT_ATM}-->
                    <div class="innerBox">
                        ゆうちょ銀行など「ペイジー」マークのあるATMで支払番号を入力してお支払いいただけます。
                        <br>なお、商品はお支払い後のご提供となります。
                    </div>
                <!--{/if}-->
                <!--{if $memo03 == PAY_PAYGENT_BANK}-->
                    <div class="innerBox">
                        三菱UFJ銀行などメガバンク、JNB、楽天銀行、ゆうちょ銀行など<br>全国1,400行以上のネットバンキングでお支払いが可能です。
                        <br>なお、商品はお支払い後のご提供となります。
                    </div>
                <!--{/if}-->
                <!--{if $memo03 == PAY_PAYGENT_EMONEY}-->
                    <div class="innerBox">
                        プリペイドカードおよびウォレットにチャージされている電子マネーからお支払いいただけます。
                        <br>（各支払い方法は下記一覧をご確認ください）
                    </div>
                <!--{/if}-->
            <!--{/if}-->

                </div><!-- /.formBox -->
            </div><!-- /.form_area -->
        </section>

        <!--★ボタン★-->
        <div class="btn_area">

      <!--{if $quick_Flg == "1"}-->
        <!--{if $memo03 != PAY_PAYGENT_CAREER}-->
          <!--{if $memo03 != PAY_PAYGENT_YAHOOWALLET}-->
            <p>
              上記のお支払い内容でお支払いを行う場合は、「クイック決済」ボタンをクリックすると、注文を完了できます。<br>
              お支払い内容を変更する場合は、「次へ」ボタンをクリックし、決済情報入力画面で、入力をお願い致します。
            </p>
          <!--{/if}-->
        <!--{/if}-->
      <!--{/if}-->

            <ul class="btn_btm">
                <!--{if $use_module}-->
                    <li><a rel="external" href="javascript:void(fnCheckSubmit());" class="btn">次へ</a></li>

              <!--{if $quick_Flg == "1"}-->
                <!--{if $memo03 != PAY_PAYGENT_CAREER}-->
                  <!--{if $memo03 != PAY_PAYGENT_YAHOOWALLET}-->
                    <li>
                        <!--{if $security_code_flg && $token_pay == 1}-->
                        <button class="bt03" onclick="startCreateToken();return false" name="quick" id="quick">クイック決済</button>
                        <!--{else}-->
                        <button class="bt03" onclick="return fnQuickCheckSubmit();" name="quick" id="quick">クイック決済</button>
                        <!--{/if}-->
                    </li>
                  <!--{/if}-->
                <!--{/if}-->
              <!--{/if}-->

                <!--{else}-->
                    <li><a rel="external" href="javascript:void(document.form1.submit());" class="btn">ご注文完了ページへ</a></li>
                <!--{/if}-->
                <li><a rel="external" href="./payment.php" class="btn_back">戻る</a></li>
            </ul>
        </div>

    </form>
</section>

<!--{if $quick_Flg == "1"}-->
<!--{if $memo03 == PAY_PAYGENT_CONVENI_NUM}-->
<section>
    <h3 class="subtitle">お支払方法説明</h3>
    <div class="form_area">
        <div class="formBox">
            <div class="innerBox">
                <em>セブン-イレブン</em>：
                セブン-イレブンのレジ店頭にてお支払いが可能です。
            </div>
            <div class="innerBox">
                <em>ファミリーマート</em>：
                ファミリーマート店内に設置されている「Famiポート」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。
            </div>
            <div class="innerBox">
                <em>ローソン、ミニストップ</em>：
                ローソン、ミニストップ店内に設置されている「Loppi」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。
            </div>
            <div class="innerBox">
                <em>デイリーヤマザキ</em>：
                デイリーヤマザキのレジ店頭にてお支払いが可能です。
            </div>
            <div class="innerBox">
                <em>セイコーマート</em>：
                セイコーマート店内に設置されている「クラブステーション」で支払番号を入力し、発券される申込券でレジにてお支払が可能です。
            </div>
        </div>
    </div>
</section>
<!--{/if}-->
<!--{if $memo03 == PAY_PAYGENT_EMONEY}-->
<section>
    <h3 class="subtitle">お支払方法説明</h3>
    <div class="form_area">
        <div class="formBox">
            <div class="innerBox">
                <em>WebMoney</em>：
                プリペイドカードご利用の場合はカード記載の番号、ウォレットご利用の場合はウォレットID/パスワードに続いてセキュアパスワードを入力することによりお支払が可能です。
            </div>
        </div>
    </div>
</section>
<!--{/if}-->
<!--{/if}-->

<!--▼検索バー -->
<section id="search_area">
    <form method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="search" />
        <input type="search" name="name" id="search" value="" placeholder="キーワードを入力" class="searchbox" >
    </form>
</section>
<!--▲検索バー -->
<!--▲コンテンツここまで -->
<!--{/if}-->