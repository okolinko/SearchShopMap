<?php

namespace Hunters\SearchShopMap\Helper;

use Hunters\SearchShopMap\Service\SearchZip;

class Cord
{
    private $searchZip;

    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        SearchZip $searchZip
    ) {
        $this->searchZip = $searchZip;
        $this->driverFile = $driverFile;
    }

    public function cord($zip)
    {
        sleep(0.2);
        $addres = $this->searchZip->getApiGoogle($zip);
        if ($addres != NULL){
            $res = json_decode(json_encode(json_decode($addres)), true);
            return $res;
        }
        else {
            return NULL;
        }
    }

    public function ziprevert($pathFile)
    {
        if ($this->driverFile->isExists($pathFile)) {
            $zip = $this->driverFile->fileGetContents($pathFile);
            $zip = json_decode($zip);
            $result = array_map(array($this, 'cord'), $zip);
            $new_array = array_filter($result, function ($element) {
                if ($element != NULL) {
                    return $element;
                }
            });
            return $new_array;
        }
        return NULL;
    }

    public function getArrayCoordinate($pathFile)
    {
        $arr = array();
        if ($this->ziprevert($pathFile) != NULL) {
            $zipArr = array_values($this->ziprevert($pathFile));
            $count = count($zipArr);
            for ($i = 0; $i < $count; $i++) {
                $arr[$i]['postcode'] = $zipArr[$i]['postcode'];
                $arr[$i]['state'] = $zipArr[$i]['state'];
                $arr[$i]['coordinate'] = json_encode($zipArr[$i]['coordinate']);
            }
            return $arr;
        }
        return $arr;
    }
}