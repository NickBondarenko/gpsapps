<?php
//print_r($_POST);

?>
<script language="javascript">
function funEditUser(uid,cid,act)
{
	document.frmSubmit.txtDeviceId.value = uid;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
</script>
<div class="pagearea">
<div align="left" class="listofusers">List of  / <a href="?ch=addSimcard">Add Simcard</a></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>Name  </th>
        <th>Mobile Number</th>
        <th>Payment Status</th> 
        <th>Gsm Number</th>
        <th>Amount</th>
        <th>Added Date</th>
        <th>Edit</th>
        <th>Delete</th>
   </tr>
   <?php
    $sql = "SELECT * FROM tb_simcard WHERE clientid=".$_SESSION[userID]."";

   $rows = $db->query($sql);
   if($db->affected_rows > 0)
   { 
   	while ($record = $db->fetch_array($rows)) 
	{
		
		
	?>
		<tr>
        <td><?php echo ucfirst($record[name]);?></td>
        <td><?php echo ucfirst($record[number]);?></td>
        <td><?php echo $record[payment_status];?> </td> 
        <td><?php echo $record[gsm_number];?> </td>
        <td><?php echo $record[amount];?> </td>
        <td><?php echo $record['date'];?></td>
        <td><a href="?ch=addSimcard&id=<?php echo $record[id];?>">Edit Device</a></span></td>
        <td><a href="?ch=addSimcard&id=<?php echo $record[id];?>&del=1">Delete</a></span></td>
      
   </tr>
    <?php
		
	}
	}
	
   ?>
</table>
</div>
</div>
</div>
