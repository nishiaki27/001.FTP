<!--{*
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
 *}-->
<!--▼CONTENTS-->
<div id="under02column">
    <div id="under02column_shopping">
        <h2 class="title"><!--{$tpl_title|h}--></h2><br />

        <form name="form1" id="form1" method="post" action="?">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="confirm" />
            <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />

            <!-- ▼ポイント使用 ここから -->
            <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
                <div class="pointarea">

                        <p><span class="attention">1ポイントを1円</span>として使用する事ができます。<br />
                            使用する場合は、「ポイントを使用する」にチェックを入れた後、使用するポイントをご記入ください。</p>
                    <div>
                        <p><!--{$name01|h}--> <!--{$name02|h}-->様の、現在の所持ポイントは「<em><!--{$tpl_user_point|default:0|number_format}-->Pt</em>」です。</p>
                        <p>今回ご購入合計金額：<span class="paymentprice"><!--{$arrPrices.subtotal|number_format}-->円</span> <span class="attention">(送料、手数料を含みません。)</span></p>
                        <ul id="paymentP">
                            <li><input class="radio_btn" type="radio" id="point_on" name="point_check" value="1" <!--{$arrForm.point_check.value|sfGetChecked:1}--> onclick="fnCheckInputPoint();" /><label for="point_on">ポイントを使用する</label></li>
                             <!--{assign var=key value="use_point"}-->

                             <li class="underline">今回のお買い物で、<input type="number" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|default:$tpl_user_point|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="6" class="box100 pointP" />&nbsp;ポイントを使用する。<span class="attention"><!--{$arrErr[$key]}--></span></li>
                             <li><input class="radio_btn" type="radio" id="point_off" name="point_check" value="2" <!--{$arrForm.point_check.value|sfGetChecked:2}--> onclick="fnCheckInputPoint();" /><label for="point_off">ポイントを使用しない</label></li>
                        </ul>
                    </div>
                </div>
            <!--{/if}-->
            <!-- ▲ポイント使用 ここまで -->

            <div class="tblareabtn">
                <p><input type="submit" value="決定する" class="spbtn spbtn-shopping" width="130" height="30" alt="決定する" name="next" id="next" /></p>
                <p><a href="./confirm.php" class="spbtn spbtn-medeum">戻る</a></p>
            </div>
        </form>
    </div>
</div>
<!--▲CONTENTS-->
