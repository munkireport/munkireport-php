<?php


namespace App\Faker;

use Faker\Provider\Base as FakerBase;
use Illuminate\Support\Arr;

class MacProvider extends FakerBase
{
    private $modelIdentifiers = [];

    private function populateModelIdentifiers()
    {
        $this->modelIdentifiers[] = 'iMacPro1,1';

        $this->modelIdentifiers = array_merge(
            $this->modelIdentifiers, array_map(
            function ($i) {
                return "iMac${i},1";
            },
            range(1, 19)
        ));

        $this->modelIdentifiers = array_merge(
            $this->modelIdentifiers, array_map(
            function ($i) {
                return "MacBookPro${i},1";
            },
            range(1, 16)
        ));

        $this->modelIdentifiers = array_merge(
            $this->modelIdentifiers, array_map(
            function ($i) {
                return "Macmini${i},1";
            },
            range(1, 8)
        ));

        $this->modelIdentifiers = array_merge(
            $this->modelIdentifiers, array_map(
            function ($i) {
                return "MacBookAir${i},1";
            },
            range(1, 9)
        ));

        $this->modelIdentifiers = array_merge(
            $this->modelIdentifiers, array_map(
            function ($i) {
                return "MacPro${i},1";
            },
            range(1, 7)
        ));
    }

    public function __construct()
    {
        $this->populateModelIdentifiers();
    }

    public function macModelName(): string
    {
        return Arr::random([
            'Mac Pro',
            'iMac',
            'MacBook',
        ]);
    }

    public function macModelIdentifier(): string
    {
        return Arr::random($this->modelIdentifiers);
    }
}
