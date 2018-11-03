<?php
class sccm_status_model extends \Model {

        public function __construct($serial='')
        {
              parent::__construct('id', 'sccm_status'); //primary key, tablename
              $this->rs['id'] = '';
              $this->rs['serial_number'] = $serial;
              $this->rs['agent_status'] = '';
              $this->rs['mgmt_point'] = '';
              $this->rs['enrollment_name'] = '';
              $this->rs['enrollment_server'] = '';     
              $this->rs['last_checkin'] = '';
              $this->rs['cert_exp'] = '';       

              if ($serial) {
                  $this->retrieve_record($serial);
                  $this->serial = $serial;
              }
        }
        
        // ------------------------------------------------------------------------

        /**
         * Process data sent by postflight
         *
         * @param string data
         * 
         **/
        public function process($data)
        {               
                // Translate network strings to db fields
                $translate = array(
                        'Status = ' => 'agent_status',
                        'Management_Point = ' => 'mgmt_point',
                        'Enrollment_User_Name = ' => 'enrollment_name',
                        'Enrollment_Server_Address = ' => 'enrollment_server',
                        'Last_Check_In_Time = ' => 'last_checkin',
                        'Client_Certificate_Expiry_Date = ' => 'cert_exp');

                        //clear any previous data we had
                        foreach($translate as $search => $field) {
                                $this->$field = '';
                        }
                
                        // Parse data
                        foreach(explode("\n", $data) as $line) {
                                // Translate standard entries
                                foreach($translate as $search => $field) {
                                        if(strpos($line, $search) === 0) {
                                                
                                            $value = substr($line, strlen($search));
                                                
                                            $this->$field = $value;
                                            break;
                                        }
                                }
                        } //end foreach explode lines
                        $this->save();
        }
}
