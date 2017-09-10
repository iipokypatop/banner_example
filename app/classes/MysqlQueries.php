<?php

namespace Task;


class MysqlQueries
{
    public static function initDump()
    {
        $sql = <<<SQL
-- CREATE DATABASE IF NOT EXISTS  banner /*!40100 DEFAULT CHARACTER SET utf8 */;

CREATE TABLE  IF NOT EXISTS banner.visits(
  id INT NOT NULL AUTO_INCREMENT,
  ip_address VARCHAR(16) NOT NULL,
  user_agent VARCHAR(1024) NOT NULL,
  view_date DATETIME NOT NULL,
  page_url VARCHAR(1024) NOT NULL,
  HASH VARCHAR(41) NULL,
  views_count INT NULL DEFAULT 0,
  PRIMARY KEY (id),
UNIQUE INDEX hash_UNIQUE (HASH ASC));
SQL;

        return $sql;

    }

    public static function checkUserInDb()
    {
        $sql = <<<SQL

SELECT count(*) as count 
FROM visits 
WHERE hash = :hash
SQL;

        return $sql;
    }

    public static function addVisit()
    {
        $sql = <<<SQL
INSERT INTO visits
(
    ip_address,
    user_agent,
    view_date,
    page_url,
    hash,
    views_count
)
VALUES
(
    :ip_address,
    :user_agent,
    :view_date,
    :page_url,
    :hash,
    1
)


SQL;

        return $sql;
    }


    public static function updateVisit()
    {
        $sql = <<<SQL
UPDATE visits
set
	view_date = :view_date,
    views_count = views_count +1 
where  hash = :hash   

SQL;

        return $sql;
    }
}