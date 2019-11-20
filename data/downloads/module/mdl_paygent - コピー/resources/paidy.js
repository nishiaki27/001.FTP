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

// PaidyCheckout設定
var paidy = Paidy.configure({
    "api_key": api_key,
    "logo_url": logo_url,
    "closed": function(callbackData) {
        $("#amount").val(callbackData.amount);
        $("#currency").val(callbackData.currency);
        $("#created_at").val(callbackData.created_at);
        $("#id").val(callbackData.id);
        $("#status").val(callbackData.status);
        if (callbackData.status === "rejected" || callbackData.status === "closed") {
            fnModeSubmit('paidy_commit_cancel','','');
        } else {
            fnModeSubmit('paidy_commit','','');
        }
    }
});
/**
 * PaidyCheckout起動判定ファンクション
 * 
 * mode = next   : PaidyCheckout起動する
 * mode = return : 戻る処理する。
 */
function checkPaidyPay(mode) {
    $('div#blackout').append('<div class="blackout"><span></span></div>');
    $("#back03").attr("disabled",true);
    $("#next").attr("disabled",true);
	if (mode == "next") {
        var postData = {};
        $('#form1').find(':input').each(function(){
            postData[$(this).attr('name')] = $(this).val();
        });
        $.ajax({
            type: "POST",
            data: postData,
            url: "./load_payment_module.php",
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert('エラーが発生しました。お手数ですが、別の決済手段をご検討ください。');
            },
            success: function(result){
            }
        });
        paidy.launch(payload);
	} else if (mode == "return") {
        fnModeSubmit(mode,'','');
        return false;
	}
}