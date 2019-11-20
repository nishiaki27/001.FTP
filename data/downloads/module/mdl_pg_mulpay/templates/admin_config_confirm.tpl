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
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->

<script type="text/javascript">
//<![CDATA[
function fnModeSubmit(mode, keyname, keyid) {
    switch(mode) {
    case 'register':
        var msg = document.form1['not_install_customize'].checked
            ? '上書きファイルの手動での反映をお願いします。'
            : 'EC-CUBE本体のファイルを上書きしますが、よろしいですか？';
        if(!window.confirm(msg)){
            return;
        }
        break;
    }
    document.form1['mode'].value = mode;
    if(keyname != "" && keyid != "") {
        document.form1[keyname].value = keyid;
    }
    document.form1.submit();
}
//]]>
</script>

<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid|escape}-->" />
  <input type="hidden" name="mode" value="register">

  <table class="form">
    <colgroup width="20%"></colgroup>
    <colgroup width="40%"></colgroup>

    <tr>
      <th colspan="2">▼変更を手動で反映</th>
    </tr>
    <tr>
      <td colspan="2">
	<!--{assign var=key value="not_install_customize"}-->
	<span class="attention"><!--{$arrErr[$key]}--></span>
	<input type="checkbox"
	       name="<!--{$key}-->"
	       style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
	       value="1"
	       <!--{if $arrForm[$key].value}--> checked <!--{/if}-->> 自動で上書きせずに、手動で変更を反映したい場合はチェックを入れてください<br><br><br>
	  <ul>
	    <li>※EC-CUBE本体のファイルを上書きされたくない場合にチェックを入れて下さい。</li>
	    <li>※自動で上書きしない場合、モジュールによるカスタマイズ部分を手動で反映する必要があります。</li>
	    <li>上書き・新規追加するファイル一覧
	      <ul>
	        <!--{foreach from=$customizeFiles item=file}-->
		<li><!--{$file.dst}--></li>
	        <!--{/foreach}-->
	      </ul>
	    </li>
	  </ul>
      </td>
    </tr>
  </table>

  <div class="btn-area">
    <ul>
      <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('form1', 'return', '', ''); return false;"><span class="btn-prev">入力画面に戻る</span></a></li>
      <li><a class="btn-action" href="javascript:;" onclick="fnModeSubmit('register', '', ''); return false;"><span class="btn-next">設定を保存する</span></a></li>
    </ul>
  </div>

  <!--{foreach from=$arrForm key=key item=item}-->
    <!--{if $key ne "mode" && $key ne "subm" && $key ne $smarty.const.TRANSACTION_ID_NAME && $key ne "conveni" && $key ne "method_paytimes" && $key ne "not_install_customize"}-->
      <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" /> 
    <!--{/if}-->
  <!--{/foreach}-->

  <!--{foreach from=$arrFormConveni key=key item=item}-->
     <input type="hidden" name="conveni[]" value="<!--{$item|h}-->" /> 
  <!--{/foreach}-->

  <!--{foreach from=$arrFormMethodPaytimes key=key item=item}-->
     <input type="hidden" name="method_paytimes[]" value="<!--{$item|h}-->" /> 
  <!--{/foreach}-->

</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
