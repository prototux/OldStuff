<?php
	class ts3
	{
		private $host;
		private $port;
		private $sid;
		private $login;
		private $password;
		private $socket = null;
		private $userDatas = array();
		private $timeout;

		public function __construct($host = '127.0.0.1', $queryPort = 10011, $serverId = 1, $login = null, $password = null, $timeout = 2)
		{
			$this->host = $host;
			$this->port = $queryPort;
			$this->sid = $serverId;
			$this->login = $login;
			$this->password = $password;
			$this->timeout = $timeout;
		}

		private function unescape($str, $reverse = false)
		{
			$find = array('\\\\',   "\/",     "\s",     "\p",     "\a",   "\b",   "\f",     "\n",     "\r",   "\t",   "\v");
			$rplc = array(chr(92),  chr(47),  chr(32),  chr(124), chr(7), chr(8), chr(12),  chr(10),  chr(3), chr(9), chr(11));
			if(!$reverse)
				return str_replace($find, $rplc, $str);
			return str_replace($rplc, $find, $str);
		}

		private function parseLine($rawLine)
		{
			$datas = array();
			$rawItems = explode("|", $rawLine);
			foreach ($rawItems as $rawItem)
			{
				$rawDatas = explode(" ", $rawItem);
				$tempDatas = array();
				foreach($rawDatas as $rawData)
				{
					$ar = explode("=", $rawData, 2);
					$tempDatas[$ar[0]] = isset($ar[1]) ? $this->unescape($ar[1]) : "";
				}
				$datas[] = $tempDatas;
			}
			return $datas;
		}

		private function sendCommand($cmd)
		{
			fputs($this->socket, "$cmd\n");
			$response = "";
			do
			{
				$response .= fread($this->socket, 8096);
			}while(strpos($response, 'error id=') === false);

			if(strpos($response, "error id=0") === false)
				return false;
			return $response;
		}

		private function queryServer()
		{
			$this->socket = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
			if($this->socket)
			{
				socket_set_timeout($this->socket, $this->timeout);
				$isTs3 = trim(fgets($this->socket)) == "TS3";
				if(!$isTs3)
					return false;

				if($this->login !== false)
					if (!$this->sendCommand("login client_login_name=" . $this->login . " client_login_password=" . $this->password))
						return 0;
				$response = "";
				$response .= $this->sendCommand("use sid=".$this->sid);
				$response .= $this->sendCommand("serverinfo");
				$response .= $this->sendCommand("channellist -topic -flags -voice -limits");
				$response .= $this->sendCommand("clientlist -uid -away -voice -groups");
				$response .= $this->sendCommand("servergrouplist");
				$response .= $this->sendCommand("channelgrouplist");
				fputs($this->socket, "quit\n");
				fclose($this->socket);
				return $response;
			}
			return false;
		}

		private function sortUsers($a, $b)
		{
			return strcasecmp($a["client_nickname"], $b["client_nickname"]);
		}

		private function update()
		{
			$response = $this->queryServer();
			if (!$response)
				return false;

			$lines = explode("error id=0 msg=ok\n\r", $response);
			if(count($lines) == 7)
			{
				$this->userDatas = $this->parseLine($lines[3]);
				usort($this->userDatas, array($this, "sortUsers"));
			}
			else
				return false;
			return true;
		}

		public function getUsers()
		{
			if(!$this->update())
				return false;

			$i = 0;
			if (!empty($this->userDatas))
				foreach($this->userDatas as $user)
				{
					if ($user["client_type"] == 0)
					{
						$users[$i]['name'] = $user['client_nickname'];

						if ($user["client_output_muted"] || !$user["client_output_hardware"])
							$users[$i]['status'] = '<span class="label label-inverse">Pas de son</span>';
						else if ($user["client_input_muted"] || !$user["client_input_hardware"])
							$users[$i]['status'] = '<span class="label label-warning">Muet</span>';
						else if ($user["client_away"])
							$users[$i]['status'] = '<span class="label label-info">Absent</span>';
						else if ($user["client_is_recording"])
							$users[$i]['status'] = '<span class="label label-important">En enrengistrement</span>';
						else
							$users[$i]['status'] = '<span class="label label-success">Disponible</span>';
						$i++;
					}
				}
			else
				return false;
			return $users;
		}
	}
?>
