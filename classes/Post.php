<?php
	class Post
	{
		public static function getById(int $userId, int $postId)
		{
			$data = DB::table('posts')->where('id', '=', $postId)->where('user_id', '=', $userId)->get()[0];

			return $data;
		}
		public static function getAll(): array
		{
			$data = DB::table('posts')->orderBy('posts.created_at DESC')->get();

			return $data;
		}

		public static function create(int $userId, string $title, string $content, string $thumbnail)
		{
			if(!self::postExists($title)) {
				$result = DB::table('posts')->create([
					'user_id' => $userId,
					'title' => $title,
					'text' => $content,
					'thumbnail' => $thumbnail,
				]);

				return $result;
			}

			return 'exists';
		}

		public static function delete(int $userId, int $postId): int
		{
			$result = DB::table('posts')->where('id', '=', $postId)->where('user_id', '=', $userId)->delete();

			return $result;
		}

		public static function edit(int $userId, int $postId, string $title, string $content, string $thumbnail): int
		{
			$totalPosts = DB::table('posts')->notWhere('id', '=', $postId)->where('title', '=', $title)->count();

			if($totalPosts == 0) {
				$result = DB::table('posts')->where('id', '=', $postId)->where('user_id', '=', $userId)->update([
					'title' => $title,
					'text' => $content,
					'thumbnail' => $thumbnail,
				]);

				return $result;
			}
			
			return 'exists';
		}

		public static function changeStatus(int $userId, int $postId, string $status): int
		{
			$lastUpdate = DB::table('posts')->getById($postId)['updated_at'];

			$result = DB::table('posts')->where('id', '=', $postId)->where('user_id', '=', $userId)->update([
				'status' => $status,
				'updated_at' => $lastUpdate,
			]);

			return $result;
		}

		private function postExists(string $title): bool
		{
			if(DB::table('posts')->where('title', '=', $title)->count() > 0) {
				return true;
			}
			
			return false;
		}
	}
