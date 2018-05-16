<?php
class Detectx_model extends \Model
{

    function __construct($serial='')
    {
        parent::__construct('id', 'detectx'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['searchdate'] = 0;
        $this->rs['numberofissues'] = 0;
        $this->rs['status'] = '';
        $this->rs['scantime'] = 0;
        $this->rs['spotlightindexing'] = true;
        $this->rs['registered'] = true;
        $this->rs['infections'] = '';
        $this->rs['issues'] = '';

        $this->serial_number = $serial;
    }

    // ------------------------------------------------------------------------


    /**
   * Process data sent by postflight
   *
   * @param  string data
   * @author wardsparadox
   * based on homebrew by tuxudo
   **/
    function process($json)
    {
        // Check if data was uploaded
        if (! $json ) {
            throw new Exception("Error Processing Request: No JSON file found", 1);
        }
        // Delete previous set
        $this->deleteWhere('serial_number=?', $this->serial_number);

        // Process json into object thingy
        $data = json_decode($json, true);
        $this->searchdate = strtotime($data['searchdate']);
        $this->scantime = isset($data['duration']) ? $data['duration'] : 0;
        $this->spotlightindexing = (int) $data['spotlightindexing'];
        $this->registered = (int) $data['registered'];
        $len = count($data['infections']);
        $lis = count($data['issues']);
        if ($len > 0) {
            $this->status = "Infected";
            foreach ($data['issues'] as $issue) {
                $this->issues .= ($issue . ";");
            }
            foreach ($data['infections'] as $infectionname) {
                $this->numberofissues += 1;
                $this->infections .= ($infectionname . ";");
            }
        } else if ($lis > 0) {
            $this->status = "Issues";
            foreach ($data['issues'] as $issue) {
                $this->issues .= ($issue . ";");
            }
            foreach ($data['issues'] as $issuesname) {
                $this->numberofissues += 1;
                $this->issues .= ($issuesname . ";");
            }
        } else {
            $this->status = "Clean";
            $this->issues = 'No Issues Detected';
            $this->numberofissues = 0;
        }
        $this->save();
    }
}
