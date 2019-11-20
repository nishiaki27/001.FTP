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
<div id="undercolumn">
    <div id="undercolumn_shopping">
        <h2 class="title"><!--{$tpl_title|h}--></h2>

        <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />

        <!-- ▼ポイント使用 ここから -->
        <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
            <div class="point_area">
                    <p><span class="attention">1ポイントを1円</span>として使用する事ができます。<br />
                        使用する場合は、「ポイントを使用する」にチェックを入れた後、使用するポイントをご記入ください。
                    </p>
                    <div class="point_announce">
                        <p><span class="user_name"><!--{$name01|h}--> <!--{$name02|h}-->様</span>の、現在の所持ポイントは「<span class="point"><!--{$tpl_user_point|default:0|number_format}-->Pt</span>」です。<br />
                            今回ご購入合計金額：<span class="price"><!--{$arrPrices.subtotal|number_format}-->円</span> <span class="attention">(送料、手数料を含みません。)</span>
                        </p>
                        <ul>
                            <li>
                            <input type="radio" id="point_on" name="point_check" value="1" <!--{$arrForm.point_check.value|sfGetChecked:1}--> onclick="fnCheckInputPoint();" /><label for="point_on">ポイントを使用する</label>
                                <!--{assign var=key value="use_point"}--><br />
                            今回のお買い物で、<input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|default:$tpl_user_point}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="box60" />&nbsp;Ptを使用する。<span class="attention"><!--{$arrErr[$key]}--></span>
                            </li>
                            <li><input type="radio" id="point_off" name="point_check" value="2" <!--{$arrForm.point_check.value|sfGetChecked:2}--> onclick="fnCheckInputPoint();" /><label for="point_off">ポイントを使用しない</label></li>
                        </ul>
                </div>
            </div>
        <!--{/if}-->
        <!-- ▲ポイント使用 ここまで -->


        <div class="btn_area">
            <ul>
                <li>
                <a href="./confirm.php" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg','back03')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg','back03')">
                    <img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back03" id="back03" /></a>
                </li>
                <li>
                    <input type="image" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_determine_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_determine.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_determine.jpg" alt="次へ" name="next" id="next" />
                </li>
            </ul>
        </div>
        </form>
    </div>
</div>
<!--▲CONTENTS-->
