<?php

namespace App\Services\Query;

use App\Services\Request\UriParser;
use App\Traits\Models\Enabled;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator as BasePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Traits\ForwardsCalls;
use Str;
use Unlu\Laravel\Api\Exceptions\UnknownColumnException;
use Unlu\Laravel\Api\Paginator;

/**
 * @method firstOrFail()
 */
class QueryBuilder
{
    use ForwardsCalls;

    protected $model;

    protected $uriParser;

    protected $wheres = [];

    protected $orderBy = [];

    protected $limit;

    protected $page = 1;

    protected $offset = 0;

    protected $columns = ['*'];

    protected $existsRelations = [];

    protected $relationColumns = [];

    protected $includes = [];

    protected $groupBy = [];

    protected $excludedParameters = [];

    protected $appends = [];

    protected $query;

    protected $result;

    public function __construct(Model $model, Request $request)
    {
        $this->orderBy = config('api-query-builder.orderBy');

        $this->limit = config('api-query-builder.limit');

        $this->excludedParameters = array_merge($this->excludedParameters, config('api-query-builder.excludedParameters'));

        $this->model = $model;

        $this->uriParser = new UriParser($request);

        $this->query = $this->model->newQuery();
    }

    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            return $this->forwardCallTo($this->query, $method, $args);
        }
        return $this->{$method}($args);
    }

    public function findOrFail($id)
    {
        return $this->query->withExists($this->existsRelations)->findOrFail($id);
    }

    public function build()
    {
        $this->prepare();

        if ($this->hasWheres()) {
            array_map([$this, 'addWhereToQuery'], $this->wheres);
        }

        if ($this->hasGroupBy()) {
            $this->query->groupBy($this->groupBy);
        }

        if ($this->hasLimit()) {
            $this->query->take($this->limit);
        }

        if ($this->hasOffset()) {
            $this->query->skip($this->offset);
        }

        array_map([$this, 'addOrderByToQuery'], $this->orderBy);

        $this->query->with($this->includes);

        $this->query->select($this->columns);

        $this->query->withExists($this->existsRelations);

        return $this;
    }

    public function get()
    {
        $result = $this->query->get();

        if ($this->hasAppends()) {
            $result = $this->addAppendsToModel($result);
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function paginate()
    {
        if (!$this->hasLimit()) {
            throw new Exception("You can't use unlimited option for pagination", 1);
        }

        $result = $this->basePaginate($this->limit);

        if ($this->hasAppends()) {
            $result = $this->addAppendsToModel($result);
        }

        return $result;
    }

    public function lists($value, $key)
    {
        return $this->query->lists($value, $key);
    }

    protected function prepare()
    {
        $this->setWheres($this->uriParser->whereParameters());

        $constantParameters = $this->uriParser->constantParameters();

        array_map([$this, 'prepareConstant'], $constantParameters);

        if ($this->hasIncludes() && $this->hasRelationColumns()) {
            $this->fixRelationColumns();
        }

        return $this;
    }

    private function prepareConstant($parameter)
    {

        if (!$this->uriParser->hasQueryParameter($parameter)) {
            return;
        }

        $callback = [$this, $this->setterMethodName($parameter)];

        $callbackParameter = $this->uriParser->queryParameter($parameter);

        call_user_func($callback, $callbackParameter['value']);
    }

    private function setIncludes($includes)
    {
        $this->includes = array_filter(explode(',', $includes));
    }

    private function setPage($page)
    {
        $this->page = (int)$page;

        $this->offset = ($page - 1) * $this->limit;
    }

    private function setColumns($columns)
    {
        $columns = array_filter(explode(',', $columns));

        $this->columns = $this->relationColumns = [];

        array_map([$this, 'setColumn'], $columns);
    }

    private function setColumn($column): void
    {
        if ($this->isRelationColumn($column)) {
            $this->appendRelationColumn($column);
            return;
        }

        $this->columns[] = $column;
    }

    private function appendRelationColumn($keyAndColumn): void
    {
        list($key, $column) = explode('.', $keyAndColumn);

        $this->relationColumns[$key][] = $column;
    }

    private function fixRelationColumns()
    {
        $keys = array_keys($this->relationColumns);

        $callback = [$this, 'fixRelationColumn'];

        array_map($callback, $keys, $this->relationColumns);
    }

    private function fixRelationColumn($key, $columns)
    {
        $index = array_search($key, $this->includes);

        unset($this->includes[$index]);

        $this->includes[$key] = $this->closureRelationColumns($columns);
    }

    private function closureRelationColumns($columns)
    {
        return function ($q) use ($columns) {
            $q->select($columns);
        };
    }

    private function setExists($relations)
    {
        $this->existsRelations = array_filter(explode(',', $relations));
    }

    private function setOrderBy($order)
    {
        $this->orderBy = [];

        $orders = array_filter(explode('|', $order));

        array_map([$this, 'appendOrderBy'], $orders);
    }

    private function appendOrderBy($order)
    {
        if ($order == 'random') {
            $this->orderBy[] = 'random';
            return;
        }

        list($column, $direction) = explode(',', $order);

        $this->orderBy[] = [
            'column' => $column,
            'direction' => $direction
        ];
    }

    private function setGroupBy($groups)
    {
        $this->groupBy = array_filter(explode(',', $groups));
    }

    private function setPageLimit($limit): void
    {
        $this->limit = ($limit == 'unlimited') ? null : (int)$limit;

    }

    private function setWheres($parameters)
    {
        $this->wheres = $parameters;
    }

    private function setAppends($appends)
    {
        $this->appends = explode(',', $appends);
    }

    /**
     * @throws UnknownColumnException
     */
    private function addWhereToQuery($where): void
    {
        extract($where);

        // For array values (whereIn, whereNotIn)
        if (isset($values)) {
            $value = $values;
        }
        if (!isset($operator)) {
            $operator = '';
        }

        /** @var mixed $key */
        if ($this->isExcludedParameter($key)) {
            return;
        }

        if ($this->hasCustomFilter($key)) {
            /** @var string $type */
            $this->applyCustomFilter($key, $operator, $value, $type);
            return;
        }

        if (in_array($key, $this->model->translatedAttributes ?? [])) {
            $this->applyTranslationFilter($key, $operator, $value, $type);
            return;
        }

        if ($this->isRelation($key)) {
            $key_parts = str($key)->explode('.');
            $column = $key_parts->last();
            $relation = $key_parts->filter(fn($part) => $part != $column)->implode('.');
            $this->applyRelation($relation, $column, $operator, $value, $type);
            return;
        }

        if (!$this->hasTableColumn($key)) {
            throw new UnknownColumnException("Unknown column '{$key}'");
        }

        /** @var string $type */
        if ($type == 'In') {
            $this->query->whereIn($key, $value);
            return;
        }

        if ($type == 'NotIn') {
            $this->query->whereNotIn($key, $value);
            return;
        }

        if ($value == '[null]') {
            $operator == '=' ? $this->query->whereNull($key) : $this->query->whereNotNull($key);
            return;
        }

        in_array($operator, ['!=', '<>'])
            ? $this->query->where(fn($q) => $q->where($key, $operator, $value)->orWhereNull($key))
            : $this->query->where($key, $operator, $value);
    }

    private function addOrderByToQuery($order): void
    {
        if ($order == 'random') {
            $this->query->orderBy(DB::raw('RAND()'));
            return;
        }

        extract($order);

        /** @var string $column */
        /** @var string $direction */
        $this->query->orderBy($column, $direction);
    }

    private function applyTranslationFilter($key, $operator, $value, $type = 'Basic'): void
    {
        $this->query = $this->model->{'whereTranslation'}(translationField: $key, value: $value, operator: $operator);
    }

    private function applyCustomFilter($key, $operator, $value, $type = 'Basic'): void
    {
        $callback = [$this, $this->customFilterName($key)];

        $this->query = call_user_func($callback, $this->query, $value, $operator, $type);
    }

    private function isRelationColumn($column): bool
    {
        return (count(explode('.', $column)) > 1);
    }

    private function isExcludedParameter($key): bool
    {
        return in_array($key, $this->excludedParameters);
    }

    private function hasWheres(): bool
    {
        return (count($this->wheres) > 0);
    }

    private function hasIncludes(): bool
    {
        return (count($this->includes) > 0);
    }

    private function hasAppends(): bool
    {
        return (count($this->appends) > 0);
    }

    private function hasGroupBy(): bool
    {
        return (count($this->groupBy) > 0);
    }

    private function hasLimit()
    {
        return ($this->limit);
    }

    private function hasOffset(): bool
    {
        return ($this->offset != 0);
    }

    private function hasRelationColumns(): bool
    {
        return (count($this->relationColumns) > 0);
    }

    private function hasTableColumn($column)
    {
        return (Schema::hasColumn($this->model->getTable(), $column));
    }

    private function hasCustomFilter($key): bool
    {
        $methodName = $this->customFilterName($key);

        return (method_exists($this, $methodName));
    }

    private function setterMethodName($key): string
    {
        return 'set' . Str::studly($key);
    }

    private function customFilterName($key): string
    {
        return 'filterBy' . Str::studly($key);
    }

    private function addAppendsToModel($result)
    {
        $result->map(function ($item) {
            $item->append($this->appends);
            return $item;
        });

        return $result;
    }

    /**
     * Paginate the given query.
     *
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return Paginator
     *
     * @throws \InvalidArgumentException
     */
    private function basePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: BasePaginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $query = $this->query->getQuery();

        if (in_array(SoftDeletes::class, class_uses(new $this->model), true)) {
            $query->whereNull('deleted_at');
        }

        if (in_array(Enabled::class, class_uses(new $this->model), true)) {
            $query->where('enabled', true);
        }

        $total = $query->getCountForPagination();

        $results = $total ? $this->query->forPage($page, $perPage)->get($columns) : new Collection;

        return (new Paginator($results, $total, $perPage, $page, [
            'path' => BasePaginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]))->setQueryUri($this->uriParser->getQueryUri());
    }

    public function isRelation($key): bool
    {
        if (Str::of($key)->explode('.')->count() > 1) {
            return method_exists($this->model, Str::of($key)->explode('.')->first());
        }
        return false;
    }

    public function applyRelation($relation, $column, $operator, $value, $type)
    {
        return $this->query->whereHas($relation, function ($q) use ($column, $operator, $value, $type) {
            if ($type == 'In') {
                return $q->whereIn($column, $value);
            }

            if ($type == 'NotIn') {
                return $q->whereNotIn($column, $value);
            }
            if ($value == '[null]') {
                return $operator == '='
                    ? $q->whereNull($column)
                    : $q->whereNotNull($column);
            }

            return in_array($operator, ['!=', '<>'])
                ? $q->where(fn($subQ) => $subQ->where($column, $operator, $value)->orWhereNull( $column)) :
                $q->where($column, $operator, $value);
        });
    }
}
