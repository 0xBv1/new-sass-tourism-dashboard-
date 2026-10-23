<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Request;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UriParser
{
    const PATTERN = '/!=|=|<=|<|>=|>/';

    const ARRAY_QUERY_PATTERN = '/(.*)\[\]/';

    protected $request;

    protected $constantParameters = [
        'order_by',
        'group_by',
        'page_limit',
        'page',
        'columns',
        'includes',
        'appends',
        'exists',
    ];

    protected $uri;

    protected $queryUri;

    protected $queryParameters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->uri = Str::of($request->getRequestUri())->explode('?')->first() . $this->buildQueryString();

        $this->setQueryUri($this->uri);

        if ($this->hasQueryUri()) {
            $this->setQueryParameters($this->queryUri);
            //$this->setQueryParameters($this->request->toArray());
        }
    }

    public function buildQueryString(): string
    {
        $uri = Str::of($this->request->getRequestUri());
        $query = $uri->contains('?') ? $uri->trim()->explode('?')->last() : '';

        if (trim($query) != '?') {
            $query .= '&';
        }

        foreach ($this->request->all() as $k => $v) {
            if (!str_contains($query, $k) && !str_contains($query, str_replace('_', '.', $k))) {
                $query .= $k . '=' . $v . '&';
            }
        }

        return '?' . Str::of($query)->replaceLast('&', '')->toString();
    }

    public static function getPattern()
    {
        return self::PATTERN;
    }

    public static function getArrayQueryPattern()
    {
        return self::ARRAY_QUERY_PATTERN;
    }

    public function queryParameter($key)
    {
        $keys = \Arr::pluck($this->queryParameters, 'key');

        $queryParameters = array_combine($keys, $this->queryParameters);

        return $queryParameters[$key];
    }

    public function constantParameters()
    {
        return $this->constantParameters;
    }

    public function whereParameters()
    {
        return array_filter(
            $this->queryParameters,
            function ($queryParameter) {
                $key = $queryParameter['key'];

                return !in_array($key, $this->constantParameters);
            }
        );
    }

    private function setQueryUri($uri)
    {
        $explode = explode('?', $uri);

        $this->queryUri = (isset($explode[1])) ? rawurldecode($explode[1]) : null;
    }

    private function setQueryParameters($queryUri)
    {
        $queryParameters = array_filter(explode('&', $queryUri));
        array_map([$this, 'appendQueryParameter'], $queryParameters);
    }

    private function appendQueryParameter($parameter)
    {
        // whereIn expression
        preg_match(self::ARRAY_QUERY_PATTERN, $parameter, $arrayMatches);
        if (count($arrayMatches) > 0) {
            $this->appendQueryParameterAsWhereIn($parameter, $arrayMatches[1]);

            return;
        }

        // basic where expression
        $this->appendQueryParameterAsBasicWhere($parameter);
    }

    private function appendQueryParameterAsBasicWhere($parameter)
    {
        try {
            preg_match(self::PATTERN, $parameter, $matches);

            if (empty($matches)) {
                return;
            }

            $operator = $matches[0];

            [$key, $value] = explode($operator, $parameter);

            if (strpos($value, '::') !== false) {
                $valueList = explode('::', $value);
                $value = $valueList[1];

                switch ($valueList[0]) {
                    case 'eq':
                        $operator = '=';
                        break;
                    case '!eq':
                        $operator = '!=';
                        break;
                    case 'gt':
                        $operator = '>';
                        break;
                    case 'gteq':
                        $operator = '>=';
                        break;
                    case 'lt':
                        $operator = '<';
                        break;
                    case 'lteq':
                        $operator = '<=';
                        break;
                }
            }

            $value = urldecode($value);

            // to avoid string like %CA , %Ba , all strings starts with % cancatenated with chars from A to F , produce (?)
            if (!$this->isConstantParameter($key) && $this->isLikeQuery($value)) {
                $operator = 'like';
                $value = Str::replace('*', '%', $value);
            }

            $this->queryParameters[] = [
                'type' => 'Basic',
                'key' => $key,
                'operator' => $operator,
                'value' => $value,
            ];
        } catch (\Throwable|\Exception $e) {

        }
    }

    private function appendQueryParameterAsWhereIn($parameter, $key)
    {
        if (Str::contains($parameter, '!=')) {
            $type = 'NotIn';
            $seperator = '!=';
        } else {
            $type = 'In';
            $seperator = '=';
        }

        $index = null;
        foreach ($this->queryParameters as $_index => $queryParameter) {
            if ($queryParameter['type'] == $type && $queryParameter['key'] == $key) {
                $index = $_index;
                break;
            }
        }

        if ($index !== null) {
            $this->queryParameters[$index]['values'][] = explode($seperator, $parameter)[1];
        } else {
            $this->queryParameters[] = [
                'type' => $type,
                'key' => $key,
                'values' => [explode($seperator, $parameter)[1]],
            ];
        }
    }

    public function hasQueryUri()
    {
        return $this->queryUri;
    }

    public function getQueryUri()
    {
        return $this->queryUri;
    }

    public function hasQueryParameters()
    {
        return count($this->queryParameters) > 0;
    }

    public function hasQueryParameter($key)
    {
        $keys = \Arr::pluck($this->queryParameters, 'key');

        return in_array($key, $keys);
    }

    private function isLikeQuery($query)
    {
        $pattern = "/^\*|\*$/";

        return preg_match($pattern, $query, $matches);
    }

    private function isConstantParameter($key)
    {
        return in_array($key, $this->constantParameters);
    }
}
