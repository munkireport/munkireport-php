<?php
class Detectx_model extends \Model {

  function __construct($serial='')
  {
  parent::__construct('id', 'detectx'); //primary key, tablename
    $this->rs['id'] = '';
    $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
    $this->rs['searchdate'] = 0; $this->rt['searchdate'] = 'BIGINT';
    $this->rs['numberofissues'] = 0;
    $this->rs['status'] = '';
    $this->rs['scantime'] = 0;
    $this->rs['spotlightindexing'] = true; $this->rt['spotlightindexing'] = 'BOOLEAN';
    $this->rs['registered'] = true; $this->rt['registered'] = 'BOOLEAN';
    $this->rs['infections'] = ''; $this->rt['infections'] = 'TEXT';
    $this->rs['issues'] = ''; $this->rt['issues'] = 'TEXT';
    
  // Schema version, increment when creating a db migration
    $this->schema_version = 0;

  // Add indexes
    $this->idx[] = array('numberofissues');
    $this->idx[] = array('searchdate');
    $this->idx[] = array('status');
    $this->idx[] = array('scantime');
    $this->idx[] = array('spotlightindexing');

    $this->serial_number = $serial;
  }

  // ------------------------------------------------------------------------


  /**
   * Process data sent by postflight
   *
   * @param string data
   * @author wardsparadox
   * based on homebrew by tuxudo
   **/
  function process($json)
  {
  // Check if data was uploaded
  if ( ! $json ){
  throw new Exception("Error Processing Request: No JSON file found", 1);
  }
  // Delete previous set
  $this->deleteWhere('serial_number=?', $this->serial_number);

  // Process json into object thingy
        $data = json_decode($json, true);
        $this->searchdate = strtotime($data['searchdate']);
        $this->scantime = isset($data['duration']) ? $data['duration'] : 0;
        $this->spotlightindexing = $data['spotlightindexing'];
        $this->registered = $data['registered'];
        $len = count($data['infections']);
        //$lis = count($data['issues']);
        if ($len > 0)
        {
          $this->status = "Infected";
          foreach($data['issues'] as $issue){
            $this->issues .= ($issue . ";");
            }
          foreach($data['infections'] as $infectionname){
            $this->numberofissues += 1;
            $this->infections .= ($infectionname . ";");
          }
        }
         else if ($lis > 0)
        {
          $this->status = "Issues";
          foreach($data['issues'] as $issue){
            $this->issues .= ($issue . ";");
            }
          foreach($data['issues'] as $issuesname){
            $this->numberofissues += 1;
            $this->issues .= ($issuesname . ";");
          }
        }
         else
        {
          $this->status = "Clean";
          $this->issues = 'No Issues Detected';
          $this->numberofissues = 0;
        }
        $this->save();
  }
}
