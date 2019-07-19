<?php
/**
  * Copyright © 2016 Magento. All rights reserved.
  * See COPYING.txt for license details.
  */
namespace Ecomteck\Core\Lib\Serialize\Serializer;
use Ecomteck\Core\Lib\Serialize\SerializerInterface;
class Json implements SerializerInterface
{
     /**
      * {@inheritDoc}
      */
     public function serialize($data)
     {
        if(class_exists('Magento\Framework\Serialize\Serializer\Json')){
            $result = json_encode($data);
            if (false === $result) {
                throw new \InvalidArgumentException('Unable to serialize value.');
            }
            return $result;
        }
        return serialize($data);
     }
 
     /**
      * {@inheritDoc}
      */
     public function unserialize($string)
     {
        if(class_exists('Magento\Framework\Serialize\Serializer\Json')){
            $result = json_decode($string, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Unable to unserialize value.');
            }
            return $result;
        }
        return unserialize($string);
     }
}