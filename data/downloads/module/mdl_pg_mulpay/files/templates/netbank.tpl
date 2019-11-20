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
<script type="text/javascript">//<![CDATA[
function OnLoadEvent() {
document.SelectPageCall.submit();
}
//]]>
</script>
<form name="SelectPageCall" action="<!--{$smarty.const.MDL_PG_MULPAY_NETBUNK_PC_LINK_URL}-->" method="POST">
<noscript>
<br>
<br>
<center>
<h2>
金融機関選択画面に遷移します。<br>
ボタンをクリックしてください。
</h2>

<input type="submit" value="金融機関選択画面へ">
</center>
</noscript>
<!--{assign var=key value=encryptReceiptNo}-->
<input type="hidden" name="code" value="<!--{$arrForm[$key].value|escape}-->">
<input type="hidden" name="rkbn" value="1">
</form>
<Script Language="JavaScript">
<!--
OnLoadEvent();
//-->
</script>