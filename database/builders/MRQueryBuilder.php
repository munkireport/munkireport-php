<?php

namespace munkireport\builders;

use Illuminate\Database\Query\Builder as QueryBuilder;

class MRQueryBuilder extends QueryBuilder{

  public function whereSerialNumber($serial_number)
  {
    $this->where($this->from.'.serial_number', $serial_number);
  }

  public function filter($what = '')
  {
      $this->filterMachineGroup();
      if($what != 'groupOnly'){
        $this->filterArchived();
      }
      return $this;
  }

  private function filterMachineGroup()
  {
      $key = 'serial_number';
      $table = 'reportdata';
      if($this->from != $table){
          $this->join(
              $table,
              $table.'.'.$key,
              '=',
              $this->from.'.'.$key
          );
      }
      if ($groups = get_filtered_groups()) {
          $this->whereIn('machine_group', $groups);
      }
  }

  private function filterArchived()
  {
    if( is_archived_filter_on()) {
        $this->where('reportdata.archive_status', 0);
    }elseif( is_archived_only_filter_on() ){
        $this->where('reportdata.archive_status', '!=', 0);
    }
  }

  public function insertChunked(array $values, int $chunkSize = 0)
  {
      if (empty($values)) {
          return true;
      }

      if (! is_array(reset($values))) {
          $values = [$values];
      }

      if ( $chunkSize === 0 ){
          // Calculate chunksize based on SQLite limits (max 999 inserts)
          $itemCount = count($values[0]);
          $chunkSize = floor(999 / $itemCount);
      }

      // Insert chunked data
      foreach( array_chunk ($values , $chunkSize, TRUE ) as $chunk ){
          $this->insert($chunk);
      }
  }
}
