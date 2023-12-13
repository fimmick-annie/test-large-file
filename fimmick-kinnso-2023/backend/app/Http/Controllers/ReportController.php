<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\CampaignOffer;
use App\Models\WhatsappWebhook;
use App\Models\CampaignWhatsappMessageQueue;
use App\Models\CampaignCoupon;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

use DB;

//========================================================================================
class ReportController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  CSV Filename: offer_1_coupon_daily_report_2020-07-15.csv
	public function processOfferCouponDailyReport()  {

		$folder = storage_path("app/public/");
		$yesterday = date("Y-m-d", strtotime("-1 day"));

		$prefix = env("WHATSAPP_PREFIX", "");
		$reportEnabled = env("REPORT_ENABLED", "true");
		if ($reportEnabled == false)  {
			echo("### Report disabled...");
			return;
		}

		// CSV header
		$headerArray = [
			'created_at',
			'offer_id',
			'parent_offer_id',
			'coupon_order',
			'unique_code',
			'mobile',
			'email',
			'start_at',
			'use_at',
			'expired_at',
			'selected_channel',
			'referrer_code',
			'referral_code',
		];

		//----------------------------------------------------------------------------------------
		//  Export outbound CSV
		$offerArray = CampaignOffer::getList(null, $yesterday);
		foreach ($offerArray as $offer)  {

			$_headerArray = $headerArray;
			$offerID = $offer->id;
			$offerName = $offer->offer_name;
			$offerEndAt = $offer->end_at;

			$offerINI = parse_ini_file(public_path("offers/".$offerName."/offer.ini"), true);
			echo("\nProcessing offer #$offerID...");

			$recipients = "";
			if (isset($offerINI["settings"]["daily_coupon_report_recipients"]) !== false)  {
				$recipients = trim($offerINI["settings"]["daily_coupon_report_recipients"]);
			}
			if (empty($recipients))  {
				echo("### Recipient not found...");
				continue;
			}

			$passwordRecipients = "";
			if (isset($offerINI["settings"]["daily_report_password_recipients"]) !== false)  {
				$passwordRecipients = trim($offerINI["settings"]["daily_report_password_recipients"]);
			}
			if (empty($passwordRecipients))  {
				echo("### Password Recipient not found");
			}

			$dateExtend = "+ 7 days";
			if (isset($offerINI["settings"]["daily_coupon_date_extend"]) !== false)  {
				$dateExtend = trim($offerINI["settings"]["daily_coupon_date_extend"]);
			}
			if (strtotime($offerEndAt.$dateExtend) < strtotime($yesterday)) {
				continue;
			}

			// form data header
			$formDataPrefix = 'form_data_';
			$couponArray = CampaignCoupon::where('parent_offer_id', $offerID)
									->whereNotNull('form_data')
									->distinct()
									->get(DB::raw('JSON_KEYS(`form_data`) AS `form_data_keys`'));
			if (!$couponArray->isEmpty()) {
				foreach($couponArray as $key => $coupon) {

					$formDataKeys = json_decode($coupon->form_data_keys, true);
					foreach($formDataKeys as $key => $value) {

						$fieldName = $formDataPrefix.$value;
						if (!in_array($fieldName, $_headerArray))
							$_headerArray[] = $fieldName;
					}
				}
			}

			// referral data header
			$referralDataPrefix = 'referral_data_';
			$couponArray = CampaignCoupon::where('parent_offer_id', $offerID)
									->whereNotNull('referral_data')
									->distinct()
									->get(DB::raw('JSON_KEYS(`referral_data`) AS `referral_data_keys`'));
			if (!$couponArray->isEmpty()) {
				foreach($couponArray as $key => $coupon) {

					$referralDataKeys = json_decode($coupon->referral_data_keys, true);
					foreach($referralDataKeys as $key => $value) {

						$fieldName = $referralDataPrefix.$value;
						if (!in_array($fieldName, $_headerArray))
							$_headerArray[] = $fieldName;
					}
				}
			}

			//----------------------------------------------------------------------------------------
			//  Preparing CSV file
			$csvFilename = "offer_".$offerID."_coupon_daily_report_".$yesterday.".csv";
			$csvFilePath = $folder.$csvFilename;

			$handle = fopen($csvFilePath, "w");
			if ($handle === false)  {

				echo("### Error creating CSV file '$csvFilename'...");
				return;
			}

			//  UTF-8 header bytes
			fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));
			fputcsv($handle, $_headerArray);

			$recordCount = CampaignCoupon::where('parent_offer_id', $offerID)
											->count();
			if ($recordCount > 0) {
				$recordsPerPage = 500;
				$totalPages = ceil($recordCount/$recordsPerPage);

				for ($page = 1; $page <= $totalPages; $page++) {

					$skip = $recordsPerPage * ($page - 1);
					$couponArray = CampaignCoupon::where('parent_offer_id', $offerID)
													->skip($skip)
													->take($recordsPerPage)
													->get();
					if (!$couponArray->isEmpty()) {

						//  Export CSV content
						$_headerArrayLength = count($_headerArray);
						foreach($couponArray as $key => $coupon) {

							$rowArray = array_fill(0, $_headerArrayLength, null);
							$coupon = $coupon->toArray();
							foreach($coupon as $fieldName => $value) {

								if (!is_null($value) and in_array($fieldName, ['form_data', 'referral_data'])) {
									$data = json_decode($value, true);
									if (is_array($data)) {
										$fieldNamePrefix = $fieldName.'_';
										foreach($data as $_fieldName => $_value) {

											$index = null;
											$_fieldName = $fieldNamePrefix.$_fieldName;
											if (in_array($_fieldName, $_headerArray))
												$index = array_search($_fieldName, $_headerArray);
											if (!is_null($index))
												$rowArray[$index] = $_value;
										}
									}
								} else {
									$index = null;
									if (in_array($fieldName, $_headerArray))
										$index = array_search($fieldName, $_headerArray);
									if (!is_null($index))
										$rowArray[$index] = $value;
								}
							}

							fputcsv($handle, $rowArray);
						}
					}
				}
			}

			//----------------------------------------------------------------------------------------
			//  CSV file created
			fclose($handle);

			if ($recordCount == 0)  {

				//  No record in CSV, remove it and next
				unlink($csvFilePath);
				echo("### No record found...");
				continue;
			}
			echo($recordCount);

			//----------------------------------------------------------------------------------------
			//  Send email notification with attachment

			//  Generate password
			$length = 10;
			$charsArray = ["abcdefghijklmnopqrstuvwxyz", "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "0123456789", "!@#$%^&*()_-=+`~[]{}|:;,.<>/?"];
			$password = Str::random($length-count($charsArray));
			foreach ($charsArray as $chars) {
				$password .= substr(str_shuffle($chars), 0, 1);
			}
			$password = str_shuffle($password);

			$brandName = env("BRAND_NAME", "");
			try  {

				$body = "Dear all,<br>".
						"<br>".
						"Attached please find coupon daily report CSV with ZIP password protected.  Password will be sent with another email, please check.  Thanks!<br>".
						"<br>".
						$brandName." WhatsApp Offer Project<br>".
						"<b>Fimmick Development Team</b><br>".
						__FUNCTION__;

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
				$mail->Subject = "=?UTF-8?B?".base64_encode("[$brandName] ".$prefix."$brandName Offer #$offerID daily coupon report $yesterday...")."?=";
				$mail->Body = $body;

				$mail->addBCC("developer1@fimmick.com");
				$mail->addBCC("pacessho@fimmick.com");

				$recipientArray = explode(",", $recipients);
				foreach ($recipientArray as $recipient)  {

					$recipient = trim($recipient);
					$mail->AddAddress($recipient);
				}

				//  Attachments
				$csvFileSize = filesize($csvFilePath);
				//  Zip CSVs regardless of size because of encryption requirement
				if ($csvFileSize > 262144 || true)  {					// 256KB

					//  ZIP it
					$zip = new \ZipArchive();
					$zipFilePath = substr($csvFilePath, 0, -4).".zip";
					$result = $zip->open($zipFilePath, \ZipArchive::CREATE|\ZipArchive::OVERWRITE);
					$zip->setPassword($password);
					if ($result == true)  {

						$zip->addFile($csvFilePath, $csvFilename);
						$zip->setEncryptionName($csvFilename, \ZipArchive::EM_AES_256);
						$zip->close();

						$csvFilePath = $zipFilePath;
						$csvFilename = substr($csvFilename, 0, -4).".zip";
					}
				}

				//  Attachments
				$mail->AddAttachment($csvFilePath, $csvFilename);
				$sendResult = $mail->Send();
				echo("\nReport Email Result: $sendResult");

			}  catch (Exception $e)  {
				echo("### Error sending report email...".$_eol);
			}

			//  Sending Email password
			try {

				$body = "Dear all,<br>".
				"<br>".
				"Here is the password for the files in the previous email.<br>".
				"<br>".
				"<b>$password</b><br>".
				"<br>".
				"Regards,<br>".
				"$brandName WhatsApp Offer Project<br>".
				"<b>Fimmick Development Team</b><br>".
				__FUNCTION__;

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
				$mail->Subject = "=?UTF-8?B?".base64_encode("[$brandName] ".$prefix."$brandName Offer #$offerID daily coupon report $yesterday password")."?=";
				$mail->Body = $body;

				$mail->addBCC("developer1@fimmick.com");
				$mail->addBCC("pacessho@fimmick.com");

				$recipientArray = explode(",", $passwordRecipients);

				if (!empty($recipientArray[0])){

					foreach ($recipientArray as $recipient)  {

						$recipient = trim($recipient);
						$mail->AddAddress($recipient);
					}

					$sendResult = $mail->Send();
					echo("\nPassword Email Result: $sendResult");
				}

			} catch (Exception $e) {
				echo("### Error sending password email...".$_eol);
			}

			echo "\n";
		}

	}

	//----------------------------------------------------------------------------------------
	//  CSV Filename: offer_1_daily_report_2020-07-15.csv
	public function processOfferWhatsAppDailyReport()  {

		$reportEnabled = env("REPORT_ENABLED", "true");
		if ($reportEnabled == false)  {

			echo("### Report disabled...");
			return;
		}

		$folder = storage_path("app/public/");
		$yesterday = date("Y-m-d", strtotime("-1 day"));

		$prefix = env("WHATSAPP_PREFIX", "");
		$sender = env("WHATSAPP_SENDER", "");
		$brandName = env("BRAND_NAME", "");

		//  CSV header
		$headerArray = array(
			"Create Time",
			"Direction",
			"Sent Time",
			"Status",
			"From",
			"To",
			"Offer ID",
			"Message",
			"Message Type",
			"Message ID",
		);

		//----------------------------------------------------------------------------------------
		//  Export outbound CSV
		$offerArray = CampaignOffer::getList($yesterday, $yesterday);
		foreach ($offerArray as $offer)  {

			$offerID = $offer->id;
			$offerINI = parse_ini_file(public_path("offers/".$offer->offer_name."/offer.ini"), true);
			echo("\nProcessing offer #$offerID...");

			$recipients = "";
			if (isset($offerINI["settings"]["daily_outbound_report_recipients"]) !== false)  {
				$recipients = trim($offerINI["settings"]["daily_outbound_report_recipients"]);
			}
			if (empty($recipients))  {

				echo("### Recipient not found...");
				continue;
			}

			//----------------------------------------------------------------------------------------
			//  Preparing CSV file
			$csvFilename = "offer_".$offerID."_daily_report_".$yesterday.".csv";
			$csvFilePath = $folder.$csvFilename;

			$handle = fopen($csvFilePath, "w");
			if ($handle === false)  {

				echo("### Error creating CSV file '$csvFilename'...");
				return;
			}

			//  UTF-8 header bytes
			fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));
			fputcsv($handle, $headerArray);

			//  Get outbound records
			$fromDateTime = $yesterday." 00:00:00";
			$toDateTime = $yesterday." 23:59:59";
			$array = CampaignWhatsappMessageQueue::getSentRecords($fromDateTime, $toDateTime, $offerID);
			$recordCount = count($array);

			//  Export CSV content
			foreach ($array as $row)  {

				$rowArray = array(
					$row["created_at"],
					"Outbound",
					$row["send_at"],
					$row["status"],
					$sender,
					$row["mobile"],
					$row["offer_id"],
					$row["message"],
					$row["message_type"],
					$row["message_id"],
				);
				fputcsv($handle, $rowArray);
			}

			//----------------------------------------------------------------------------------------
			//  CSV file created
			fclose($handle);

			if ($recordCount == 0)  {

				//  No record in CSV, remove it and next
				unlink($csvFilePath);
				echo("### No record found...");
				continue;
			}
			echo($recordCount);

			//----------------------------------------------------------------------------------------
			//  Send email notification with attachment
			try  {

				$body = "Dear all,<br>".
						"<br>".
						"Attached please find outbound daily report CSV.  Thanks!<br>".
						"<br>".
						"$brandName WhatsApp Offer Project<br>".
						"<b>Fimmick Development Team</b><br>".
						__FUNCTION__;

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
				$mail->Subject = "=?UTF-8?B?".base64_encode("[$brandName] ".$prefix."$brandName Offer #$offerID daily report $yesterday...")."?=";
				$mail->Body = $body;

				$mail->addBCC("developer1@fimmick.com");
				$mail->addBCC("pacessho@fimmick.com");

				$recipientArray = explode(",", $recipients);
				foreach ($recipientArray as $recipient)  {

					$recipient = trim($recipient);
					$mail->AddAddress($recipient);
				}

				//  Attachments
				$mail->AddAttachment($csvFilePath, $csvFilename);
				$sendResult = $mail->Send();
				echo("\nResult: $sendResult");

			}  catch (Exception $e)  {
				echo("### Error sending email...".$_eol);
			}

		}
		echo("\n");
	}

	//----------------------------------------------------------------------------------------
	//  CSV Filename: offer_monthly_report_2020-07.csv
	public function processOfferWhatsAppMonthlyReport()  {

		$yesterday = date("Y-m-d", strtotime("-1 day"));
		$firstOfMonth = date("Y-m-01", strtotime("-1 day"));
		$this->processOfferWhatsAppMonthlyReportWithDate($firstOfMonth, $yesterday);
	}

	public function processOfferWhatsAppMonthlyReportMay()  {
		$this->processOfferWhatsAppMonthlyReportWithDate("2020-05-01", "2020-05-31");
	}
	public function processOfferWhatsAppMonthlyReportJune()  {
		$this->processOfferWhatsAppMonthlyReportWithDate("2020-06-01", "2020-06-30");
	}

	//----------------------------------------------------------------------------------------
	public function processOfferWhatsAppMonthlyReportWithDate($startDate, $endDate)  {

		$reportEnabled = env("REPORT_ENABLED", "true");
		if ($reportEnabled == false)  {

			echo("### Report disabled...");
			return;
		}

		$folder = storage_path("app/public/");
		$prefix = env("WHATSAPP_PREFIX", "");
		$sender = env("WHATSAPP_SENDER", "");
		$brandName = env("BRAND_NAME", "");

		//  Create CSV file
		$date = substr($endDate, 0, 4+1+2);
		$csvFilename = "offer_monthly_report_".$date.".csv";
		$csvFilePath = $folder.$csvFilename;

		$handle = fopen($csvFilePath, "w");
		if ($handle == false)  {

			echo("### Unable to create monthly report CSV $csvFilename...");
			return;
		}

		//  Save UTF-8 header
		$headerArray = array(
			"Create Time",
			"Direction",
			"Sent Time",
			"Status",
			"From",
			"To",
			"Offer ID",
			"Message",
			"Message Type",
			"Message ID",
		);

		$totalRows = 0;
		$rowPerPage = 500;
		fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($handle, $headerArray);

		//----------------------------------------------------------------------------------------
		//  Export inbound CSV
		echo("\nProcessing inbound...");
		$skipRows = 0;
		$loopCount = 100;
		while ($loopCount-- > 0)  {

			$array = WhatsappWebhook::getListWithPaging($startDate, $endDate, "message", $skipRows, $rowPerPage);
			$count = count($array);
			if ($count == 0)  {break;}

			$skipRows += $count;
			foreach ($array as $row)  {

				$totalRows++;

				$fromMobile = "";
				$message = "";
				$status = "";

				//  Extract JSON
				// 	"SmsMessageSid":"SM0df7c80c214295415c937e93c7adffd5",
				// 	"NumMedia":"0",
				// 	"SmsSid":"SM0df7c80c214295415c937e93c7adffd5",
				// 	"SmsStatus":"received",
				// 	"Body":"UAT: \\u6211\\u60f3\\u9818\\u53d6 L\\u2019Occitane \\u9ad4\\u9a57\\u88dd\\uff08\\u63db\\u9818\\u7de8\\u78bc\\uff1aGAj8Sw5m\\uff09\\u7684\\u63db\\u9818\\u9023\\u7d50\\uff01",
				// 	"To":"whatsapp:+85230016606",
				// 	"NumSegments":"1",
				// 	"MessageSid":"SM0df7c80c214295415c937e93c7adffd5",
				// 	"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
				// 	"From":"whatsapp:+85294129112",
				// 	"ApiVersion":"2010-04-01"

				// 	"MediaContentType0":"image\/jpeg",
				// 	"SmsMessageSid":"MM01ec25167795ebbcd3775d99588414d8",
				// 	"NumMedia":"1",
				// 	"SmsSid":"MM01ec25167795ebbcd3775d99588414d8",
				// 	"SmsStatus":"received",
				// 	"Body":null,
				// 	"To":"whatsapp:+85230016606",
				// 	"NumSegments":"1",
				// 	"MessageSid":"MM01ec25167795ebbcd3775d99588414d8",
				// 	"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
				// 	"From":"whatsapp:+85297231930",
				// 	"MediaUrl0":"https:\/\/api.twilio.com\/2010-04-01\/Accounts\/ACa8c4e3793f543cc3b4d68b112171edf1\/Messages\/MM01ec25167795ebbcd3775d99588414d8\/Media\/ME78c5cadf68433a270f0a8e9f31d47a6f",
				// 	"ApiVersion":"2010-04-01"
				$content = $row["content"];
				if (empty($content) == false)  {

					$json = json_decode($content, true);
					$status = ucfirst($json["SmsStatus"]);
					$from = $json["From"];
					$body = $json["Body"];

					//  Handling media body
					$mediaBody = "";
					$mediaCount = intval($json["NumMedia"]);
					for ($i=0; $i<$mediaCount; $i++)  {
						if (isset($json["MediaUrl".$i]))  {

							$mediaURL = $json["MediaUrl".$i];
							$mediaBody .= "\n$mediaURL";
						}
					}
					$message = $mediaBody.$body;

					//  Remove prefix
					$fromMobile = str_replace("whatsapp:", "", $from);
				}

				$rowArray = array(
					$row["created_at"],
					"Inbound",
					$row["created_at"],
					$status,
					$fromMobile,
					$sender,
					"-",
					$message,
					"User",
					$row["message_id"],
				);
				fputcsv($handle, $rowArray);
			}
		}
		echo($skipRows);

		//----------------------------------------------------------------------------------------
		//  Export outbound CSV
		echo("\nProcessing outbound...");
		$skipRows = 0;
		$loopCount = 100;
		while ($loopCount-- > 0)  {

			$array = CampaignWhatsappMessageQueue::getSentRecordsWithPaging($startDate." 00:00:00", $endDate." 23:59:59", 0, $skipRows, $rowPerPage);
			$count = count($array);
			if ($count == 0)  {break;}

			//  Export CSV content
			$skipRows += $count;
			foreach ($array as $row)  {

				$totalRows++;

				$rowArray = array(
					$row["created_at"],
					"Outbound",
					$row["send_at"],
					$row["status"],
					$sender,
					$row["mobile"],
					$row["offer_id"],
					$row["message"],
					$row["message_type"],
					$row["message_id"],
				);
				fputcsv($handle, $rowArray);
			}
		}
		echo($skipRows);

		//----------------------------------------------------------------------------------------
		//  Finally, output
		fclose($handle);

		echo("\nTotal: ");
		if ($totalRows <= 0)  {

			echo("### No record found...");
		}  else  {

			echo($totalRows);

			//  Send email notification
			try  {

				$body = "Dear all,<br>".
						"<br>".
						"Attached please find daily accumulate report CSV.  Thanks!<br>".
						"<br>".
						"$brandName WhatsApp Offer Project<br>".
						"<b>Fimmick Development Team</b><br>".
						__FUNCTION__;

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
				$mail->Subject = "=?UTF-8?B?".base64_encode("[$brandName] ".$prefix."$brandName Daily accumulate report $startDate ~ $endDate...")."?=";
				$mail->Body = $body;

				$mail->addBCC("developer1@fimmick.com");

				$environment = env("APP_ENV", "");
				if ($environment == "production")  {

					$mail->AddAddress("dp@kinnso.com", "Digital Parntership");
					$mail->AddAddress("allpm@kinnso.com", "Project Management");
				}

				//  Attachments
				$csvFileSize = filesize($csvFilePath);
				if ($csvFileSize > 262144)  {					// 256KB

					//  ZIP it
					$zip = new \ZipArchive();
					$zipFilePath = substr($csvFilePath, 0, -4).".zip";
					$result = $zip->open($zipFilePath, \ZipArchive::CREATE|\ZipArchive::OVERWRITE);
					if ($result == true)  {

						$zip->addFile($csvFilePath, $csvFilename);
						$zip->close();

						$csvFilePath = $zipFilePath;
						$csvFilename = substr($csvFilename, 0, -4).".zip";
					}
				}

				//  Add ttachment
				$mail->AddAttachment($csvFilePath, $csvFilename);

				$sendResult = $mail->Send();
				echo("\nResult: $sendResult");

			}  catch (Exception $e)  {
				echo("### Error sending email...".$_eol);
			}
		}
		echo("\n");
	}

	//----------------------------------------------------------------------------------------
	//  Assumes the files live in storage/app/
	//  Assumes the file naming convention is: prefix + time + .csv
	//
	//  CSV Filename: offer_1_daily_report_2020-07-15.csv
	//  CSV Filename: offer_monthly_report_2020-07.csv
	public function processCleanUpReport()  {

		$days = intval(env("REPORT_KEEP_DAYS", "7"));
		$folder = storage_path("app/public/");

		$prefixes = [
			"_daily_report_",
			"offer_monthly_report_",
			"_Whatsapp_Offer_Coupons",
		];
		$suffix = ".csv";

		$fileArray = new \DirectoryIterator($folder);
		foreach ($fileArray as $file)  {

			if ($file->isDot())  {continue;}

			$filename = $file->getFilename();
			$isCSV = strpos($filename, $suffix);
			if (!$isCSV)  {continue;}

			//  For each file, check if they contains the prefix
			foreach ($prefixes as $prefix)  {

				$pos = strpos($filename, $prefix);
				if ($pos === false)  {continue;}

				$startPos = $pos+strlen($prefix);
				$endPos = strlen($suffix);

				//  Get date string by removing leading and trailing characters
				$fileDateString = substr($filename, $startPos, 0-$endPos);

				$fileDate = new \DateTime($fileDateString);
				$diff = $fileDate->diff(new \DateTime("now"), true);
				$diff = intval($diff->format("%a"));

				echo $filename ."\t" .$diff ." days\n";
				if ($diff > $days)  {

					//  Delete it
					unlink($file->getPathname());
				}

				//  If match is found and processed, no need to try other prefixes
				break;
			}
		}
	}

	//----------------------------------------------------------------------------------------
	public function processCleanUpload()  {

		$days = intval(env("REPORT_KEEP_DAYS", "7"));
		$folder = storage_path("app/uploads/");
		$suffix = ".csv";

		$fileArray = new \DirectoryIterator($folder);
		foreach ($fileArray as $file)  {

			if ($file->isDot())  {continue;}

			$filename = $file->getFilename();
			$isCSV = strpos($filename, $suffix);
			if (!$isCSV)  {continue;}

			$filePath = $folder.$filename;
			$fileDate = filemtime($filePath);

			$diff = intval((time()-$fileDate)/(60*60*24));
			echo $filename ."\t" .$diff ." days\n";
			if ($diff > $days)  {

				//  Delete it
				unlink($file->getPathname());
			}
		}
	}
}
