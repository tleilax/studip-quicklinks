<?php
class Quicklink {
	public static function LoadAll($user_id) {
		$statement = DBManager::get()->prepare("SELECT `link_id`, `link`, `title`, `timestamp`, `position` FROM `quicklinks` WHERE `user_id` = ? ORDER BY `position` ASC, `title` ASC");
		$statement->execute(array($user_id));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function Load($user_id, $link_id) {
		$statement = DBManager::get()->prepare("SELECT `link_id`, `link`, `title`, `timestamp`, `position` FROM `quicklinks` WHERE `user_id` = ? AND `link_id` = ?");
		$statement->execute(array($user_id, $link_id));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}
	
	public static function Save($user_id, $link_id, $link, $title = '', $position = null) {
		if (empty($position)) {
			$position = self::GetMaxPosition($user_id);
		}
		
		$statement = DBManager::get()->prepare("INSERT INTO `quicklinks` (`user_id`, `link_id`, `link`, `title`, `timestamp`, `position`) VALUES (?, ?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE `link` = VALUES(`link`), `title` = VALUES(`title`), `position` = VALUES(`position`), `modified` = NOW()");
		$statement->execute(array($user_id, $link_id, $link, $title, $position));
		if (!$link_id) {
			$link_id = DBManager::get()->lastInsertId();
		}
		return $link_id;
	}
	
	public static function Delete($user_id, $link_id) {
		$statement = DBManager::get()->prepare("DELETE FROM `quicklinks` WHERE `user_id` = ? AND `link_id` = ?");
		$statement->execute(array($user_id, $link_id));
	}
	
	private static function GetMaxPosition($user_id) {
		$statement = DBManager::get()->prepare("SELECT 0 + MAX(`position`) FROM `quicklinks` WHERE `user_id` = ?");
		$statement->execute(array($user_id));
		return $statement->fetchColumn() + 1;
	}
	
	public static function FindLink($user_id, $link) {
		$statement = DBManager::get()->prepare("SELECT `link_id` FROM `quicklinks` WHERE `user_id` = ? AND `link` = ?");
		$statement->execute(array($user_id, $link));
		return $statement->fetchColumn();
	}
	
	public static function Move($user_id, $id, $direction) {
		$u = self::Load($user_id, $id);
		
		$sql = ($direction === 'up')
			? "AND `position` < ? ORDER BY `position` DESC"
			: "AND `position` > ? ORDER BY `position` ASC";

		$statement = DBManager::get()->prepare("SELECT `link_id` FROM `quicklinks` WHERE `user_id` = ? {$sql} LIMIT 1");
		$statement->execute(array($user_id, $u['position']));
		$v_id = $statement->fetch(PDO::FETCH_ASSOC);
		$v = self::Load($user_id, $v_id);
		
		$statement = DBManager::get()->prepare("UPDATE `quicklinks` SET `position` = ? - `position`, `modified` = NOW() WHERE `user_id` = ? AND `link_id` IN (?, ?)");
		$statement->execute(array($u['position'] + $v['position'], $user_id, $u['link_id'], $v['link_id']));

		return true;
	}
}