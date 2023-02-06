<?php

namespace App\Searches;

use App\Common\Helpers\DataReplacerModel;
use Illuminate\Validation\ValidationException;
use Validator;

/**
 * Class AbstractSearch
 * @package App\Searches
 */
abstract class AbstractSearch implements SearchInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $transformers = [];

    /**
     * @var bool
     */
    protected $allowedEmpty = false;

    /**
     * AbstractSearch constructor.
     *
     * @param array $data
     *
     * @throws ValidationException
     */
    public function __construct(array $data)
    {
        $this
            ->setData($data)
            ->validate();
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        $data = [];
        foreach ($this->data as $name => $value) {
            $data[$name] = $this->getFilter($name, $value);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function isAllowedEmpty(): bool
    {
        return $this->allowedEmpty;
    }

    /**
     * @param bool $allowedEmpty
     *
     * @return SearchInterface
     */
    public function setAllowedEmpty(bool $allowedEmpty): SearchInterface
    {
        $this->allowedEmpty = $allowedEmpty;

        return $this;
    }

    /**
     * @return array
     */
    protected abstract function getRules(): array;

    /**
     * @return array
     */
    protected function getTransformers(): array
    {
        return $this->transformers;
    }

    /**
     * @param array $data
     *
     * @return AbstractSearch
     */
    protected function setData(array $data): AbstractSearch
    {
        $this->data = [];

        $rules = $this->getRules();
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $rules)) {
                continue;
            }

            if (empty($value)) {
                continue;
            }

            if ($rules[$key] === 'boolean') {
                $value = (bool)$value;
            }

            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * @return AbstractSearch
     * @throws ValidationException
     */
    protected function validate(): AbstractSearch
    {
        if (!empty($this->data)) {
            $rules = $this->getRules();

            /** @var \Illuminate\Validation\Validator $validator */
            $validator = Validator::make($this->data, $rules);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $this->data = $this->transformData($validator->getData());

            if (empty($this->data) && !$this->isAllowedEmpty()) {
                throw ValidationException::withMessages([
                    'generic' => ['Provide at least one search filter from the following list: ' . implode(', ', array_keys($rules))],
                ]);
            }
        }

        return $this;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function transformData(array $data): array
    {
        $transformers = $this->getTransformers();
        foreach ($transformers as $property => $transformer) {
            if (!array_key_exists($property, $data) || !($transformer instanceof \Closure)) {
                continue;
            }

            $transformedValue = $transformer($data[$property]);
            if ($transformedValue instanceof DataReplacerModel) {
                unset($data[$property]);
                $property         = $transformedValue->property;
                $transformedValue = $transformedValue->value;
            }

            if (null === $transformedValue) {
                unset($data[$property]);
                continue;
            }

            $data[$property] = $transformedValue;
        }

        return $data;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function getFilter(string $name, $value)
    {
        return $value;
    }
}