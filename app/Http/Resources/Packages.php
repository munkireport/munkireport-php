<?php

namespace App\Http\Resources;

use Composer\Package\CompletePackage;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Packages extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function (CompletePackage $package) {
           return [
               'name' => $package->getName(),
               'prettyName' => $package->getPrettyName(),
               'version' => $package->getVersion(),
               'prettyVersion' => $package->getPrettyVersion(),
               'description' => $package->getDescription(),
           ];
        })->toArray();
    }
}
