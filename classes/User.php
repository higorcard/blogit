<?php
	class User
	{
		public static function get(string $email): array
		{
			$data = DB::table('users')->where('email', '=', $email)->get()[0];

			return $data;
		}
		public static function getById(int $id): array
		{
			$data = DB::table('users')->getById($id);

			return $data;
		}
		
		public static function create(string $name, string $email, string $password)
		{
			if(!self::userExists($email)) {
				$result = DB::table('users')->create([
					'name' => $name,
					'email' => $email,
					'password' => $password,
				]);
			}

			return $result;
		}

		private function userExists(string $email): bool
		{
			if(DB::table('users')->where('email', '=', $email)->count() > 0) {
				return true;
			}
			
			return false;
		}
	}
