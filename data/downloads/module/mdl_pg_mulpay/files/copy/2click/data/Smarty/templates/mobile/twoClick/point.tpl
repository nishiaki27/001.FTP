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
<form method="post" action="?">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="confirm">
<input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">

<!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
1ポイントを<!--{$smarty.const.POINT_VALUE}-->円として使用する事ができます。<br>
<br>
<!--{$name01|h}--> <!--{$name02|h}-->様の、現在の所持ポイントは「<font color="#FF0000"><!--{$tpl_user_point|number_format|default:0}-->Pt</font>」です。<br>
<br>
今回ご購入合計金額：<font color="#FF0000"><!--{$arrPrices.subtotal|number_format}-->円</font><br>
(送料、手数料を含みません。)<br>
<br>
<input type="radio" name="point_check" value="1" <!--{$arrForm.point_check.value|sfGetChecked:1}-->>ポイントを使用する<br>
<!--{assign var=key value="use_point"}-->
<!--{if $arrErr[$key] != ""}-->
<font color="#FF0000"><!--{$arrErr[$key]}--></font>
<!--{/if}-->
<input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|default:$tpl_user_point}-->" maxlength="<!--{$arrForm[$key].length}-->" size="6">&nbsp;ポイントを使用する。<br>
<input type="radio" name="point_check" value="2" <!--{$arrForm.point_check.value|sfGetChecked:2}-->>ポイントを使用しない<br>
<br>
<!--{/if}-->

<center><input type="submit" value="決定する"></center>
</form>

<form action="./confirm.php" method="get">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<center><input type="submit" name="return" value="戻る"></center>
</form>
