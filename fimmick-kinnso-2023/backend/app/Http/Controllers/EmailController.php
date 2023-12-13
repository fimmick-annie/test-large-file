<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by John SHAN
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2021.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use App\Models\CampaignOfferHunting;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

//========================================================================================
class EmailController extends Controller  {

	//----------------------------------------------------------------------------------------
	public static function sendFosoUserInfoEmail($email="", $password="")  {

		$brandName = config("app.brand_name", "KINNSO");
		$url = "https://www.kinnso.com/foso";

		$environment = env("APP_ENV", "local");
		if ($environment != "production")  {
			$url = "https://".env("DOMAIN_STAGING", "")."/foso";
		}

		$prefix = config("app.env", "DEV");
		if ($prefix != "")  {$prefix = " $prefix:";}

		$searchArr = [
			"##__FOSO__##",
			"##__EMAIL__##",
			"##__PASSWORD__##",
			"##__BRAND__##",
		];
		$replaceArr = [
			$url,
			$email,
			$password,
			$brandName,
		];

		$html = file_get_contents(storage_path("email/create_foso_user.html"));
		$html = str_replace($searchArr, $replaceArr, $html);

		$mail = (new EmailController)->PHPMailerSetUp();

		$mail->Subject = "=?UTF-8?B?".base64_encode("[$brandName]$prefix FOSO User Creation")."?=";

		$mail->Body = $html;

		$mail->AddAddress($email);
		$sendResult = $mail->Send();
		return $sendResult;
	}

	public static function sendOfferHuntingEmail($emails, CampaignOfferHunting $offerHunting)
	{
		$brandName = config("app.brand_name", "KINNSO");

		$prefix = config("app.env", "DEV");
		if ($prefix != "")  {$prefix = " $prefix:";}

		$searchArr = [
			"##__NAME__##",
			"##__WHATSAPP_NUMBER__##",
			// "##__IMAGE__##",
			"##__CONTENT__##",
			'##__BRAND__##'
		];
		$replaceArr = [
			$offerHunting->name,
			substr($offerHunting->mobile_num, 0, 4) . 'xxxx',
			// isset($offerHunting->media) ? storage_path($offerHunting->media) : '',
			$offerHunting->discount_content,
			$brandName
		];

		$html = file_get_contents(storage_path("email/offer_hunter_notification.html"));
		$html = str_replace($searchArr, $replaceArr, $html);

		$mail = (new EmailController)->PHPMailerSetUp();

		$mail->Subject = "=?UTF-8?B?".base64_encode("[$brandName]$prefix New Offer Hunting Submission")."?=";

		$mail->Body = $html;

		foreach ($emails as $email) {
			$mail->addAddress($email);
		}

		$sendResult = $mail->Send();
		return $sendResult;
	}

	private function PHPMailerSetUp()
	{
		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->Host = "smtp.gmail.com";
		$mail->SMTPAuth = true;
		$mail->Username = "it@fimmick.com";
		$mail->Password = "bdwviinvdmmcclke";
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = 587;

		$mail->setFrom("it@fimmick.com", "Fimmick Development Team");

		$mail->isHTML(true);
		$mail->CharSet = "UTF-8";

		return $mail;
	}
}
