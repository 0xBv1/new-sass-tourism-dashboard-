<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    protected array $relations = [];

    protected array $data;

    public function toArray($request): array
    {
        $this->data = $this->resource->toArray();

        $this->prepareRelations();

        return $this->data;
    }

    protected function prepareRelations(): void
    {
        unset($this->data['translation'], $this->data['translations']);

        foreach ($this->relations as $relation => $resource) {
            if (!empty($this->data[$relation])) {
                $this->data[$relation] = $resource['type'] == 'collection'?
                    $resource['resourceClass']::collection($this->resource->$relation):
                    new $resource['resourceClass']($this->resource->$relation);
            }
        }
    }
}
