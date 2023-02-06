<?php

namespace App\Assets;

/**
 * Class WishlistReport
 * @package App\Assets
 */
class WishlistReport
{
    /**
     * @var string
     */
    private $query = null;

    /**
     * @var string
     */
    private $sortBy = 'nrUsers';

    /**
     * @var string
     */
    private $sortDir = 'desc';

    /**
     * @var int
     */
    private $page = 0;

    /**
     * @var int
     */
    private $rowsPerPage = 25;

    /**
     * @param array $data
     *
     * @return WishlistReport
     */
    public static function make(array $data = []): WishlistReport
    {
        $instance = new static;
        $mapper   = [
            'query'       => 'setQuery',
            'sortBy'      => 'setSortBy',
            'sortDir'     => 'setSortDir',
            'page'        => 'setPage',
            'rowsPerPage' => 'setRowsPerPage',
        ];

        foreach ($data as $property => $value) {
            if (is_null($value)) {
                continue;
            }

            if (!array_key_exists($property, $mapper)) {
                $bulkFields[$property] = $value;
                continue;
            }

            call_user_func_array([$instance, $mapper[$property]], [$value]);
        }

        return $instance;
    }

    /**
     * @return string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string|null $query
     *
     * @return WishlistReport
     */
    public function setQuery(string $query = null): WishlistReport
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    /**
     * @param string|null $sortBy
     *
     * @return WishlistReport
     */
    public function setSortBy(string $sortBy): WishlistReport
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortDir(): string
    {
        return $this->sortDir;
    }

    /**
     * @param string $sortDir
     *
     * @return WishlistReport
     */
    public function setSortDir(string $sortDir): WishlistReport
    {
        $this->sortDir = $sortDir;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return WishlistReport
     */
    public function setPage(int $page): WishlistReport
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getRowsPerPage(): int
    {
        return $this->rowsPerPage;
    }

    /**
     * @param int $rowsPerPage
     *
     * @return WishlistReport
     */
    public function setRowsPerPage(int $rowsPerPage): WishlistReport
    {
        $this->rowsPerPage = $rowsPerPage;

        return $this;
    }
}