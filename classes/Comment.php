<?php
	require_once 'User.php';

	class Comment
	{
		public static function getAll(int $postId)
		{
			$data = DB::table('comments')->where('post_id', '=', $postId)->orderBy('comments.created_at DESC')->get();

			return $data;
		}

		public static function delete(int $userId, int $commentId): int
		{
			$result = DB::table('comments')->where('id', '=', $commentId)->where('user_id', '=', $userId)->delete();

			return $result;
		}

		public static function create(int $userId, int $postId, string $text): int
		{
			$author = User::getById($userId)['name'];
      
      $result = DB::table('comments')->create([
        'post_id' => $postId,
        'user_id' => $userId,
        'author' => $author,
        'text' => $text,
      ]);

			return $result;
		}
	}
