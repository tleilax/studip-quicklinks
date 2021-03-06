<?php
class Quicklink
{
    static function GetInstance($user_id)
    {
        return new self($user_id);
    }

    private $user_id;

    private function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    function loadAll()
    {
        $query = "SELECT `link_id`, `link`, `title`, `timestamp`, `position`
                  FROM `quicklinks`
                  WHERE `user_id` = :user_id
                  ORDER BY `position` ASC, `title` ASC";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':user_id', $this->user_id);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function loadAllIds()
    {
        $query = "SELECT `link_id`
                  FROM `quicklinks`
                  WHERE `user_id` = :user_id
                  ORDER BY `position` ASC, `title` ASC";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':user_id', $this->user_id);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    function load($link_id)
    {
        $query = "SELECT `link_id`, `link`, `title`, `timestamp`, `position`
                  FROM `quicklinks`
                  WHERE `user_id` = :user_id AND `link_id` = :link_id";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':user_id', $this->user_id);
        $statement->bindValue(':link_id', $link_id);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function store($link_id, $link, $title = '', $position = null)
    {
        if (empty($position)) {
            $position = $this->getMaxPosition();
        }

        $query = "INSERT INTO `quicklinks` (`user_id`, `link_id`, `link`, `title`, `timestamp`, `position`)
                  VALUES (?, ?, ?, ?, NOW(), ?)
                  ON DUPLICATE KEY UPDATE `link` = VALUES(`link`), `title` = VALUES(`title`), `position` = VALUES(`position`),
                  `modified` = NOW()";
        $statement = DBManager::get()->prepare($query);
        // ->
        $statement->execute(array($this->user_id, $link_id, $link, $title, $position));
        if (!$link_id) {
            $link_id = DBManager::get()->lastInsertId();
        }
        return $link_id;
    }

    function remove($link_id)
    {
        $query = "DELETE FROM `quicklinks`
                  WHERE `user_id` = :user_id AND `link_id` = :link_id";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':user_id', $this->user_id);
        $statement->bindValue(':link_id', $link_id);
        $statement->execute();

        return $statement->rowCount() == 1;
    }

    private function getMaxPosition()
    {
        $query = "SELECT 0 + MAX(`position`)
                  FROM `quicklinks`
                  WHERE `user_id` = :user_id";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':user_id', $this->user_id);
        $statement->execute();

        return $statement->fetchColumn() + 1;
    }

    function findLink($link)
    {
        $query = "SELECT `link_id`
                  FROM `quicklinks`
                  WHERE `user_id` = :user_id AND `link` = :link";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':user_id', $this->user_id);
        $statement->bindValue(':link', $link);
        $statement->execute();

        return $statement->fetchColumn();
    }

    function move($id, $direction)
    {
        $u = $this->load($id);

        $sql = ($direction === 'up')
             ? "AND `position` < :position ORDER BY `position` DESC"
             : "AND `position` > :position ORDER BY `position` ASC";

        $query = "SELECT `link_id`
                  FROM `quicklinks`
                  WHERE `user_id` = :user_id {$sql}
                  LIMIT 1";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':user_id', $this->user_id);
        $statement->bindValue(':position', $u['position']);
        $statement->execute();
        $v_id = $statement->fetchColumn();

        $v = $this->load($v_id);

        $query = "UPDATE `quicklinks`
                  SET `position` = :position - `position`, `modified` = NOW()
                  WHERE `user_id` = :user_id AND `link_id` IN (:link_id0, :link_id1)";
        $statement = DBManager::get()->prepare($query);
        $statement->bindValue(':position', $u['position'] + $v['position']);
        $statement->bindValue(':user_id', $this->user_id);
        $statement->bindValue(':link_id0', $u['link_id']);
        $statement->bindValue(':link_id1', $v['link_id']);
        $statement->execute();

        return $statement->rowCount() == 2;
    }
}
