<?php

namespace munkireport\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use munkireport\builders\MRQueryBuilder as Builder;

class MRModel extends Eloquent
{
  protected function newBaseQueryBuilder()
  {
   $connection = $this->getConnection();

     return new Builder(
          $connection, $connection->getQueryGrammar(), 
             $connection->getPostProcessor()
    );
  }

  public function toLabelCount()
  {
      $out = [];
      foreach($this->toArray() as $label => $value){
          $out[] = ['label' => $label, 'count' => $value];
      }
      return $out;
  }

  public $timestamps = false;

}