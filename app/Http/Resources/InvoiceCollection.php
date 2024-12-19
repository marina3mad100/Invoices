<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InvoiceCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = 'App\Http\Resources\InvoiceResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'links' => [
                "first_page_url" => $this->url(1),
                "last_page_url" => $this->url($this->lastPage()),
                "prev_page_url" => $this->previousPageUrl(),
                "next_page_url" => $this->nextPageUrl()
            ],
            'meta' => [
                "current_page" => $this->currentPage(),
                'from' => $this->firstItem(),
                "last_page" => $this->lastPage(),
                "per_page" => $this->perPage(),
                'to' => $this->lastItem(),
                "total" => $this->total()
            ]

        ];
    }

}
