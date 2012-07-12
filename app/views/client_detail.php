<?$this->view('partials/head')?>

<?$report_type = (object) array('name'=>'Munkireport', 'desc' => 'munkireport')?>
<?$this->view('partials/machine_info', array('report_type' => $report_type))?>


<?$this->view('partials/foot')?>
