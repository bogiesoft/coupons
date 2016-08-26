<?php
include_once(dirname(__FILE__).'/../mainClass.php');
require_once 'email/class.phpmailer.php';
require_once 'email/class.smtp.php';

class EmailC extends mainClass
{
    /**
     * 发送邮件
     * @param unknown $to 表示收件人地址
     * @param string $subject 表示邮件标题
     * @param string $body 表示邮件正文
     */
    function postmail($to,$subject,$body){
        $result = array();
        try{
            $form_email = POST_EMAIL_ACCOUNT;
            $form_email_pwd = POST_EMAIL_PWD;
            $mail = new PHPMailer();
            $mail->Mailer = "SMTP";
            $mail->CharSet ="UTF-8";            // 这里指定字符集！
            $mail->IsSMTP();                    // 设定使用SMTP服务 
            $mail->Host = "smtp.exmail.qq.com"; // SMTP 服务器 
            $mail->SMTPSecure = 'ssl';          // 安全协议 
            $mail->Port = 465;                  // SMTP服务器的端口号 
            $mail->SMTPAuth = true;             //打开SMTP认证
            $mail->Username = $form_email;  // SMTP服务器用户名 
            $mail->Password = $form_email_pwd;              // SMTP服务器密码 
            $mail->From = $form_email;      // 发件人邮箱地址
            $mail->FromName = "玩券管家";      // 发件人
            $mail->Subject = $subject;      // 邮件主题
            $mail->Body = $body;            // 邮件内容
            $mail->AddAddress($to,'');      // 收件人邮箱地址和姓名
            $mail->AddReplyTo($form_email,"51玩券"); //邮件回复地址
            $mail->WordWrap = 50;           // set word wrap 换行字数
            $mail->IsHTML(true);            // send as HTML
            if(!$mail->Send()) {
                $mail->SmtpClose();
                throw new Exception('Mailer Error: ' . $mail->ErrorInfo);
            } else {
                $mail->SmtpClose();
                $result['status'] = ERROR_NONE;
            }
        }catch (Exception $e){
            $result['status'] = ERROR_EXCEPTION;
            $result['errMsg'] = $e -> getMessage();
        }        
        return json_encode($result);
    }

}