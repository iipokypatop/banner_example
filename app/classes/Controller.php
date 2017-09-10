<?php


namespace Task;


class Controller
{
    /**
     * @return static
     */
    public static function create()
    {
        $ob = new static;

        return $ob;
    }

    public function handle(array $server)
    {
        $user = User::createFromServer($server);

        $rows = \Task\Mysql::get()
            ->query(
                \Task\MysqlQueries::checkUserInDb(),
                [
                    ":hash" => $user->getHash()
                ]
            );

        $user_exists = false;

        if (!isset($rows[0]['count'])) {
            $user_exists = true;
        }

        if (false === $user_exists) {
            \Task\Mysql::get()->query(
                \Task\MysqlQueries::addVisit(),
                [
                    ':ip_address' => $user->getIpAddress(),
                    ':user_agent' => $user->getUserAgent(),
                    ':view_date' => (new \DateTime())->format('Y-m-d H:i:s'),
                    ':page_url' => $user->getPageUrl(),
                    ':hash' => $user->getHash(),
                ]
            );
        } else {
            \Task\Mysql::get()->query(
                \Task\MysqlQueries::updateVisit(),
                [
                    ':view_date' => (new \DateTime())->format('Y-m-d H:i:s'),
                    ':hash' => $user->getHash(),
                ]
            );
        }
    }
}