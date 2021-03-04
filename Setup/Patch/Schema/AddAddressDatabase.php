<?php

namespace Hunters\SearchShopMap\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Hunters\SearchShopMap\Helper\Cord;

class AddAddressDatabase implements SchemaPatchInterface
{
    private $cord;
    private $moduleDataSetup;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $connection;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Framework\App\ResourceConnection $connection,
        Cord $cord
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->connection = $connection;
        $this->cord = $cord;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $connection = $this->connection->getConnection();
        $table = $this->connection->getTableName('AddressZipCode');
        $arr = $this->cord->getArrayCoordinate("/var/www/html/magento224/addres2.txt");
        $connection->insertMultiple($table, $arr);
        $this->moduleDataSetup->endSetup();
    }
}