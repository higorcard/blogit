<?php
	abstract class Connection
	{
		private $dbname;
		private $host;
		private $username;
		private $password;
		protected $connection;

		protected function __construct()
		{
			$this->dbname = 'blogit';
			$this->host = 'mysql';
			$this->username = 'root';
			$this->password = 'db_password';

			$this->connection = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->username, $this->password);
		}
	}
