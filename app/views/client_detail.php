<?$this->view('partials/head')?>

<?$report_type = (object) array('name'=>'Munkireport', 'desc' => 'munkireport')?>
<?$this->view('partials/machine_info', array('report_type' => $report_type))?>

<h2>Warranty</h2>
<?$this->view('partials/warranty')?>

<h2>Installed Apple Software</h2>
<?$this->view('partials/install_history', array('apple'=> TRUE))?>

 <h2>Installed Third-Party Software</h2>
<?$this->view('partials/install_history', array('apple'=> FALSE))?>


<?$this->view('partials/foot')?>
