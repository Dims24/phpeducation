<?php

namespace App\Http\Common;

use App\Foundation\Database\QueryBuilder;
use App\Foundation\HTTP\Request;
use App\Helpers\Collection\Arr;
use Closure;
use Exception;

abstract class BaseCRUDController extends BaseController
{
    /**
     * @return array|string
     */
    abstract protected function getDefaultOrder(): array|string;

    /**
     * @return QueryBuilder
     */
    abstract protected function getQueryBuilder(): QueryBuilder;

    /**
     * @throws Exception
     */
    protected function parentIndex(Request $request, array $options = [], Closure $closure = null)
    {
        $default_options = [
            'filters' => [
                'enable' => true,
                'ignore' => []
            ],
            'orders' => [
                'enable' => true,
            ],
            'pagination' => [
                'limit' => 10,
                'enable' => true,
            ],
        ];

        $this->setOptions(array_merge_recursive_distinct($default_options, $options));

        $builder = $this->getQueryBuilder();

        if ($this->getOption('filters.enable')) {
            $builder = $this->addFilters($request, $builder);
        }

        if ($this->getOption('orders.enable')) {
            $builder = $this->addOrders($request, $builder);
        }

        if ($closure) {
            $tmp_builder = $closure($builder, 'builder');
            if ($tmp_builder) {
                $builder = $tmp_builder;
            }
        }

        if ($this->getOption('pagination.enable')) {
            $limit = $request->get('limit') ?? $this->getOption('pagination.limit');
            $page = $request->get('page') ?? 1;

            $items = $builder->paginate($limit, $page);
        } else {
            $items = $builder->get();
        }


        if ($closure) {
            if ($filter_result = $closure($items, 'filter')) {
                $items = $filter_result;
            }
        }

        return $items;
    }

    /**
     * @param  Request  $request
     * @param  QueryBuilder  $builder
     * @return QueryBuilder
     * @throws Exception
     */
    protected function addFilters(Request $request, QueryBuilder $builder): QueryBuilder
    {
        $filters = $request->get('filter') ?? [];

        foreach ($filters as $filter) {
            $builder = $this->addFilter($builder, $filter);
        }

        return $builder;
    }

    /**
     * Operators:
     * '=', '<', '>', '<=', '>=', '<>', '!=', '<=>', 'like', 'like binary',
     * 'not like', 'ilike', '&', '|', '^', '<<', '>>', 'rlike', 'not rlike',
     * 'regexp', 'not regexp','~', '~*', '!~', '!~*', 'similar to',
     * 'not similar to', 'not ilike', '~~*', '!~~*'
     * @param  QueryBuilder  $builder
     * @param  array  $filter
     * @return QueryBuilder
     * @throws Exception
     */
    protected function addFilter(QueryBuilder $builder, array $filter): QueryBuilder
    {
        if (!Arr::isAssoc($filter)) {
            throw new Exception('Bad filter. The filter should be an associative array.', 400);
        }

        if (!isset($filter['column'])) {
            throw new Exception('Bad filter. The column is required.', 400);
        }

        $ignore = $this->getOption('filter.ignore');
        if ($ignore && is_array($ignore) && in_array($filter['column'], $ignore)) {
            return $builder;
        }

        $column = $filter['column'];
        $operator = $filter['operator'] ?? '=';
        $value = $filter['value'] ?? null;
        $boolean = $filter['boolean'] ?? 'and';

        if (!mb_stripos($column, '.')) {
            $column = $this->current_model->getTable().'.'.$column;
        }

        if (is_array($value)) {
            return $builder->whereIn($column, $operator !== '=', $value, $boolean);
        }

        return $builder->where($column, $operator, $value, $boolean);
    }

    /**
     * @param  Request  $request
     * @param  QueryBuilder  $builder
     * @return QueryBuilder
     */
    protected function addOrders(Request $request, QueryBuilder $builder): QueryBuilder
    {
        $orders = $request->get('order') ?? [];

        if (!$orders) {
            $orders = $this->getDefaultOrder();
        }

        if (!is_array($orders)) {
            $orders = [$orders];
        }

        foreach ($orders as $order) {
            $builder = $this->addOrder($builder, htmlspecialchars($order, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        }

        return $builder;
    }

    /**
     * @param  QueryBuilder  $builder
     * @param  string  $order
     * @return QueryBuilder
     */
    protected function addOrder(QueryBuilder $builder, string $order): QueryBuilder
    {
        $direction = 'asc';

        if (str_starts_with($order, '-')) {
            $direction = 'desc';
            $order = substr($order, 1);
        }

        $builder->orderBy($order, $direction);

        return $builder;
    }
}
