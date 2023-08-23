<?php
	class DB
	{
		private $connection;
		private $table;
		private $columns;
		private $where;
		private $orderBy;
		private $limit;
		private $data;

		public function __construct($conn)
		{
			$this->connection = $conn;
			$this->columns = '*';
			$this->where = [];
			$this->orderBy = '';
			$this->limit = '';
			$this->data = [];
		}

		public function table($tableName)
		{
			$this->table = $tableName;

			return $this;
		}
		public function columns($columns)
		{
			$this->columns = $columns;

			return $this;
		}
		public function where($column, $operator, $value)
		{
			$whereArray = ['condition' => "$column $operator :$column", $column => $value];
			
			array_push($this->where, $whereArray);

			return $this;
		}
		public function orWhere($column, $operator, $value)
		{
			$whereArray = ['operator' => ' OR ', 'condition' => "$column $operator :$column", $column => $value];
			
			array_push($this->where, $whereArray);

			return $this;
		}
		public function notWhere($column, $operator, $value)
		{
			$whereArray = ['operator' => ' NOT ', 'condition' => "$column $operator :$column", $column => $value];
			
			array_push($this->where, $whereArray);

			return $this;
		}
		public function orderBy($order)
		{
			$this->orderBy = 'ORDER BY ' . $order;

			return $this;
		}
		public function limit($limit)
		{
			$this->limit = 'LIMIT ' . $limit;

			return $this;
		}

		public function get()
		{
			$condition = $this->getCondition($this->where);
			$query = "SELECT $this->columns FROM $this->table WHERE $condition $this->orderBy $this->limit";

			$sql = $this->execute($query);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
		public function getById($id)
		{
			$this->data = ['id' => $id];
			
			$query = "SELECT $this->columns FROM $this->table WHERE id = :id";

			$sql = $this->execute($query);

			return $sql->fetch(PDO::FETCH_ASSOC);
		}
		public function delete()
		{
			$condition = $this->getCondition($this->where);
			$query = "DELETE FROM $this->table WHERE $condition";

			$sql = $this->execute($query);

			return $sql->rowCount();
		}
		public function update($data)
		{
			$this->data = $data;

			$changes = implode(', ', array_map(function($key) { return $key . ' = :' . $key; }, array_keys($data)));
			$condition = $this->getCondition($this->where);

			$query = "UPDATE $this->table SET $changes WHERE $condition";

			$sql = $this->execute($query);

			return $sql->rowCount();
		}
		public function create($data)
		{
			$this->data = $data;

			$columns = implode(', ', array_keys($data));
			$values = implode(', ', array_map(function($key) { return ':' . $key; }, array_keys($data)));

			$query = "INSERT INTO $this->table ($columns) VALUES ($values)";

			$sql = $this->execute($query);

			return $sql->rowCount();
		}
		public function join($table1, $table2, $column1, $column2)
		{
			$condition = $this->getCondition($this->where);
			$query = "SELECT $this->columns FROM $table1 INNER JOIN $table2 ON $table1.$column1 = $table2.$column2 WHERE $condition $this->orderBy $this->limit";

			$sql = $this->execute($query);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
		public function count()
		{
			$condition = $this->getCondition($this->where);
			$query = "SELECT COUNT(*) count FROM $this->table WHERE $condition";

			$sql = $this->execute($query);

			return $sql->fetch(PDO::FETCH_ASSOC)['count'];
		}
		public function raw($query, $data = [])
		{
			$this->data = $data;

			return $this->execute($query);
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
		private function resetAttributes()
		{
			$this->table = '';
			$this->columns = '';
			$this->where = [];
			$this->orderBy = '';
			$this->limit = '';
			$this->data = [];
		}
		private function execute($query)
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

			$this->resetAttributes();

			return $sql;
		}
	}
