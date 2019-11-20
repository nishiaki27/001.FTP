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
<script type="text/javascript">//<![CDATA[
var send = true;

function fnCheckSubmit(mode) {
    if(send) {
        send = false;
        fnModeSubmit(mode,'','');
        return false;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
}

function next(now, next) {
	if (now.value.length >= now.getAttribute('maxlength')) {
	next.focus();
	}
}
//]]>
</script>

<!--▼CONTENTS-->
<div id="under02column">
<div id="under02column_shopping">
  <p class="flow_area"><img src="<!--{$TPL_URLPATH}-->img/picture/img_flow_03.jpg" alt="購入手続きの流れ" /></p>
  <h2 class="title1"><!--{$tpl_payment_method}--></h2>

  <form name="form1" id="form1" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <input type="hidden" name="mode" value="next">
  <table summary="お支払詳細入力" class="delivname">
    <tbody>
      <!--{if $tpl_error != ""}-->
      <tr>
        <td class="lefttd" colspan="2">
          <span class="attention"><!--{$tpl_error}--></span>
        </td>
      </tr>
      <!--{/if}-->
            <!--{if $tpl_payment_image != ""}-->
      <tr>
        <th>ご利用いただけるコンビニの種類</th>
        <td class="lefttd">
          <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->">
        </td>
      </tr>
      <!--{/if}-->
      <tr>
        <th>利用者</th>
        <td class="lefttd">
          <!--{assign var=key1 value="customer_family_name"}-->
          <!--{assign var=key2 value="customer_name"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
                    姓&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;
                    名&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
        </td>
      </tr>
      <tr>
        <th>利用者(カナ)</th>
        <td class="lefttd">
          <!--{assign var=key1 value="customer_family_name_kana"}-->
          <!--{assign var=key2 value="customer_name_kana"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <span class="attention"><!--{$arrErr[$key2]}--></span>
                    セイ&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="bo20">&nbsp;&nbsp;
                    メイ&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="bo20">
          <br /><p class="mini">※カナに濁点（゛）や半濁点（゜）がある場合、該当記号のみ除外されます。予めご了承ください。</p>
        </td>
      </tr>

      <tr>
        <th>郵便番号</th>
        <td>
          <!--{assign var=key1 value="customer_zip01"}-->
          <!--{assign var=key2 value="customer_zip02"}-->
          <span class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></span>
          <p>〒&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="6" class="box60" />&nbsp;-&nbsp;  <input type="text"  name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"  size="6" class="box60" />
            <a href="http://search.post.japanpost.jp/zipcode/" target="_blank"><span class="fs10">郵便番号検索</span></a></p>

          <p class="zipimg"><a href="<!--{$smarty.const.ROOT_URLPATH}-->address/index.php" onclick="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', 'customer_zip01', 'customer_zip02', 'customer_pref', 'customer_addr01'); return false;" target="_blank"><img src="<!--{$TPL_URLPATH}-->img/button/btn_address_input.jpg" alt="住所自動入力" /></a>
             <span class="mini">&nbsp;郵便番号を入力後、クリックしてください。</span></p>
        </td>
        </tr>
          <tr>
            <th>住所</th>
            <td>
              <!--{assign var=key value="customer_pref"}-->
              <span class="attention"><!--{$arrErr.customer_pref}--><!--{$arrErr.customer_addr01}--><!--{$arrErr.customer_addr02}--></span>
              <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <option value="">都道府県を選択</option>
                <!--{html_options options=$arrPref selected=$arrForm[$key].value}-->
              </select>
              <p class="mini">
                <!--{assign var=key value="customer_addr01"}-->
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" size="40" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="box380" /><br />
                <!--{$smarty.const.SAMPLE_ADDRESS1}--></p>
              <p class="mini">
                <!--{assign var=key value="customer_addr02"}-->
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" size="40"  maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="box380" /><br />
                <!--{$smarty.const.SAMPLE_ADDRESS2}--></p>
              <p class="mini"><em>住所は2つに分けてご記入いただけます。マンション名は必ず記入してください。</em></p></td>
          </tr>
          <tr>
            <th>お電話番号</th>
            <td class="lefttd">
            <!--{assign var=key1 value="customer_tel_division"}-->
                <select name="<!--{$key1}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                  <!--{html_options options=$arrTelDivision selected=$arrForm[$key].value}-->
                </select><br>
              <!--{assign var=key1 value="customer_tel01"}-->
              <!--{assign var=key2 value="customer_tel02"}-->
              <!--{assign var=key3 value="customer_tel03"}-->
              <span class="attention"><!--{$arrErr[$key1]}--></span>
              <span class="attention"><!--{$arrErr[$key2]}--></span>
              <span class="attention"><!--{$arrErr[$key3]}--></span>
              <input type="text" name="<!--{$arrForm[$key1].keyname}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box60" /> -
              <input type="text" name="<!--{$arrForm[$key2].keyname}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"  size="6" class="box60" /> -
              <input type="text" name="<!--{$arrForm[$key3].keyname}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" size="6" class="box60" />
            </td>
          </tr>
    </tbody>
  </table>

  <table>
    <tr>
      <td class="lefttd">
                以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
      </td>
    </tr>
  </table>

  <div class="btn_area">
    <ul>
      <li>
        <input type="image" onclick="return fnCheckSubmit('return');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_keep_shopping.png',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_keep_shopping.png" alt="戻る" border="0" name="back03" id="back03"/>
      </li>
      <li>
        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" border="0" name="next" id="next" />
      </li>
    </ul>
  </div>
  </form>

</div>
</div>
<!--▲CONTENTS-->