<?php
	require_once 'Connection.php';

	class DB extends Connection
	{
		private $table;
		private $columns;
		private $where;
		private $orderBy;
		private $limit;
		private $data;

		public function __construct()
		{
			Connection::__construct();

			$this->columns = '*';
			$this->where = [];
			$this->orderBy = '';
			$this->limit = '';
			$this->data = [];
		}

		public static function table(string $tableName): object
		{
			$instance = new self();
			$instance->table = $tableName;

			return $instance;
		}
		public function columns(string $columns): object
		{
			$this->columns = $columns;

			return $this;
		}
		public function where(string $column, string $operator, $value): object
		{
			$whereArray = ['condition' => "$column $operator :$column", $column => $value];
			
			array_push($this->where, $whereArray);

			return $this;
		}
		public function orWhere(string $column, string $operator, $value): object
		{
			$whereArray = ['operator' => ' OR ', 'condition' => "$column $operator :$column", $column => $value];
			
			array_push($this->where, $whereArray);

			return $this;
		}
		public function notWhere(string $column, string $operator, $value): object
		{
			$whereArray = ['operator' => ' NOT ', 'condition' => "$column $operator :$column", $column => $value];
			
			array_push($this->where, $whereArray);

			return $this;
		}
		public function orderBy(string $order): object
		{
			$this->orderBy = 'ORDER BY ' . $order;

			return $this;
		}
		public function limit($limit): object
		{
			$this->limit = 'LIMIT ' . $limit;

			return $this;
		}

		public function get(): array
		{
			$condition = $this->getCondition($this->where);
			$query = "SELECT $this->columns FROM $this->table WHERE $condition $this->orderBy $this->limit";

			$sql = $this->execute($query);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
		public function getById(int $id): array
		{
			$this->data = ['id' => $id];
			
			$query = "SELECT $this->columns FROM $this->table WHERE id = :id";

			$sql = $this->execute($query);

			return $sql->fetch(PDO::FETCH_ASSOC);
		}
		public function delete(): int
		{
			$condition = $this->getCondition($this->where);
			$query = "DELETE FROM $this->table WHERE $condition";

			$sql = $this->execute($query);

			return $sql->rowCount();
		}
		public function update(array $data): int
		{
			$this->data = $data;

			$changes = implode(', ', array_map(function($key) { return $key . ' = :' . $key; }, array_keys($data)));
			$condition = $this->getCondition($this->where);

			$query = "UPDATE $this->table SET $changes WHERE $condition";

			$sql = $this->execute($query);

			return $sql->rowCount();
		}
		public function create(array $data): int
		{
			$this->data = $data;

			$columns = implode(', ', array_keys($data));
			$values = implode(', ', array_map(function($key) { return ':' . $key; }, array_keys($data)));

			$query = "INSERT INTO $this->table ($columns) VALUES ($values)";

			$this->execute($query);

			return $this->connection->lastInsertId();
		}
		public function join(string $table1, string $table2, string $column1, string $column2): array
		{
			$condition = $this->getCondition($this->where);
			$query = "SELECT $this->columns FROM $table1 INNER JOIN $table2 ON $table1.$column1 = $table2.$column2 WHERE $condition $this->orderBy $this->limit";

			$sql = $this->execute($query);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
		public function count(): int
		{
			$condition = $this->getCondition($this->where);
			$query = "SELECT COUNT(*) count FROM $this->table WHERE $condition";

			$sql = $this->execute($query);

			return $sql->fetch(PDO::FETCH_ASSOC)['count'];
		}
		public static function raw(string $query, array $data = []): object
		{
			$instance = new self();
			$instance->data = $data;

			return $instance->execute($query);
		}

		private function getCondition()
		{
			if($this->where) {
				$conditionString = '';

				for($i = 0; $i < count($this->where); $i++) {
					$conditionArray = $this->where[$i];
					
					if($i > 0) {
						$operator = ' AND ';
					} else {
						$operator = '';
					}
					
					$operator = $conditionArray['operator'] ?? $operator;

					$conditionString .= $operator . $conditionArray['condition'];
				}

				return $conditionString;
			}
			
			return 1;
		}
		private function execute(string $query): object
		{
			$sql = $this->connection->prepare($query);
			for($i = 0; $i < count($this->where); $i++) {
				end($this->where[$i]);

				$key = key($this->where[$i]);
				$value = current($this->where[$i]);

				$sql->bindValue(':'.$key, $value);
			}
			if($this->data) {
				foreach($this->data as $key => $value) {
					$sql->bindValue(':'.$key, $value);
				} 
			}
			$sql->execute();

			return $sql;
		}
	}
