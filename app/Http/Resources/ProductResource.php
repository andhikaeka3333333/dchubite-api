<?php

namespace App\Http\Resources;

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category->name,
            'category_id' => $this->category->id,
            'name' => $this->name,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'image' => $this->image,
        ];
    }
}

