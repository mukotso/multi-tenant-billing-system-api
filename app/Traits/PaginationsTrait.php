<?php

namespace App\Traits;

trait PaginationsTrait
{
  
    public function scopeSearch($query, $searchColumns = [], $searchTerm = null)
    {

        if (!empty($searchColumns) && !empty($searchTerm) and $searchTerm != '') {
            $query->where(function ($query) use ($searchTerm, $searchColumns) {
                foreach ($searchColumns as $column) {
                    $query->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        return $query;
    }


    public function scopeFilters($query, $filters)
    {
        if (is_array($filters) && !empty($filters) && count($filters) > 0) {
            foreach ($filters as $column => $value) {
                if (!empty($value)) {
                    $query->where($column, $value);
                }
            }
        }
        return $query;
    }

    public function scopeSort($query, ?string $sortBy = 'created_at', $sortType = 'asc')
    {
        try {
            $sortDirection = strtolower($sortType) == 'desc' ? 'desc' : 'asc';
            if (!empty($sortDirection)) {
                $query->orderBy($sortBy, $sortDirection);
            }
        } catch (\Exception $e) {
            $query->orderBy($sortBy, 'asc');
        }

        return $query;
    }

    public function scopeSorts($query, $sortColumns, $sortType)
    {
        if (is_array($sortColumns) && !empty($sortType)) {
            foreach ($sortColumns as $column) {
                if ($column === $sortType || $column === str_replace('-', '', $sortType)) {
                    // $query->orderBy($column, starts_with($sortType, '-') ? 'desc' : 'asc');
                    $query->orderBy($column, str_starts_with($sortType, '-') ? 'desc' : 'asc');
                    break;
                }
            }
        }
        return $query;
    }


    public function scopecustomPaginate($query, $perPage = 10, $currentPage = 1)
    {
        $perPage = is_numeric($perPage) ? (int)$perPage : 10;
        $perPage = ($perPage <= 100) ? $perPage : 10;
        $currentPage = is_numeric($currentPage) ? (int)$currentPage : 1;
        return $query->paginate($perPage, ['*'], 'page', $currentPage);
    }
}
