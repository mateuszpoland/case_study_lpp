<?php
namespace Lpp\Lpp\Service;

class UnorderedBrandService extends BrandService
{
    /**
     * @param string $collectionName Name of the collection to search for.
     *
     * @return \Lpp\Lpp\Entity\Brand[]
     */
    public function getBrandsForCollection($collectionName) {
        if (empty($this->collectionNameToIdMapping[$collectionName])) {
            throw new \InvalidArgumentException(sprintf('Provided collection name [%s] is not mapped.', $collectionName));
        }

        $collectionId = $this->collectionNameToIdMapping[$collectionName];
        return $this->itemService->getResultForCollectionId($collectionId);
    }
}
