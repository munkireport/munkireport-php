<?php
$this->view('partials/json_header');

$data = array("meta" => $meta, "machine" => $machine);

echo json_encode($data);