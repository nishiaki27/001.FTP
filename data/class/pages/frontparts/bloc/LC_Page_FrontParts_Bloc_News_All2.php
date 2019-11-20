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
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * 新着情報 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_FrontParts_Bloc_News_All2.php 21126 2011-08-04 11:48:01Z nanasess $
 */
class LC_Page_FrontParts_Bloc_News extends LC_Page_FrontParts_Bloc {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
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

        $objFormParam = new SC_FormParam_Ex();
        switch($this->getMode()){
            case "getList":
                $this->lfInitNewsParam($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->arrErr = $objFormParam->checkError(false);
                if(empty($this->arrErr)){
                    $json = $this->lfGetNewsForJson($objFormParam);
                    echo $json;
                    exit;
                } else {
                    echo $this->lfGetErrors($this->arrErr);
                    exit;
                }
                break;
            case "getDetail":
                $this->lfInitNewsParam($objFormParam);
                $objFormParam->setParam($_GET);
                $objFormParam->convParam();
                $this->arrErr = $objFormParam->checkError(false);
                if(empty($this->arrErr)){
                     $json = $this->lfGetNewsDetailForJson($objFormParam);
                     echo $json;
                     exit;
                } else {
                    echo $this->lfGetErrors($this->arrErr);
                    exit;
                }
                break;
            default:
                $this->newsCount = $this->lfGetNewsCount();
                $this->arrNews = $this->lfGetNews();
                break;
        }
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
     * 新着情報パラメーター初期化
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return void
     */
    function lfInitNewsParam(&$objFormParam) {
        $objFormParam->addParam("現在ページ", "pageno", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"), "", false);
        $objFormParam->addParam("表示件数", "disp_number", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"), "", false);
        $objFormParam->addParam("新着ID", "news_id", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"), "", false);
    }

    /**
     * 新着情報を取得する.
     *
     * @return array $arrNewsList 新着情報の配列を返す
     */
    function lfGetNews(){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $sql = '';
        $sql .= " SELECT ";
        $sql .= "   *, ";
        $sql .= "   cast(news_date as date) as news_date_disp ";
        $sql .= " FROM ";
        $sql .= "   dtb_news ";
        $sql .= " WHERE ";
        $sql .= "   del_flg = '0' ";
        $sql .= " ORDER BY ";
        $sql .= "   rank DESC ";

        $arrNewsList = $objQuery->getAll($sql);
        return $arrNewsList;
    }

    /**
     * 新着情報をJSON形式で取得する
     * (ページと表示件数を指定)
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return String $json 新着情報のJSONを返す
     */
    function lfGetNewsForJson(&$objFormParam){

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrData = $objFormParam->getHashArray();
		
        $dispNumber = $arrData['disp_number'];
        $pageNo = $arrData['pageno'];
        if(!empty($dispNumber) && !empty($pageNo)){
             $objQuery->setLimitOffset($dispNumber, (($pageNo - 1) * $dispNumber));
        }

        $objQuery->setOrder("rank DESC ");
        $arrNewsList = $objQuery->select(" * , cast(news_date as date) as news_date_disp "," dtb_news "," del_flg = '0' ");

        //新着情報の最大ページ数をセット
        $newsCount = $this->lfGetNewsCount();
        $arrNewsList["news_page_count"] = ceil($newsCount / 3);

        $json =  SC_Utils_Ex::jsonEncode($arrNewsList);    //JSON形式

        return $json;
    }

    /**
     * 新着情報1件分をJSON形式で取得する
     * (news_idを指定)
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return String $json 新着情報1件分のJSONを返す
     */
    function lfGetNewsDetailForJson(&$objFormParam){

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrData = $objFormParam->getHashArray();
        $newsId = $arrData['news_id'];
        $arrNewsList = $objQuery->select(" * , cast(news_date as date) as news_date_disp "," dtb_news "," del_flg = '0' AND news_id = ? ", array($newsId));

        $json =  SC_Utils_Ex::jsonEncode($arrNewsList);    //JSON形式

        return $json;
    }
    
    /**
     * 新着情報の件数を取得する
     *
     * @return Integer $count 新着情報の件数を返す
     */
    function lfGetNewsCount(){

        $count = 0;
        
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $count = $objQuery->count("dtb_news", "del_flg = '0'");

        return $count;
    }

    /**
     * エラーメッセージを整形し, JSON 形式で返す.
     *
     * @param array $arrErr エラーメッセージの配列
     * @return string JSON 形式のエラーメッセージ
     */
    function lfGetErrors($arrErr) {
        $messages = '';
        foreach ($arrErr as $val) {
            $messages .= $val . "\n";
        }
        return SC_Utils_Ex::jsonEncode(array('error' => $messages));
    }
}
?>
