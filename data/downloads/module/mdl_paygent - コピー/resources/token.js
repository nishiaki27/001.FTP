/*
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
 */

function callCreateToken() {

    var form = document.form1;

    //セキュリティーコードの必須チェック
    if (form.security_code != null && form.security_code.value == '') {
        alert("セキュリティーコードが入力されていません。");
        send = false;
        return;
    }
    //カード名義の必須チェック
    if (form.card_name02.value == '' || form.card_name01.value == '') {
        alert("カード名義が入力されていません。");
        send = false;
        return;
    }

    var paygentToken = new PaygentToken();

    if (paygent_token_connect_url) {
    	paygentToken.URL = paygent_token_connect_url;
    }

    //カード登録有無により第四引数のコールバックメソッドを切り替える
    if (form.stock_new != null && form.stock_new.checked == true) {
        paygentToken.createToken(
            merchant_id,
            token_key,
            {
                card_number:form.card_no01.value +  form.card_no02.value +  form.card_no03.value +  form.card_no04.value,
                expire_year:form.card_year.value,
                expire_month: form.card_month.value,
                cvc:(form.security_code != null) ? form.security_code.value : '',
                name:form.card_name01.value + ' ' + form.card_name02.value
            },callCreateTokenStock
        );
    } else {
        paygentToken.createToken(
            merchant_id,
            token_key,
            {
                card_number:form.card_no01.value +  form.card_no02.value +  form.card_no03.value +  form.card_no04.value,
                expire_year:form.card_year.value,
                expire_month: form.card_month.value,
                cvc:(form.security_code != null) ? form.security_code.value : '',
                name:form.card_name01.value + ' ' + form.card_name02.value
            },execSubmit
        );
    }
}

function callCreateTokenStock(response) {

    if (response.result == '0000') {

        var form = document.form1;

        //取得したトークンをhiddenのinput要素にセット
        form.card_token_stock.value = response.tokenizedCardObject.token;

        var paygentToken = new PaygentToken();

        if (paygent_token_connect_url) {
        	paygentToken.URL = paygent_token_connect_url;
        }

        paygentToken.createToken(
            merchant_id,
            token_key,
            {
                card_number:form.card_no01.value +  form.card_no02.value +  form.card_no03.value +  form.card_no04.value,
                expire_year:form.card_year.value,
                expire_month: form.card_month.value,
                cvc:(form.security_code != null) ? form.security_code.value : '',
                name:form.card_name01.value + ' ' + form.card_name02.value
            },execSubmit
        );
    } else {
        showErrorMessage(response.result);
        send = false;
    }
}

function execSubmit(response) {

    if (response.result == '0000') {

        var form = document.form1;

        //入力値をクリアする(これをしないとカード情報が加盟店に渡ってしまってトークン決済の意味を成さない)
        form.card_no01.removeAttribute('name');
        form.card_no02.removeAttribute('name');
        form.card_no03.removeAttribute('name');
        form.card_no04.removeAttribute('name');
        form.card_month.removeAttribute('name');
        form.card_year.removeAttribute('name');
        form.card_name02.removeAttribute('name');
        form.card_name01.removeAttribute('name');

        if (form.security_code != null) {
            form.security_code.removeAttribute('name');
        }

        //取得したトークンをhiddenのinput要素にセット
        form.card_token.value = response.tokenizedCardObject.token;

        form.submit();

    } else {
        showErrorMessage(response.result);
        send = false;
    }
}

function showErrorMessage(result_code) {

    var error_message = "";

    switch (result_code){
      case "1300":
        error_message = 'カード番号が入力されていません。';
        break;
      case "1301":
        error_message = 'カード番号の書式が不正です。';
        break;
      case "1400":
        error_message = '有効期限(年)が入力されていません。';
        break;
      case "1401":
        error_message = '有効期限(年)の書式が不正です。';
        break;
      case "1500":
        error_message = '有効期限(月)が入力されていません。';
        break;
      case "1501":
        error_message = '有効期限(月)の書式が不正です。';
        break;
      case "1502":
        error_message = '有効期限(年月)が不正です。';
        break;
      case "1600":
        error_message = 'セキュリティコードの書式が不正です。';
        break;
      case "1700":
        error_message = 'カード名義の書式が不正です。';
        break;
      case "7000":
        error_message = '非対応のブラウザです。';
        break;
      case "8000":
        error_message = 'システムメンテナンス中です。';
        break;
      default:
        error_message = 'システムエラー (' + result_code + ')';
        break;
    }

    alert(error_message);
}


function callCreateTokenCvc() {

    var form = document.form1;

    //セキュリティーコードの必須チェック
    if (form.security_code != null && form.security_code.value == '') {
        alert("セキュリティーコードが入力されていません。");
        send = false;
        return;
    }

    //登録カード選択の必須チェック
    if (form.CardSeq != null) {

	    var is_checked = false;
	    var elmCardSeq = document.getElementsByName('CardSeq');

	    for(var i = 0; i < elmCardSeq.length; i++){
	        if(elmCardSeq[i].checked){
	        	is_checked = true;
	        	break;
	        }
	    }

	    if(!is_checked){
	    	alert("登録カードが選択されていません。");
	    	send = false;
	    	return;
	    }
    }

    var paygentToken = new PaygentToken();

    if (paygent_token_connect_url) {
    	paygentToken.URL = paygent_token_connect_url;
    }

    paygentToken.createCvcToken(
        merchant_id,
        token_key,{
            cvc:form.security_code.value
        },execSubmitCvc
    );
}

function execSubmitCvc(response) {

    if (response.result == '0000') {

        var form = document.form1;

        //入力値をクリアする(これをしないとカード情報が加盟店に渡ってしまってトークン決済の意味を成さない)

        if (form.card_no01 != null) {
            form.card_no01.removeAttribute('name');
        }
        if (form.card_no02 != null) {
            form.card_no02.removeAttribute('name');
        }
        if (form.card_no03 != null) {
            form.card_no03.removeAttribute('name');
        }
        if (form.card_no04 != null) {
            form.card_no04.removeAttribute('name');
        }
        if (form.card_month != null) {
            form.card_month.removeAttribute('name');
        }
        if (form.card_year != null) {
            form.card_year.removeAttribute('name');
        }
        if (form.card_name02 != null) {
            form.card_name02.removeAttribute('name');
        }
        if (form.card_name01 != null) {
            form.card_name01.removeAttribute('name');
        }

        form.security_code.removeAttribute('name');

        //取得したトークンをhiddenのinput要素にセット
        form.card_token.value = response.tokenizedCardObject.token;

        form.submit();

    } else {
        showErrorMessageCvc(response.result);
        send = false;
    }
}

function showErrorMessageCvc(result_code) {

    var error_message = "";

    switch (result_code){
      case "1600":
        error_message = 'セキュリティコードの書式が不正です。';
        break;
      case "7000":
        error_message = '非対応のブラウザです。';
        break;
      case "8000":
        error_message = 'システムメンテナンス中です。';
        break;
      default:
        error_message = 'システムエラー (' + result_code + ')';
        break;
    }

    alert(error_message);
}
