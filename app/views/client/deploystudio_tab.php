<?php //Initialize models needed for the table
$deploystudio_obj = new Dsw_model($serial_number);
?>

    <h2>DeployStudio</h2>

      <table class="table table-striped">
        <tbody>
          <tr>
            <td>Workflow used</td>
            <td><?php echo $deploystudio_obj->workflow; ?></td>
          </tr>
        </tbody>
      </table>
