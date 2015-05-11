<?php //Initialize models needed for the table
$ds_obj = new Dsw_model($serial_number);
?>

    <h2>DeployStudio</h2>

      <table class="table table-striped">
        <tbody>
          <tr>
            <td>Workflow used</td>
            <td><?=$ds_obj->ds_workflow?></td>
          </tr>
        </tbody>
      </table>
