<?php

class srcds
{
	// Configuration
	private $host = '127.0.0.1';
	private $port = 27015;
	private $timeout = 2;

	const PACKET_SIZE = 1248;

	// Server query strings
	// http://developer.valvesoftware.com/wiki/Server_queries
	const CMD_INFO						= "\xFF\xFF\xFF\xFFTSource Engine Query\0"; // Get the server info
	const CMD_SERVERQUERY_GETCHALLENGE	= "\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF"; // Get a challenge key
	const CMD_PLAYER					= "\xFF\xFF\xFF\xFF\x55"; // Get the player list

	function __construct($host = '127.0.0.1', $port = 27015, $timeout = 2)
	{
		$this->host = $host;
		$this->port = $port;
		$this->timeout = $timeout;
	}

	public function ping()
	{
		// Open a socket to the server and set the timeout
		$socket = fsockopen('udp://'.$this->host, $this->port, $err_num, $err_str, $this->timeout);

		// Send a command and read a packet, get time it taken.
		$start_time = microtime(TRUE);
		fwrite($socket, self::CMD_INFO);
		$response = fread($socket, self::PACKET_SIZE);
		$end_time = microtime(TRUE);
		fclose($socket);

		// Calculate ping in ms or timeouted
		if (empty($response))
			return false;
		else
			$ping = number_format(($end_time - $start_time) * 1000, 2);
		return $ping;
	}

	public function getInfos()
	{
		// Open a socket to the server, send the command, and get the data.
		$socket = fsockopen('udp://'.$this->host, $this->port, $err_num, $err_str, $this->timeout);
		fwrite($socket, self::CMD_INFO);
		$response = fread($socket, self::PACKET_SIZE);
		$response = substr($response, 6);
		fclose($socket);

		// Set vars and return, or just return
		if (!empty($response))
		{
			$server = array();
			$server['hostname'] = $this->get_string($response);
			$server['map'] = $this->get_string($response);
			$server['game_dir'] = $this->get_string($response);
			$server['game']	= $this->get_string($response);
			$server['app_id'] = $this->get_short_unsigned($response);
			$server['players'] = $this->get_byte($response);
			$server['max_players'] = $this->get_byte($response);
			$server['bots'] = $this->get_byte($response);
			$server['dedicated'] = $this->get_char($response);
			$server['os'] = $this->get_char($response);
			$server['password'] = $this->get_byte($response);
			$server['secure'] = $this->get_byte($response);
			$server['version'] = $this->get_string($response);
			return $server;
		}
		return false;
	}

	public function getPlayers()
	{
		// Open a socket to the server and get challenge.
		$socket = fsockopen('udp://'.$this->host, $this->port, $err_num, $err_str, $this->timeout);
		fwrite($socket, self::CMD_SERVERQUERY_GETCHALLENGE);
		fread($socket, 5);
		$challenge = fread($socket, 4);

		// Send the command to get the player list and close the socket
		$command = self::CMD_PLAYER.$challenge;
		fwrite($socket, $command);
		$response = fread($socket, self::PACKET_SIZE);
		$response = substr($response, 6);
		fclose($socket);

		if (!empty($response))
		{
			$players = array();
			if (ord(substr($response, 0, 1)) === 0)
			{
				$id = 0;
				while($response !== false)
				{
					$this->get_byte($response);
					$players[$id]['name'] = $this->get_string($response);
					$players[$id]['kills'] = $this->get_long($response);
					$players[$id]['time'] = $this->get_float($response);
					$id++;
				}
			}
			return $players;
		}
		return false;
	}


	// These functions unpack the binary data
	// recieved from the server into usable strings/numbers
	private function get_char(&$string)
	{
		return chr($this->get_byte($string));
	}

	private function get_byte(&$string)
	{
		$data = substr($string, 0, 1);
		$string = substr($string, 1);
		$data = unpack('Cvalue', $data);

		return $data['value'];
	}

	private function get_short_unsigned(&$string)
	{
		$data = substr($string, 0, 2);
		$string = substr($string, 2);
		$data = unpack('nvalue', $data);

		return $data['value'];
	}

	private function get_short_signed(&$string)
	{
		$data = substr($string, 0, 2);
		$string = substr($string, 2);
		$data = unpack('svalue', $data);

		return $data['value'];
	}

	private function get_long(&$string)
	{
		$data = substr($string, 0, 4);
		$string = substr($string, 4);
		$data = unpack('Vvalue', $data);

		return $data['value'];
	}

	private function get_float(&$string)
	{
		$data = substr($string, 0, 4);
		$string = substr($string, 4);
		$array = unpack("fvalue", $data);

		return $array['value'];
	}

	private function get_string(&$string)
	{
		$data = "";
		$byte = substr($string, 0, 1);
		$string = substr($string, 1);

		while (ord($byte) != "0")
		{
			$data .= $byte;
			$byte = substr($string, 0, 1);
			$string = substr($string, 1);
		}
		return $data;
	}
}