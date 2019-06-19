<?php

namespace munkireport\builders;

use Illuminate\Database\Query\Builder as QueryBuilder;

class MRQueryBuilder extends QueryBuilder{
  
  public function whereSerialNumber($serial_number)
  {
    $this->where($this->from.'.serial_number', $serial_number);
  }
  
  public function filter()
  {
      $this->filterMachineGroup();
      return $this;
  }
  
  public function filterMachineGroup()
  {
      $key = 'serial_number';
      $table = 'reportdata';
      // echo '<pre>';dd($this->from);
      $this->join(
          $table, 
          $table.'.'.$key, 
          '=', 
          $this->from.'.'.$key,
      );
      if ($groups = get_filtered_groups()) {
          $this->whereIn('machine_group', $groups);
      }
  }
}
