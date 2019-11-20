<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
 */
*}-->

<tr>
    <th>会社名</th>
    <td>
        <!--{assign var=key1 value="`$prefix`name01"}-->
        <!--{if $arrErr[$key1]}-->
            <div class="txt_red2"><!--{$arrErr[$key1]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: active;" class="box_w380 mb5" />&nbsp;
	<div class="txt_m">(全角入力) ※文字化けの原因となりますので、(株) (有)など略さずにご記入下さい。</div>
    </td>
</tr>
<tr>
    <th>会社名(フリガナ)</th>
    <td>
        <!--{assign var=key1 value="`$prefix`kana01"}-->
        <!--{if $arrErr[$key1]}-->
            <div class="txt_red2"><!--{$arrErr[$key1]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: active;" class="box_w380 mb5" />&nbsp;
	<div class="txt_m">(全角カタカナ) 例: カブシキガイシャクウチョウセンター</div>
    </td>
</tr>
<tr>
    <th>お名前<span class="attention2">必須</span></th>
    <td>
        <!--{assign var=key2 value="`$prefix`name02"}-->
        <!--{if $arrErr[$key2]}-->
            <div class="txt_red2"><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: active;" class="box_w200" />
	<div class="txt_m">例: 東京太郎</div>
    </td>
</tr>
<tr>
    <th>お名前(フリガナ)<span class="attention2">必須</span></th>
    <td>
        <!--{assign var=key2 value="`$prefix`kana02"}-->
        <!--{if $arrErr[$key2]}-->
            <div class="txt_red2"><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: active;" class="box_w200" />
	<div class="txt_m">(全角カタカナ) 例: トウキョウタロウ</div>
    </td>
</tr>
<tr>
    <th>郵便番号<span class="attention2">必須</span></th>
    <td>
        <!--{assign var=key1 value="`$prefix`zip01"}-->
        <!--{assign var=key2 value="`$prefix`zip02"}-->
        <!--{assign var=key3 value="`$prefix`pref"}-->
        <!--{assign var=key4 value="`$prefix`addr01"}-->
        <!--{assign var=key5 value="`$prefix`addr02"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
        <div class="txt_red2"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        <p style="padding-bottom:0;" class="top">〒&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.ZIP01_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w60" />&nbsp;-&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.ZIP02_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w60"  onchange="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', '<!--{$key1}-->', '<!--{$key2}-->', '<!--{$key3}-->', '<!--{$key4}-->');"/><span style="width:90px; font-size:12px; margin-left:10px;" class="txt_m bg_pink"><a target="_blank" href="http://www.post.japanpost.jp/zipcode/index.html">郵便番号検索</a></span></p>
     
	<div class="txt_m" style="margin-top:5px;">(半角) 例: 141-0031</div>
       <div class="txt_m txt_red" style="margin-top:10px;">郵便番号を入力後、都道府県、市区町村名が自動で入力されます</div>
       
    </td>
</tr>
<tr>
    <th>住所<span class="attention2">必須</span></th>
    <td>
        <!--{if $arrErr[$key3] || $arrErr[$key4] || $arrErr[$key5]}-->
            <div class="txt_red2"><!--{$arrErr[$key3]}--><!--{$arrErr[$key4]}--><!--{$arrErr[$key5]}--></div>
        <!--{/if}-->
        <select name="<!--{$key3}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->">
                <option value="" selected="selected">都道府県を選択</option>
                <!--{html_options options=$arrPref selected=$arrForm[$key3]}-->
        </select>
        <input type="text" name="<!--{$key4}-->" value="<!--{$arrForm[$key4]|h}-->" class="box_w380 mb5" style="<!--{$arrErr[$key4]|sfGetErrorColor}-->; ime-mode: active;" />
           <div style="margin-bottom:20px;" class="txt_m"><!--{$smarty.const.SAMPLE_ADDRESS1}--></div>
        <input type="text" name="<!--{$key5}-->" value="<!--{$arrForm[$key5]|h}-->" class="box_w380 mb5" style="<!--{$arrErr[$key5]|sfGetErrorColor}-->; ime-mode: active;" />
            <div class="txt_m"><!--{$smarty.const.SAMPLE_ADDRESS2}--></div>
        <p class="txt_m"><span class="txt_red">住所は2つに分けてご記入ください。<br />
マンション名は必ず記入してください。</span></p>
    </td>
</tr>
<tr>
    <th>電話番号<span class="attention2">必須</span></th>
    <td>
        <!--{assign var=key1 value="`$prefix`tel01"}-->
        <!--{assign var=key2 value="`$prefix`tel02"}-->
        <!--{assign var=key3 value="`$prefix`tel03"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
            <div class="txt_red2"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w60 mb5" />&nbsp;-&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w60 mb5" />&nbsp;-&nbsp;<input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w60 mb5" />
	<div class="txt_m">(半角) 例: 03-3710-2511</div>
	<div class="txt_m txt_red">携帯電話など、常時つながるものをご記入ください。</div>
    </td>
</tr>
<!--{if $flgFields > 1}-->
    <tr>
        <th>FAX</th>
        <td>
            <!--{assign var=key1 value="`$prefix`fax01"}-->
            <!--{assign var=key2 value="`$prefix`fax02"}-->
            <!--{assign var=key3 value="`$prefix`fax03"}-->
            <!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
                <div class="txt_red2"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
            <!--{/if}-->
            <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w60 mb5" />&nbsp;-&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w60 mb5" />&nbsp;-&nbsp;<input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w60 mb5" />
	    <div class="txt_m">(半角) 例: 03-3710-2512</div>
        </td>
    </tr>
    <tr>
        <th>メールアドレス<span class="attention2">必須</span></th>
        <td>
            <!--{assign var=key1 value="`$prefix`email"}-->
            <!--{assign var=key2 value="`$prefix`email02"}-->
            <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                <div class="txt_red2"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
            <!--{/if}-->
            <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode:disabled;" class="box_w380 mb5" /><br />
            <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" style="<!--{$arrErr[$key1]|cat:$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box_w380 mb5" /><br />
	    <div class="txt_m">(半角) 例: info@tokyo-aircon.net ※ 携帯アドレス不可</div>
            <span class="txt_red txt_m">確認のため2度入力してください。</span>
        </td>
    </tr>
    <!--{if $emailMobile}-->
        <tr>
            <th>携帯メールアドレス</th>
            <td>
                <!--{assign var=key1 value="`$prefix`email_mobile"}-->
                <!--{assign var=key2 value="`$prefix`email_mobile02"}-->
                <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                <div class="txt_red2"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
                <!--{/if}-->
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" class="box_w380 mb5" /><br />
                <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" style="<!--{$arrErr[$key1]|cat:$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" class="box_w380 mb5" /><br />
                <span class="txt_red txt_m">確認のため2度入力してください。</span>
            </td>
        </tr>
    <!--{/if}-->
<!--{if $flgFields > 2000}-->
    <tr>
        <th>性別<span class="attention2">必須</span></th>
        <td>
            <!--{assign var=key1 value="`$prefix`sex"}-->
            <!--{if $arrErr[$key1]}-->
                <div class="txt_red"><!--{$arrErr[$key1]}--></div>
            <!--{/if}-->
            <span style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                <input type="radio" id="man" name="<!--{$key1}-->" value="1" <!--{if $arrForm[$key1] eq 1}--> checked="checked" <!--{/if}--> /><label for="man">男性</label>
                <input type="radio" id="woman" name="<!--{$key1}-->" value="2" <!--{if $arrForm[$key1] eq 2}--> checked="checked" <!--{/if}--> /><label for="woman">女性</label>
            </span>
        </td>
    </tr>
    <tr>
        <th>職業</th>
        <td>
            <!--{assign var=key1 value="`$prefix`job"}-->
            <!--{if $arrErr[$key1]}-->
                <div class="txt_red2"><!--{$arrErr[$key1]}--></div>
            <!--{/if}-->
            <select name="<!--{$key1}-->">
                <option value="" selected="selected">選択してください</option>
                <!--{html_options options=$arrJob selected=$arrForm[$key1]}-->
            </select>
        </td>
    </tr>
    <tr>
        <th>生年月日</th>
        <td>
            <!--{assign var=errBirth value="`$arrErr.year``$arrErr.month``$arrErr.day`"}-->
            <!--{if $errBirth}-->
                <div class="txt_red2"><!--{$errBirth}--></div>
            <!--{/if}-->
            <select name="year" style="<!--{$errBirth|sfGetErrorColor}-->">
                <!--{html_options options=$arrYear selected=$arrForm.year|default:''}-->
            </select>年
            <select name="month" style="<!--{$errBirth|sfGetErrorColor}-->">
                <!--{html_options options=$arrMonth selected=$arrForm.month|default:''}-->
            </select>月
            <select name="day" style="<!--{$errBirth|sfGetErrorColor}-->">
                <!--{html_options options=$arrDay selected=$arrForm.day|default:''}-->
            </select>日
        </td>
    </tr>
<!--{/if}-->
    <!--{if $flgFields > 2}-->
        <tr>
            <th>希望するパスワード<span class="attention2">必須</span></th>
            <td>
                <!--{if $arrErr.password || $arrErr.password02}-->
                    <div class="txt_red2"><!--{$arrErr.password}--><!--{$arrErr.password02}--></div>
                <!--{/if}-->
                <input type="password" name="password" value="<!--{$arrForm.password|h}-->" maxlength="<!--{$smarty.const.PASSWORD_MAX_LEN}-->" style="<!--{$arrErr.password|sfGetErrorColor}-->" class="box_w200 mb5" />
                <p><span class="txt_red txt_m">半角英数字<!--{$smarty.const.PASSWORD_MIN_LEN}-->～<!--{$smarty.const.PASSWORD_MAX_LEN}-->文字でお願いします。（記号不可）</span></p>
                <input type="password" name="password02" value="<!--{$arrForm.password02|h}-->" maxlength="<!--{$smarty.const.PASSWORD_MAX_LEN}-->" style="<!--{$arrErr.password|cat:$arrErr.password02|sfGetErrorColor}-->" class="box_w200 mb5" />
                <p><span class="txt_red txt_m">確認のために2度入力してください。</span></p>
            </td>
        </tr>
        <tr>
            <th>パスワードを<br />
忘れた時のヒント<span class="attention2">必須</span></th>
            <td>
                <!--{if $arrErr.reminder || $arrErr.reminder_answer}-->
                    <div class="txt_red2"><!--{$arrErr.reminder}--><!--{$arrErr.reminder_answer}--></div>
                <!--{/if}-->
                質問：
                <select name="reminder" style="<!--{$arrErr.reminder|sfGetErrorColor}-->">
                    <option value="" selected="selected">選択してください</option>
                    <!--{html_options options=$arrReminder selected=$arrForm.reminder}-->
                </select>
                <br />
                答え：<input type="text" name="reminder_answer" value="<!--{$arrForm.reminder_answer|h}-->" style="<!--{$arrErr.reminder_answer|sfGetErrorColor}-->; ime-mode: active;" class="box_w380" />
            </td>
        </tr>
        <tr>
            <th>弊社からの情報を<br />
受け取る<span class="attention2">必須</span></th>
            <td>
                <!--{if $arrErr.mailmaga_flg}-->
                    <div class="txt_red2"><!--{$arrErr.mailmaga_flg}--></div>
                <!--{/if}-->
                <span style="<!--{$arrErr.mailmaga_flg|sfGetErrorColor}-->">
                    <input type="radio" name="mailmaga_flg" value="1" id="html" <!--{if $arrForm.mailmaga_flg <> 2}--> checked="checked" <!--{/if}--> class="radio"/><label for="html" class="radio" style="border-bottom:none;">受け取る</label><br />
                    <input type="radio" name="mailmaga_flg" value="2" id="text" <!--{if $arrForm.mailmaga_flg eq 2}--> checked="checked" <!--{/if}--> class="radio"/><label for="text" class="radio" style="border-bottom:none;">受け取らない</label><br />
                    
                </span>
            </td>
        </tr>
    <!--{/if}-->
<!--{/if}-->
