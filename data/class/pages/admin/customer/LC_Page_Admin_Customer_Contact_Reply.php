<?php
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
require_once CLASS_EX_REALDIR . 'helper_extends/SC_Helper_FileManager_Ex.php';
/*
class SC_Query_Ex extends SC_Query {
    function unsetorder() {
        $this->order = "";
    }
}
*/

class LC_Page_Admin_Customer_Contact_Reply extends LC_Page {
    function init() {
        parent::init();
        $this->tpl_mainpage = 'customer/contact_reply.tpl';
        $this->tpl_mainno = 'customer';
        $this->tpl_subnavi = 'customer/subnavi.tpl';
        $this->tpl_subno = 'contact';
        $this->tpl_subtitle = 'お問合せ返信';
        // アップロードファイル保存先
        $this->upload_filepath = '/data/upload/contact_reply_temp';

        // メールテンプレート一覧
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrMailTemplate = $masterData->getMasterData("mtb_mail_template");
        $this->mailTemplateId = "101";

        // ファイルアップロード上限数
        $this->uploadLimitCount = 10;
    }

    function process() {
        // 認証判定
        $objSess = new SC_Session();
        SC_Utils_Ex::sfIsSuccess($objSess);

        // contact_idの取得
        if (isset($_GET['contact_id']) && SC_Utils_Ex::sfIsInt($_GET['contact_id'])) {
            $this->contact_id = $_GET['contact_id'];
        } elseif (isset($_POST['contact_id']) && SC_Utils_Ex::sfIsInt($_POST['contact_id'])) {
            $this->contact_id = $_POST['contact_id'];
        }

        $arrFileUploaded = $this->getUploadedPostData($_POST);
        $this->uploaded = $arrFileUploaded;

//        $objQuery = new SC_Query_Ex();
        $objQuery = new SC_Query();

        // お問合せ内容の取得
        $this->contact_data = $objQuery->select("*", "dtb_contact", "contact_id=?", array($this->contact_id));

        // modeの判定
        if (isset($_POST['mode'])) {
            if ($_POST['mode'] == "send") {
                $this->arrForm = $_POST;

                // 入力値のチェック
                // ①文字列変換
                /*
                 *  文字列の変換
                 *  K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
                 *  C :  「全角ひら仮名」を「全角かた仮名」に変換
                 *  V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
                 *  n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
                 *  a :  「全角」英数字を「半角」に
                 */
                $arrConvList['contact_id'] = "n";
                $arrConvList['title']      = "KV";
                $arrConvList['content']    = "KV";
                foreach ($arrConvList as $key => $val) {
                    $this->arrForm[$key] = mb_convert_kana($this->arrForm[$key], $val);
                }
                // ②値チェック
                $objErr = new SC_CheckError($this->arrForm);
                $objErr->doFunc(array("タイトル", 'title', STEXT_LEN), array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
                $objErr->doFunc(array("本文", 'content', LTEXT_LEN), array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
                $this->arrErr = $objErr->arrErr;
                if (!$this->arrErr) {
                    // メール送信
//                    $objSiteInfo = new SC_SiteView();
//                    $objSiteInfo["email01"]:"商品注文受付メールアドレス"
//                    $objSiteInfo["email02"]:"問い合わせ受付メールアドレス"
//                    $objSiteInfo["email03"]:"メール送信元メールアドレス"
//                    $objSiteInfo["email04"]:"送信エラー受付メールアドレス"
//                    $arrInfo = $this->objSiteInfo->data;
                    $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
                    $objSendMail = new SC_SendMail_Ex();

                    $to          = $this->contact_data[0]['email'];
                    $subject     = $_POST['title'];
                    $body        = $_POST['content'];
                    $fromaddress = $arrInfo['email02'];
                    $from_name   = $arrInfo['shop_name'];
                    $reply_to    = $arrInfo['email02'];
                    $return_path = $arrInfo['email04'];
                    $errors_to   = $arrInfo['email04'];
                    $bcc         = $arrInfo['email01'];
//追加者不明 削除   $cc          = $arrInfo['email02'];
                    $attachment  = $arrFileUploaded;
//                    $objSendMail->setItem($to, $subject, $body, $fromaddress, $from_name, $reply_to, $return_path, $errors_to, $bcc);
                    $objSendMail->setItem($to, $subject, $body, $reply_to, $from_name, $reply_to, $return_path, $errors_to, $bcc, $cc, $attachment);
                    $objSendMail->setTo($to, $this->contact_data[0]['name01'] . $this->contact_data[0]['name02'] . " 様");
                    if (!empty($attachment)) {
                        $objSendMail->sendMail(false, true);
                    } else {
                        $objSendMail->sendMail();
                    }

                    // メール保存
                    $sqlval['mail_id']     = $objQuery->nextVal("dtb_contact_reply_mail_id");
                    $sqlval['contact_id']  = $this->contact_id;
                    $sqlval['email']       = $this->contact_data[0]['email'];
                    $sqlval['title']       = $_POST['title'];
                    $sqlval['content']     = $_POST['content'];
                    $sqlval['content']     = $_POST['content'];
                    $sqlval['create_date'] = 'NOW()';
                    $objQuery->begin();
                    $objQuery->insert("dtb_contact_reply", $sqlval);
                    $objQuery->commit();

                    // 添付ファイル保存
                    foreach ($attachment as $key => $value) {
                        if ($value['file_name_' . $key]) {
                            $sqlval2['temp_id'] = $objQuery->nextVal("dtb_contact_reply_temp_pkey");
                            $sqlval2['mail_id']  = $sqlval['mail_id'];
                            $sqlval2['save_file']  = $value['file_name_tmp_' . $key];
                            $sqlval2['save_path']  = $value['file_path_' . $key];
                            $sqlval2['upload_file']  = $value['file_name_' . $key];
                            $objQuery->begin();
                            $objQuery->insert("dtb_contact_reply_temp", $sqlval2);
                            $objQuery->commit();
                        }
                    }
                    $this->uploaded = array();
                    echo '<script>alert("送信しました。");</script>';
                }
            } elseif ($_POST['mode'] == "template" && SC_Utils_Ex::sfIsInt($_POST['template_id'])) {
                $this->mailTemplateId = $_POST['template_id'];
            } elseif ($_POST['mode'] == "file_upload") { //ファイルアップロード
                $status = true;
                $this->arrForm = $_POST;

                // アップロード上限チェック
                if (count($arrFileUploaded) >= $this->uploadLimitCount) {
                    $status = array('upload_error' => '添付できるファイルの上限数を超えています。'); //エラー情報
                } else {
                    // アップロードファイルを取得
                    $arrFileUpload = $this->getUploadFileData('upload_file', count($arrFileUploaded));

                    // エラーチェック
                    if ($arrFileUpload['error'] == '') {
                        // アップロードしたファイルの情報を追加
                        $arrFileUploaded[] = $arrFileUpload;
                    } else {
                        $status = array('upload_error' => $arrFileData['error']); //エラー情報
                    }
                }
                $this->uploaded = $arrFileUploaded;

                // エラー情報を掲示
                if ($status !== true) {
                    $this->arrErr = $status;
                }
            } elseif ($_POST['mode'] == "file_delete") { //ファイル削除
                $status = true;
                $this->arrForm = $_POST;

                // 削除対象
                $target_key = $_POST['select_file'];
                if ($target_key !== '') {
                    // アップロードファイルを物理削除
                    if (unlink(realpath("../../../") . $_POST['file_path_' . $target_key] . $_POST['file_name_tmp_' . $target_key])) {
                        $arrFileUploaded_new = array();
                        $cnt = 0;
                        foreach ($arrFileUploaded as $key => $value) {
                            if ($key == $target_key) continue;
                            $arrFileUploaded_new[] = array(
                                'file_name_' . $cnt => $_POST['file_name_' . $key],
                                'file_name_tmp_' . $cnt => $_POST['file_name_tmp_' . $key],
                                'file_path_' . $cnt => $_POST['file_path_' . $key],
                            );
                            $cnt++;
                        }
                        $arrFileUploaded = $arrFileUploaded_new;
                        $this->uploaded = $arrFileUploaded;
                    } else {
                        $status = array('upload_error' => '取消に失敗しました。'); //エラー情報
                    }
                    // エラー情報を掲示
                    if ($status !== true) {
                        $this->arrErr = $status;
                    }
                }
            } elseif ($_POST['mode'] == "download") { //ファイルダウンロード
                // ファイル操作クラス
                if ($_POST['select_file']) {
                    $col = "temp_id, upload_file, save_file, save_path";
                    $where = "temp_id = ?";
                    $arrMailTempData = $objQuery->select($col, "dtb_contact_reply_temp", $where, array($_POST['select_file']));

                    if (!empty($arrMailTempData)) {
                        // ファイルダウンロード
                        $objFileManager = new SC_Helper_FileManager_Ex();
                        $objFileManager->sfDownloadFile(realpath("../../../") . $arrMailTempData[0]['save_path'] . $arrMailTempData[0]['save_file'], $arrMailTempData[0]['upload_file']);
                        exit;
                    }
                }
            }
        }

        // メールテンプレート内容取得
//        $objQuery->unsetorder();
        $this->mail_template = $objQuery->select("subject, header, footer", "dtb_mailtemplate", "template_id = ?", array($this->mailTemplateId));

        // GETから（初めてのページ表示時）またはテンプレート変更時は$arrFormと$arrErrを用意
        if (isset($_GET['contact_id']) || $_POST['mode'] == "template") {
            $this->arrForm['title']   = $this->mail_template[0]['subject'];
            $this->arrForm['content'] = $this->contact_data[0]['name01'] . $this->contact_data[0]['name02'] . '様

' . $this->mail_template[0]['header'] . '

 

' . $this->mail_template[0]['footer'];
            $this->arrErr['title'] = '';
            $this->arrErr['content'] = '';
        }

        // 返信一覧の取得
        $objQuery->setorder("create_date DESC");
        $arrReplyData = $objQuery->select("*", "dtb_contact_reply", "contact_id = ?", array($this->contact_id));

        // 添付ファイル取得
        foreach ($arrReplyData as $key => $value) {
            $arrReplyData[$key]['upload_file'] = array();

            $col = "temp_id, upload_file";
            $where = "mail_id = ?";
            $objQuery->setOrder("temp_id ASC");
            $arrReplyData[$key]['upload_file'] = $objQuery->select($col, "dtb_contact_reply_temp", $where, array($value['mail_id']));
        }
        $this->arrReply = $arrReplyData;

        $objView = new SC_AdminView();
        $objView->assignobj($this);
        $objView->display(MAIN_FRAME);
    }

function getUploadedPostData($objFormParam) {
        $arrFileUploaded = array();
        for ($i = 0; $i < $this->uploadLimitCount; $i++) {
            if ($objFormParam['file_name_' . $i]) {
                $arrFileUploaded[] = array(
                    'file_name_' . $i => $objFormParam['file_name_' . $i],
                    'file_name_tmp_' . $i => $objFormParam['file_name_tmp_' . $i],
                    'file_path_' . $i => $objFormParam['file_path_' . $i],
                );
            }
        }
        return $arrFileUploaded;
    }

    /**
     * 指定されたアップロードファイルを取得する。
     * @var str upload_file
     */
    function getUploadFileData($upload_file, $cnt) {
        // 設定
        $save_path  = $this->upload_filepath . '/'; //保存先ディレクトリ
        $save_path .= date('Y') . '/';
        $save_path .= date('m') . '/';
        $file_name_sub = date('dHis') . '_' . $this->makeRandStr(5); //保存ファイル名

        // 返却値
        $file_data = array(
            'file_name_' . $cnt => '',
            'file_name_tmp_' . $cnt => '',
            'file_path_' . $cnt => '',
            'error' => '',
        );

        // 一時ファイルチェック
        if (is_uploaded_file($_FILES[$upload_file]['tmp_name'])) {
            // ディレクトリ作成
            if (file_exists(realpath("../../../") . $save_path) == false) {
                if (mkdir(realpath("../../../") . $save_path, 0755, true) == false) {
                    $file_data['error'] = "※ファイルの保存先に問題が生じました。";
                }
            }

            if ($file_data['error'] == '') {
                // 拡張子取得
                $file_exte = pathinfo($_FILES[$upload_file]['name'], PATHINFO_EXTENSION);

                $file_data['file_path_' . $cnt] = $save_path;
                $file_data['file_name_' . $cnt] = $_FILES[$upload_file]['name'];
                $file_data['file_name_tmp_' . $cnt] = $file_name_sub . '.' . $file_exte;

                // 一時ファイルを指定ディレクトリにコピー
                if (move_uploaded_file($_FILES[$upload_file]['tmp_name'], realpath("../../../") . $file_data['file_path_' . $cnt] . $file_data['file_name_tmp_' . $cnt])) {
                } else {
                    $file_data['error'] = "※ファイルの保存に失敗しました。";
                }
            }
        } else {
            $file_data['error'] = "※ファイルのアップロードに失敗しました。";
        }
        return $file_data;
    }
    /**
     * ランダム文字列生成 (英数字)
     * $length: 生成する文字数
     */
    function makeRandStr($length) {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $r_str = null;
        for ($i = 0; $i < $length; $i++) {
            $r_str .= $str[rand(0, count($str) - 1)];
        }
        return $r_str;
    }
}
?>