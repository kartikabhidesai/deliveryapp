<?php
	
	Class Notification
	{
		private $token; 
		private $payload 	 	= array();
		private $apns_port 		= 2195;
		private $apns_certificate = "";
		private $apns_url		 = "";
		private $development	 = true;
		private $time_out_sec	 = 60;
		private $apns			 = "";
		private $apns_message	 = "";
		private $getStatus		 = false;
		private $notification_records = array();
		private $notification_type;
		private $sound='default';
		private $sender_type;
		public function __construct($flag,$notification_type,$vendor_id,$last_action_ids,$customer_id)
		{
			$this->notification_type = $notification_type;
			$this->sender_type  	 = $flag;
			$dbObjects_notification  = new dbfunction();
			$dbObjects_notification->SimpleSelectQuery("CALL insert_notification('$flag','$notification_type','$vendor_id',@last_notification_id,@badge_count,@review_badge_count,@service_badge_count,@upgrade_badge_count,@downgrade_badge_count,@free_badge_count,'$last_action_ids',@cus_last_notification_id,'$customer_id')");
			$dbObjects_notification->SimpleSelectQuery("SELECT @last_notification_id AS last_notification_id,@badge_count AS badge_count,@review_badge_count AS review_badge_count,@service_badge_count AS service_badge_count,@cus_last_notification_id AS cus_last_notification_id");
			$this->notification_records = $dbObjects_notification->getFetchArray();
		}
		public function getNotificationRecords($token,$message)
		{
			$this->token						= $token;
			$this->payload['aps']['alert'] 		= $message;
			$this->payload['aps']['sound'] 		= $this->sound;
			$this->payload['aps']['notiType'] 	= intval($this->notification_type);
			$this->payload['aps']['lastId'] 	= $this->sender_type=="1"?(intval($this->notification_records['last_notification_id'])):(intval($this->notification_records['cus_last_notification_id']));
			$this->payload['aps']['badge']		= intval($this->notification_records['badge_count']);
			$this->payload['aps']['rev'] 		= intval($this->notification_records['review_badge_count']);
			$this->payload['aps']['ser'] 		= intval($this->notification_records['service_badge_count']);
			$this->payload['aps']['upg'] 		= intval($this->notification_records['upgrade_badge_count']);
			$this->payload['aps']['dow'] 		= intval($this->notification_records['downgrade_badge_count']);
			$this->payload['aps']['free'] 		= intval($this->notification_records['free_badge_count']);
			$this->apns_certificate				= $this->sender_type==2?"CustomerPem.pem":"VendorPem.pem";
			//for distribution
			// $this->apns_certificate				= $this->sender_type==2?"CustomerPem.pem":"CertificateName.pem";
			$this->apns_url						= $this->development==true?"gateway.sandbox.push.apple.com":"gateway.push.apple.com";
		}
		public function setNotificationStatus()
		{
			$this->payload 		= json_encode($this->payload);
			$stream_context = stream_context_create();
			stream_context_set_option($stream_context, 'ssl','local_cert',$this->apns_certificate);
			$this->apns = stream_socket_client('ssl://'.$this->apns_url.':'.$this->apns_port,$error,$error_string,$this->time_out_sec,STREAM_CLIENT_CONNECT,$stream_context);
			$this->apns_message = chr(0).chr(0).chr(32).pack('H*',str_replace('    ','',$this->token)).chr(0).chr(strlen($this->payload)).$this->payload;
		}
		public function getNotificationStatus()
		{
			$result = fwrite($this->apns,$this->apns_message);
			if(!$result)
			{
				$this->getStatus = false;
			}
			else
			{
				$this->getStatus = true;
			}
			return $this->getStatus;
		}
		public function __destruct()
		{
			@socket_close($this->apns);
			@fclose($this->apns);
		}
	}
	
?>