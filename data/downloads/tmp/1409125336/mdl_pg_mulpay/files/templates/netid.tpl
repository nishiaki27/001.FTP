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
<form name="NetidStartCall" action="<!--{$netidStartUrl}-->" method="POST">

<noscript>
<br />
<br />
<center>
<h2>
iD決済開始画面へ遷移します。<br />
ボタンをクリックしてください。
</h2>
<input type="submit" value="続行">
</center>
</noscript>

<input type="hidden" name="AccessID" value="<!--{$netidAccessID|escape}-->">
<input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />

</form>

<script>
<!--
function OnLoadEvent() {
     document.NetidStartCall.submit();
}
//-->
</script>
