<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">//<![CDATA[
self.moveTo(20,20);
self.resizeTo(620, 450);
self.focus();
//]]>
</script>
<style type="text/css">
.mini {
    font-size: 80%;
}
</style>
<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">

<!--{if $arrErr.err != ""}-->
    <div class="attention"><!--{$arrErr.err}--></div>
<!--{/if}-->

<table class="form">
  <colgroup width="20%"></colgroup>
  <colgroup width="40%"></colgroup>
  <tr>
    <th>動作<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value="work_flg"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <!--{html_radios name="$key" options=$arrMdlWorkFlg selected=$arrForm[$key].value|default:0}-->
    </td>
  </tr>
  <tr>
    <th>トラッキングパラメータ<span class="attention">※</span></th>
    <td>
      <!--{assign var=key value="tr_flg"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <!--{html_radios name="$key" options=$arrTrFlg selected=$arrForm[$key].value|default:0}-->
      <p class="mini">フィードから出力されるURLに「source=googleps」を付与します</p>
    </td>
  </tr>
  <tr>
    <th>接頭辞</th>
    <td>
      <!--{assign var=key value="gs_prefix"}-->
      <span class="attention"><!--{$arrErr[$key]}--></span>
      <input type="text" name="<!--{$key}-->" size="30" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" class="box30" maxlength="<!--{$arrForm[$key].length}-->" />
      <p class="mini">フィードから出力される商品IDの頭につける文字を指定します。指定しなくても問題ありません。</p>
    </td>
  </tr>
  <tr>
    <th>フィード URL</th>
    <td class="mini"><!--{$smarty.const.GSHOPPING_FEED_URL}--></td>
  </tr>
</table>

<div class="btn-area">
  <ul>
    <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">登録</span></a></li>
  </ul>
</div>
</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->