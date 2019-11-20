<?php
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

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/order/LC_Page_Admin_Order_Ex.php';

/**
 * 受注メール管理 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_Mail.php 20970 2011-06-10 10:27:24Z Seasoft $
 */
class LC_Page_Admin_Order_Mail extends LC_Page_Admin_Order_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'order/mail.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'index';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '受注管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrMAILTEMPLATE = $masterData->getMasterData("mtb_mail_template");
  asort($this->arrMAILTEMPLATE);
        $this->arrZAIKOTEMPLATE = $masterData->getMasterData("mtb_zaiko_template");
	asort($this->arrZAIKOTEMPLATE);
        $this->arrDELIVTEMPLATE = $masterData->getMasterData("mtb_deliv_template");

        $this->httpCacheControl('nocache');

        $objDate = new SC_Date_Ex();
        // 月日の設定
        $this->arrYear = $objDate->getYear();
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();


     }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);

        // POST値の取得
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $this->tpl_order_id = $objFormParam->getValue('order_id');

        // 検索パラメーターの引き継ぎ
        $this->arrSearchHidden = $objFormParam->getSearchArray();

        switch($this->getMode()) {
            case 'pre_edit':
                break;
            case 'return':
                break;
            case 'send':
                $sendStatus = $this->doSend($objFormParam);
                $this->sendZaikoMail($objFormParam);
                if($sendStatus === true){
                    SC_Response_Ex::sendRedirect(ADMIN_ORDER_URLPATH);
                    exit;
                }else{
                    $this->arrErr = $sendStatus;
                }
            case 'confirm':
                $status = $this->confirm($objFormParam);


                if($status === true){
                    $this->arrHidden = $objFormParam->getHashArray();
                    return ;
                }else{
                    $this->arrErr = $status;
                }
                break;
            case 'change':
                $objFormParam =  $this->changeData($objFormParam);
                break;
        }

        if(SC_Utils_Ex::sfIsInt($objFormParam->getValue('order_id'))) {
            $this->arrMailHistory = $this->getMailHistory($objFormParam->getValue('order_id'));
        }
        $this->arrForm = $objFormParam->getFormParamList();
    }

    /**
     * 指定された注文番号のメール履歴を取得する。
     * @var int order_id
     */
    function getMailHistory($order_id){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = "send_date, subject, template_id, send_id";
        $where = "order_id = ?";
        $objQuery->setOrder("send_date DESC");
        return $objQuery->select($col, "dtb_mail_history", $where, array($order_id));
    }

    /**
     *
     * メールを送る。
     * @param SC_FormParam $objFormParam
     */
    function doSend(&$objFormParam){
        $arrErr = $objFormParam->checkerror();

        // メールの送信
        if (count($arrErr) == 0) {
            // 注文受付メール
            $objMail = new SC_Helper_Mail_Ex();
            $objSendMail = $objMail->sfSendOrderMail($objFormParam->getValue('order_id'),
            $objFormParam->getValue('template_id'),
            $objFormParam->getValue('subject'),
            $objFormParam->getValue('header'),
            $objFormParam->getValue('footer'));
            // TODO $SC_SendMail から送信がちゃんと出来たか確認できたら素敵。
            return true;
        }
        return $arrErr;
    }

    /**
     * 確認画面を表示する為の準備
     * @param SC_FormParam $objFormParam
     */
    function confirm(&$objFormParam){
        $arrErr = $objFormParam->checkerror();
        // メールの送信
        if (count($arrErr) == 0) {
            // 注文受付メール(送信なし)
            $objMail = new SC_Helper_Mail_Ex();
            $objSendMail = $objMail->sfSendOrderMail(
            $objFormParam->getValue('order_id'),
            $objFormParam->getValue('template_id'),
            $objFormParam->getValue('subject'),
            $objFormParam->getValue('header'),
            $objFormParam->getValue('footer'), false);

            $this->tpl_subject = $objFormParam->getValue('subject');
            $this->tpl_body = mb_convert_encoding( $objSendMail->body, CHAR_CODE, 'auto' );
            $this->tpl_to = $objSendMail->tpl_to;
            $this->tpl_mainpage = 'order/mail_confirm.tpl';
            return true;
        }
        return $arrErr;
    }

	function sendZaikoMail(&$objFormParam){
		$mail_template=$objFormParam->getValue('template_id');	//メールのテンプレ
		$deliv_id=$objFormParam->getValue('zaiko_day');
		$strZaiko=array();
		$zaiko_up="ON";

		switch($mail_template){
			case 6:
			case 9:
				if($deliv_id==8){
					$strZaiko['subject']=$objFormParam->getValue('zaiko_name')."：".$objFormParam->getValue('deliv_month')."/".$objFormParam->getValue('deliv_day') . " 以降";
				}else{
					$strZaiko['subject']=$objFormParam->getValue('zaiko_name'). "：" . $objFormParam->getValue('deliv_name');
				}
				//メール内在庫状況の部分一部抜粋
				$header=explode("-------------------------------------------------------------------------\r\n", $objFormParam->getValue('header'));
				$strZaiko['text']=$header[1];
				
				break;
			case 8:
				//お届け日のメールのとき
				$set_year=$objFormParam->getValue('set_deliv_year');
				$set_month=$objFormParam->getValue('set_deliv_month');
				$set_day=$objFormParam->getValue('set_deliv_day');
				$set_jp=$objFormParam->getValue('set_deliv_jp');

				$strZaiko['deliv_date']=$set_year."/".sprintf("%02d",$set_month) ."/" .sprintf("%02d",$set_day) ."(". $set_jp .")";
				break;
			
			case 20:
				//メールのテンプレが代替機器の提案だったら、zaiko_nameを変更
				if($deliv_id==8){
					$strZaiko['subject']= "代替機器：" .$objFormParam->getValue('deliv_month')."/".$objFormParam->getValue('deliv_day') . " 以降";
				}else{
					$strZaiko['subject']= "代替機器：" . $objFormParam->getValue('deliv_name');
				}
				//メール内在庫状況の部分一部抜粋
				$header=explode("-------------------------------------------------------------------------\r\n", $objFormParam->getValue('header'));
				$strZaiko['text']=$header[0]."-------------------------------------------------------------------------\r\n".$header[1];
				break;
			default:
				$zaiko_up="";
				break;
		}

		if($zaiko_up){
			$strZaiko['id']=$objFormParam->getValue('order_id');
			$mailHistorys="";
			//既にデータがあったらUPDATE/なければINSERT
	        $objQuery =& SC_Query_Ex::getSingletonInstance();
            $where = "id = ?";	        
			$mailHistorys = $objQuery->select("*", "dtb_order_mail_history", $where, array($objFormParam->getValue('order_id')));
	        if(count($mailHistorys)) {
   		    	$strZaiko['update_date']='Now()';
		    	$objQuery->update("dtb_order_mail_history", $strZaiko , $where, array($objFormParam->getValue('order_id')));
		    }else{
		    	$strZaiko['create_date']='Now()';
		    	$strZaiko['update_date']='Now()';
		        $objQuery->insert("dtb_order_mail_history", $strZaiko );
		    }

		}
		
	}
    /**
     *
     * テンプレートの文言をフォームに入れる。
     * @param SC_FormParam $objFormParam
     */
     function changeData(&$objFormParam){
        if(SC_Utils_Ex::sfIsInt($objFormParam->getValue('template_id'))) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $where = "template_id = ?";
            $mailTemplates = $objQuery->select("subject, header, footer", "dtb_mailtemplate", $where, array($objFormParam->getValue('template_id')));
            if(!is_null($mailTemplates )){
                foreach(array('subject','header','footer') as $key){
                    $objFormParam->setValue($key,$mailTemplates[$key]);
                }
            }
            $objFormParam->setParam($mailTemplates[0]);
        }else{
            foreach(array('subject','header','footer') as $key){
                $objFormParam->setValue($key,"");
            }
        }

         if(SC_Utils_Ex::sfIsInt($objFormParam->getValue('zaiko_id'))) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $where = "template_id = ?";
            $mailTemplates = $objQuery->select("text", "dtb_zaiko_template", $where, array($objFormParam->getValue('zaiko_id')));
            
            if(!is_null($mailTemplates )){
                foreach(array('text') as $key){
                    $objFormParam->setValue($key,$mailTemplates[$key]);
                    
                }
            }
            $objFormParam->setParam($mailTemplates[0]);
            
            //在庫状況のラベル部分を取得
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $where = "id = ?";
            $mailTemplates = $objQuery->select("name", "mtb_zaiko_template", $where, array($objFormParam->getValue('zaiko_id')));

            if(!is_null($mailTemplates )){
            	if(!is_null($mailTemplates['name'])){
            		$objFormParam->setValue('zaiko_name',$mailTemplates['name']);
            	}else{
	            	$objFormParam->setValue('zaiko_name',$mailTemplates[0]['name']);
	            }
            }
            $objFormParam->setParam($mailTemplates[0]);


        }else{
            foreach(array('text','zaiko_name') as $key){
                $objFormParam->setValue($key,"");
            }
        }

         if(SC_Utils_Ex::sfIsInt($objFormParam->getValue('zaiko_day'))) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $where = "template_id = ?";
            $mailTemplates = $objQuery->select("deliv_text", "dtb_deliv_template", $where, array($objFormParam->getValue('zaiko_day')));
            
            if(!is_null($mailTemplates )){
                foreach(array('deliv_text') as $key){
                    $objFormParam->setValue($key,$mailTemplates[$key]);
                }
            }

            $objFormParam->setParam($mailTemplates[0]);

			//お届け目安日のラベル部分を取得
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $where = "id = ?";
            $mailTemplates = $objQuery->select("name", "mtb_deliv_template", $where, array($objFormParam->getValue('zaiko_day')));
            
            if(!is_null($mailTemplates )){
            	if(!is_null($mailTemplates['name'])){
            		$objFormParam->setValue('deliv_name',$mailTemplates['name']);
            	}else{
	            	$objFormParam->setValue('deliv_name',$mailTemplates[0]['name']);
	            }
            }

            $objFormParam->setParam($mailTemplates[0]);


        }else{
            foreach(array('deliv_text','deliv_name') as $key){
                $objFormParam->setValue($key,"");
            }
        }
		
		//お届け日の曜日取得
		$set_year=$objFormParam->getValue('set_deliv_year');
		$set_month=$objFormParam->getValue('set_deliv_month');
		$set_day=$objFormParam->getValue('set_deliv_day');
		if($set_day){
			//曜日の変換
			$date = $set_year."-".sprintf("%02d",$set_month)."-".sprintf("%02d",$set_day);
			$week = array("日", "月", "火", "水", "木", "金", "土");
			$time = strtotime($date);
			$w = date("w", $time);

			$objFormParam->setValue('set_deliv_jp', $week[$w]);
		}


        return $objFormParam;
    }
    
    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * パラメーター情報の初期化
     * @param SC_FormParam $objFormParam
     */
    function lfInitParam(&$objFormParam) {
        // 検索条件のパラメーターを初期化
        parent::lfInitParam($objFormParam);

        $objFormParam->addParam("オーダーID", "order_id", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("テンプレート", "template_id", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("メールタイトル", 'subject', STEXT_LEN, 'KVa',  array("EXIST_CHECK", "MAX_LENGTH_CHECK", "SPTAB_CHECK"));
        $objFormParam->addParam("ヘッダー", 'header', LTEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));
        $objFormParam->addParam("フッター", 'footer', LTEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK", "SPTAB_CHECK"));

        $objFormParam->addParam("在庫状況ID", "zaiko_id", INT_LEN, 'n', array());
        $objFormParam->addParam("在庫状況", 'text', STEXT_LEN, 'KVa',  array());
		$objFormParam->addParam("在庫名前", 'zaiko_name', STEXT_LEN, 'KVa',  array());
        
        $objFormParam->addParam("お届け日状況", "zaiko_day", INT_LEN, 'n', array());
        $objFormParam->addParam("お届け日テンプレ", 'deliv_text', STEXT_LEN, 'KVa',  array());
        $objFormParam->addParam("お届け日名前", "deliv_name",  STEXT_LEN, 'KVa', array());
		
		//お届け目安日（任意）
        $objFormParam->addParam("目安月", "deliv_month", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("目安日", "deliv_day", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));

		//お届け日
		$objFormParam->addParam("配送年", "set_deliv_year", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("配送月", "set_deliv_month", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("配送日", "set_deliv_day", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("配送曜日", "set_deliv_jp", STEXT_LEN, 'KVa', array());
    }
}
?>
