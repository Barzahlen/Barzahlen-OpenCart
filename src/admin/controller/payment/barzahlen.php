<?php

/**
 * Barzahlen Payment Module for OpenCart
 * Copyright (C) 2013 Zerebro Internet GmbH (http://www.barzahlen.de)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @category   ZerebroInternet
 * @package    ZerebroInternet_Barzahlen
 * @copyright  Copyright (C) 2013 Zerebro Internet GmbH (http://www.barzahlen.de)
 * @author     Mathias Hertlein
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License
 */

class ControllerPaymentBarzahlen extends Controller
{
    public function index()
    {
        $this->loadLibBarzahlen();
        $controller = new BarzahlenControllerAdminIndex($this->registry);
        $this->response->setOutput($controller->get());
    }

    public function orders()
    {
        $this->loadLibBarzahlen();
        $controller = new BarzahlenControllerOrdersAdmin($this->registry);
        $this->response->setOutput($controller->get());
    }

    public function refunds()
    {
        $this->loadLibBarzahlen();
        $controller = new BarzahlenControllerAdminRefunds($this->registry);
        $this->response->setOutput($controller->get());
    }

    public function install()
    {
        $dbPrefix = DB_PREFIX;

        $sql = <<<SQL
CREATE TABLE `{$dbPrefix}order_barzahlen_transaction` (
`barzahlen_transaction_id` int(11) NOT NULL,
`order_id` int(11) NOT NULL,
`date_added` datetime NOT NULL,
`date_modified` datetime NOT NULL,
PRIMARY KEY (`barzahlen_transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
SQL;
        $this->db->query($sql);

        $sql = <<<SQL
CREATE TABLE `{$dbPrefix}order_barzahlen_refund_transaction` (
`barzahlen_refund_transaction_id` int(11) NOT NULL,
`barzahlen_transaction_id` int(11) NOT NULL,
`order_id` int(11) NOT NULL,
`amount` decimal(15,4) NOT NULL,
`date_added` datetime NOT NULL,
`date_modified` datetime NOT NULL,
PRIMARY KEY (`barzahlen_refund_transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
SQL;
        $this->db->query($sql);
    }

    public function uninstall()
    {
        $dbPrefix = DB_PREFIX;

        $sql = <<<SQL
DROP TABLE `{$dbPrefix}order_barzahlen_transaction`;
SQL;
        $this->db->query($sql);

        $sql = <<<SQL
DROP TABLE `{$dbPrefix}order_barzahlen_refund_transaction`;
SQL;
        $this->db->query($sql);
    }

    /**
     * Includes Barzahlen API library
     */
    private function loadLibBarzahlen()
    {
        require_once(
            DIR_SYSTEM .
                ".." . DIRECTORY_SEPARATOR .
                "lib" . DIRECTORY_SEPARATOR .
                "barzahlen" . DIRECTORY_SEPARATOR .
                "include.php"
        );
    }
}
