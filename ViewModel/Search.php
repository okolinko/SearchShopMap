<?php
namespace Hunters\SearchShopMap\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Hunters\SearchShopMap\Service\SearchZip;
use GuzzleHttp\ClientFactory;
use Magento\Framework\DB\Ddl\Table;
use Hunters\SearchShopMap\Helper\Cord;

class Search implements ArgumentInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $connection;
    protected $searchZip;
    protected $_ApiGoogleFactory;
    protected $_registry;
    protected $cord;
    private $clientFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $connection,
        ClientFactory $clientFactory,
        \Hunters\SearchShopMap\Model\ApiGoogleFactory $ApiGoogleFactory,
        \Magento\Framework\Registry $registry,
        SearchZip $searchZip,
        Cord $cord
    )
    {
        $this->clientFactory = $clientFactory;
        $this->connection = $connection;
        $this->searchZip = $searchZip;
        $this->_ApiGoogleFactory = $ApiGoogleFactory;
        $this->_registry = $registry;
        $this->cord = $cord;
    }

    public function getMyCustomCollection()
    {
        $ApiGoogle = $this->_ApiGoogleFactory->create();
        $collection = $ApiGoogle->getCollection();
        return $collection;
    }

    public function coordinateArray($myCollection)
    {
        $result = array_column($myCollection, 'coordinate');
        $result = array_values(array_unique($result));
        $result = array_map(function($element){
            $element = json_decode(json_encode(json_decode($element)), true);
            return $element;
        }, $result);
        return $result;
    }

    public function stateAndCoordianateArray($myCollection)
    {
        $array = array_keys($myCollection);
        $final_array = [];
        foreach($array as $key =>$value){
            $final_array[$value]['coordinate'] = json_decode(json_encode(json_decode($myCollection[$key]['coordinate'])), true);
            $final_array[$value]['state'] = $myCollection[$key]['state'];
        }
        return $final_array;
    }

}