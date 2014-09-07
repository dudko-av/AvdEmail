<?php
namespace AvdEmail\Paginator;

use Zend\Paginator\Adapter\AdapterInterface;

class Paginator implements AdapterInterface
{
    protected $queryBuilder;
    protected $count = 0;
    protected $currentPageNamber = 1;

    public function __construct($queryBuilder, $currentPageNumber = 0)
    {
        $this->queryBuilder = $queryBuilder;
        $this->currentPageNamber = (int)$currentPageNumber;
        
        $this->queryBuilder->select($this->queryBuilder->expr()->count('l.id'));
        $this->count = $this->queryBuilder
                            ->getQuery()
                            ->getSingleScalarResult();
    }
    
    /**
     * Returns a collection of items for a page.
     *
     * @param  int $offset Page offset
     * @param  int $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->queryBuilder->select('l');
        
        $firstResultOffset = ($this->currentPageNamber > 1) ? ($this->currentPageNamber - 1) * 10 : 0;
        
        if ($firstResultOffset == $this->count && $firstResultOffset > 9) {
            $firstResultOffset -= 10;
        }
        
        $result = $this->queryBuilder->getQuery()
                                     ->setFirstResult($firstResultOffset)
                                     ->setMaxResults($itemCountPerPage)
                                     ->getResult();

        return $result;
    }
    
    public function count()
    {
        return $this->count;
    }
}
