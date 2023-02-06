<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Trait Tables
 * @package App\Services
 */
trait Tables
{
    /**
     * @param Request $request
     * @param Builder $builder
     * @param array   $searchFields
     * @param array   $aliasFields
     *
     * @return array
     */
    protected function getTable(Request $request, Builder $builder, array $searchFields = [], $aliasFields = []): array
    {
        $fields    = $this->transformFields($searchFields);
        $query     = $request->input('query');
        $limit     = $request->input('limit', 10);
        $page      = $request->input('page', 1);
        $orderBy   = $request->input('orderBy');
        $ascending = $request->input('ascending');
        $byColumn  = $request->input('byColumn');
        $isBundle  = $request->input('isBundle', false);

        if (!empty($aliasFields)) {
            $builder = $builder->select();

            foreach ($aliasFields as $alias => $name) {
                $builder = $builder->addSelect("{$name} as $alias");
            }
        }

        if ($query) {
            $builder = ($byColumn == 1)
                ? $this->filterByColumn($builder, $query, $fields)
                : $this->filter($builder, $query, $fields);
        }

        // adding condition for bundle product
        if ($isBundle) {
            $builder->where('isBundle', 0);
        }

        $count = $builder->count();

        $builder
            ->limit($limit)
            ->skip($limit * ($page - 1));

        if ($orderBy) {
            $fieldName = isset($fields[$orderBy]) ? $fields[$orderBy] : $orderBy;
            $direction = ($ascending == 1) ? 'ASC' : 'DESC';
            $builder->orderBy($fieldName, $direction);
        }

        $results = $builder->get();

        return [
            'data'  => $results,
            'count' => $count
        ];
    }

    protected function filterByColumn(Builder $builder, string $query, array $fields)
    {
        $queries = json_decode($query, true);

        return $builder->where(function ($q) use ($queries, $fields) {
            foreach ($queries as $field => $query) {
                if (!isset($fields[$field])) {
                    continue;
                }

                $field = $fields[$field];
                if (is_string($query)) {
                    $q->where($field, 'LIKE', "%{$query}%");
                } else {
                    $start = Carbon::createFromFormat('Y-m-d', $query['start'])->startOfDay();
                    $end   = Carbon::createFromFormat('Y-m-d', $query['end'])->endOfDay();

                    $q->whereBetween($field, [$start, $end]);
                }
            }
        });
    }

    protected function filter(Builder $builder, string $query, array $fields)
    {
        return $builder->where(function ($q) use ($query, $fields) {
            $index = 0;
            foreach ($fields as $alias => $field) {
                $method = $index ? 'orWhere' : 'where';
                $q->{$method}($field, 'LIKE', "%{$query}%");
                $index++;
            }
        });
    }

    protected function transformFields(array $inputFields): array
    {
        $fields = [];
        foreach ($inputFields as $key => $value) {
            if (is_numeric($key)) {
                $fields[$value] = $value;
            } else {
                $fields[$key] = $value;
            }
        }

        return $fields;
    }
}