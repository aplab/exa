<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 25.08.2018
 * Time: 16:30
 */

namespace Aplab\AplabAdminBundle\Component\DataTableRepresentation\Pager;

use Respect\Validation\Validator;

class Pager
{
    /**
     * @var int
     */
    const ITEMS_PER_PAGE_DEFAULT = 100;

    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $itemsPerPage;

    /**
     * @var array
     */
    private $itemsPerPageVariants = [
        10,
        50,
        100,
        200,
        500
    ];

    /**
     * Pager constructor.
     * @param int $count
     */
    public function __construct(int $count)
    {
        $this->count = $count;
        $this->itemsPerPage = static::ITEMS_PER_PAGE_DEFAULT;
        $this->currentPage = 1;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @return array
     */
    public function getItemsPerPageVariants(): array
    {
        return $this->itemsPerPageVariants;
    }

    /**
     * @param int $items_per_page
     * @return Pager
     */
    public function setItemsPerPage(int $items_per_page): Pager
    {
        if (in_array($items_per_page, $this->itemsPerPageVariants)) {
            $this->itemsPerPage = $items_per_page;
        } else {
            $this->itemsPerPage = static::ITEMS_PER_PAGE_DEFAULT;
        }
        return $this;
    }

    /**
     * @param array $items_per_page_variants
     * @return Pager
     */
    public function setItemsPerPageVariants(array $items_per_page_variants): Pager
    {
        Validator::arrayType()->each(Validator::digit())->check($items_per_page_variants);
        $this->itemsPerPageVariants = $items_per_page_variants;
        return $this;
    }

    /**
     * @return array
     */
    public function getPages()
    {
        return range(1, ceil($this->count / $this->itemsPerPage));
    }

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return Pager
     */
    public function setCount(int $count): Pager
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return Pager
     */
    public function setCurrentPage(int $currentPage): Pager
    {
        $pages = $this->getPages();
        if (in_array($currentPage, $pages)) {
            $this->currentPage = $currentPage;
        } else {
            $this->currentPage = 1;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    /**
     * @return int|null
     */
    public function getPrev(): ?int
    {
        return ($this->currentPage > 1) ? ($this->currentPage - 1) : null;
    }

    /**
     * @return int|null
     */
    public function getNext(): ?int
    {
        return ($this->currentPage < ceil($this->count / $this->itemsPerPage)) ? ($this->currentPage + 1) : null;
    }
}