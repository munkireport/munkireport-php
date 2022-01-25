<?php


namespace Compatibility\Kiss\Contracts;


interface LegacyMachineGroup
{
//    public function __construct($groupid = '', $property = '')
//    {
//        parent::__construct('id', 'machine_group'); //primary key, tablename
//        $this->rs['id'] = '';
//        $this->rs['groupid'] = 0;
//        $this->rs['property'] = '';
//        $this->rs['value'] = '';
//
//        $this->idx[] = array('property');
//        $this->idx[] = array('value');
//
//        // Table version. Increment when creating a db migration
//        $this->schema_version = 0;
//
//        // Create table if it does not exist
//        //$this->create_table();
//
//        if ($groupid and $property) {
//            $this->retrieveOne('groupid=? AND property=?', array($groupid, $property));
//            $this->groupid = $groupid;
//            $this->property = $property;
//        }
//
//        return $this;
//    }

    /**
     * Get max groupid
     *
     * @return integer max groupid
     * @author AvB
     **/
    public function get_max_groupid(): int;

    /**
     * Select unique group ids
     *
     * @return array
     * @author
     **/
    public function get_group_ids(): array;

    // ------------------------------------------------------------------------

    /**
     * Retrieve all entries for groupid
     *
     * @param integer groupid
     * @return array
     * @author abn290
     **/
    public function all($groupid = ''): array;
}
