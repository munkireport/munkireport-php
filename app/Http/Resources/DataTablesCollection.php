<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DataTablesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into a format that DataTables.NET supports by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'data' => $this->collection,
        ];
    }
}
