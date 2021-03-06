#!/opt/lampp/bin/php
<?php
error_reporting (E_ALL ^ E_NOTICE);
require("../includes/config.inc.php"); 
require("../includes/Database.class.php"); 
require("../includes/GPSFunction.php"); 
require("../includes/cronMailSMTP.php");
require("../includes/cronSMS.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

function gpspathAll($path1)
{
	$file1 = @fopen($path1, "r");
	if($file1)
	{
		while(!@feof($file1))
		{
			$data= @fgets($file1);				 
		}
		$data = getSortedData($data);
		return $data;
	}
	else
	{
		return 0;
	
	}
	@fclose($file1);
} 

$sql = "select * from tb_device_alert_info,tb_clientinfo,tb_deviceinfo,tb_client_subscription where tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND tdai_alertBy = 'Meter' AND di_id = tdai_deviceId AND ci_id = tdai_clientId AND tdai_active = 1 AND tdai_status = 0 order by tdai_id ASC";
$rows = $db->query($sql);


if($db->affected_rows > 0)
{
	$date_offline = date('d-m-Y');
	$i = 0;
	while ($record = $db->fetch_array($rows)) 
	{
		//print_r($record);
		if($record[di_deviceName])
			$devName = $record[di_deviceName];
		else
			$devName = $record[di_deviceId];
		$devImage = $record[di_deviceImg];
		
		$renewDate = date("d-m-Y",strtotime("-1 days ".($record[tcs_noOfMonths]) ."months ".$record[tcs_renewalDateFrom]));
		if(strtotime($date_offline) <= strtotime($renewDate))
		{
			 
		$sdate = date("d-m-Y",strtotime($record[di_createDate]));
		$edate = date("d-m-Y");
		
		$z = GetDays($sdate, $edate);
		for($y=0; $y<count($z); $y++)
		{
			$getDateReading = "SELECT * FROM tb_speed_meter_info WHERE tmsi_clientId = ".$record[di_clientId]." AND tmsi_imei = '".$record[di_imeiId]."' AND tmsi_readDate = '".date('Y-m-d',strtotime($z[$y]))."'";
			$resDateReading = $db->query($getDateReading);

			if($db->affected_rows == 0)
			{
				$path=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($z[$y]))."/".$record[di_imeiId].".txt";
				$km = funKMPerDayofDevice($path);
				$kmdata['tmsi_clientId'] = $record[di_clientId];
				$kmdata['tmsi_imei'] = $record[di_imeiId];
				$kmdata['tmsi_readDate'] = date('Y-m-d',strtotime($z[$y]));
				$kmdata['tmsi_kmpd'] = $km;
				
				//print_r($kmdata);
				//exit;
				$db->query_insert("tb_speed_meter_info", $kmdata);
			}
			else
			{
				$sToTime = strtotime($z[$y]);
				$eToTime = strtotime(date("d-m-Y"));
				if($sToTime == $eToTime )
				{
					$fetDateReading = $db->fetch_array($resDateReading);
					//print_r($fetDateReading);
					$path=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($z[$y]))."/".$record[di_imeiId].".txt";
					$km = explode(",",funKMPerDayofDevice($path));
					//print_r($km);
					$kmdata['tmsi_clientId'] = $record[di_clientId];
					$kmdata['tmsi_imei'] = $record[di_imeiId];
					$kmdata['tmsi_readDate'] = date('Y-m-d',strtotime($z[$y]));
					$kmdata['tmsi_kmpd'] = $km[0];
					
					//print_r($kmdata);
					//exit;
					$db->query_update("tb_speed_meter_info", $kmdata, "tsmi_id = ".$fetDateReading[tsmi_id]);
				}
			}
			
			//echo "<br><br>";
		}
		//print_r($record);
		
		$getReading = "SELECT SUM(tmsi_kmpd) as dist FROM tb_speed_meter_info WHERE tmsi_clientId = ".$record[di_clientId]." AND tmsi_imei = '".$record[di_imeiId]."' group by tmsi_imei";
		$resReading = $db->query($getReading);
		if($db->affected_rows > 0 )
		{
		
			$fetReading = mysql_fetch_assoc($resReading);
			$odoMeter = $record[di_odoMeter] + $fetReading[dist];
		}
		else
		{
			$odoMeter = $record[di_odoMeter];
		}
		//echo ((strtotime(date("H:i"))-strtotime(date("H:i",strtotime($km[4]))))/60)." ".date("H:i")." ".date("H:i",strtotime($km[4]))."<br>";
		if(((strtotime(date("H:i"))-strtotime(date("H:i",strtotime($km[4]))))/60) < 5)
			$meterArr = ucfirst($record[ci_clientName]).",".$devName.",".$odoMeter.",".$renewDate.",".$km[1].",".$km[2].",".$km[3].",".$km[4].",1".",".$devImage;
		else
			$meterArr = ucfirst($record[ci_clientName]).",".$devName.",".$odoMeter.",".$renewDate.",".$km[1].",".$km[2].",".$km[3].",".$km[4].",2".",".$devImage;
		}
		if(count($meterArr)>0)
		{
			$meterArr1 = explode(",",$meterArr);
			//print_r($meterArr1);
			//echo ($record[tdai_alertSrc] - $meterArr1[2]);
			
			$getReseller = "select * from tb_clientinfo where ci_id = ".$record[ci_clientId];
			$resReseller = mysql_query($getReseller);
			$fetReseller = @mysql_fetch_assoc($resReseller);
			
			if($record[tdai_alertSrc] == $meterArr1[2] || (($record[tdai_alertSrc] - $meterArr1[2]) > 0 && ($record[tdai_alertSrc] - $meterArr1[2]) <= 3)) 
			{
				if($record[tdai_alertType] == "Email") 
				{
					
					$t = $record[tdai_source];
					$sub = "Alert - ".strip_tags(ucfirst($record[tdai_purpose]))." for ".$fetReseller[ci_clientName];
					$fr = $fetReseller[ci_clientName];				
					
					$message = '<html><body>';
					$message .= "<b>Dear ".ucfirst($record[ci_clientName])."! </b><br><br>";
					$message .= '<table style="border-color: #666;" cellpadding="10" width="100%">';
					$message .= "<tr style='background: #eee;'><td><strong>Greetings from:</strong> </td><td>" . strip_tags($fetReseller[ci_clientName]) . "</td></tr>";
					$message .= "<tr><td><strong>Vehicle Name:</strong> </td><td>" . strip_tags($devName) . "</td></tr>";
					$message .= "<tr><td><strong>Purpose:</strong> </td><td>" . strip_tags(ucfirst($record[tdai_purpose])) . "</td></tr>";
					$message .= "<tr><td><strong>Description:</strong> </td><td>" . strip_tags(ucfirst($record[tdai_description])) . "</td></tr>";
					$message .= "<tr><td><strong>By -:</strong> </td><td>" . $fetReseller[ci_weburl] . "</td></tr>";
					$message .= '<tr><td></td><td><img src="'.$GLOBALS[dataPath].'gpsapp/modules/user/client_logo/'.$fetReseller[ci_clientLogo].'" alt="Website Change Request" /></td></tr>';						
					$message .= "<tr><td><strong>Note:</strong> </td><td style='font:12px normal Arial, Helvetica, sans-serif; color:red'>Do not reply to this system generated mail</td></tr>";
					$message .= "</table>";
					$message .= "</body></html>";
					
					//echo $message;
					//exit;
					if($mailres = sendMail($t,$sub,$message,$fr))
					{
						
						$data['tdai_status'] = 1;
						$data['tdai_deliveryTime'] = date("Y-m-d H:i:s");
						if($db->query_update("tb_device_alert_info", $data , "tdai_id=".$record[tdai_id]))
						{
							$maildata['tmi_email'] = $t;
							$maildata['tmi_tgai_id'] = $record[tdai_id];
							$maildata['tmi_mailResult'] = $mailres;
							$maildata['tmi_message'] = $message;
							$maildata['tmi_mailType'] = "DATEALERT";		
							//print_r($maildata);		
							//exit;
							if($db->query_insert("tb_mail_info", $maildata))
								echo "done";
							else echo "no";
						}
					}
				}// end if alert type
				elseif($record[tdai_alertType] == "Mobile") 
				{
					$from = "";
					$to = $record[tdai_source];
					$msg = "Dear ".ucfirst($record[ci_clientName])."! ".$devName." has ".$record[tdai_purpose].". pls log in for desc- ".$record[ci_weburl];
					//echo $msg;
					//exit;			
					if($smsres = sendSMS($from,$to,$msg))
					{
						
						$data['tdai_status'] = 1;
						$data['tdai_deliveryTime'] = date("Y-m-d H:i:s");
						//print_r($data);
						//exit;
						if($db->query_update("tb_device_alert_info", $data , "tdai_id=".$record[tdai_id]))
						{
							$smsdata['tsi_mobileno'] = $to;
							$smsdata['tsi_tgai_id'] = $record[tdai_id];
							$smsdata['tsi_smsResult'] = $smsres;
							$smsdata['tsi_message'] = urlencode($msg);
							$smsdata['tsi_smsType'] = "DATEALERT";		
							//print_r($smsdata);		
							//exit;
							if($db->query_insert("tb_smsinfo", $smsdata))
								echo "done";
							else echo "no";
						}
					}
				}		// end else alert type
			}
			
			
		}
		else
		{
			$meterArr1 = "";	
		}
		//echo $meterArr1;
		
	}
}
?>