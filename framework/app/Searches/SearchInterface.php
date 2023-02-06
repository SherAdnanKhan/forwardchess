<?php

namespace App\Searches;

/**
 * Interface SearchInterface
 * @package App\Contracts
 */
interface SearchInterface
{
    /**
     * @return array
     */
    public function getFilters(): array;

    /**
     * @return bool
     */
    public function isAllowedEmpty(): bool;

    /**
     * @param bool $allowedEmpty
     *
     * @return SearchInterface
     */
    public function setAllowedEmpty(bool $allowedEmpty): SearchInterface;
}