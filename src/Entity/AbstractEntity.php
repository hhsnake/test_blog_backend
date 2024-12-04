<?php

namespace TestBlog\Entity;

class AbstractEntity
{

    public function toArray(): array
    {
        $objectVar = [];
        if (method_exists($this, 'getObjectVars')) {
            $objectVar = $this->getObjectVars();
            foreach ($objectVar as &$item) {
                if ($item instanceof \DateTime) {
                    $item = $item->format('Y-m-d H:i:s');
                }
                if ($item instanceof PersistentCollection) {
                    $temp = [];
                    foreach ($item as $value) {
                        $temp[] = $value->getId();
                    }
                    $item = $temp;
                }
                if ($item instanceof self) {
                    $item = $item->getId();
                }
            }
        }
        return $objectVar;
    }
}