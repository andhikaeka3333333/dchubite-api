<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'category_id' => $this->id,
            'name' => $this->name,
            'products' => ProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
