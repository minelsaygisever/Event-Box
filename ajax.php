<!-- Minel SAYGISEVER -->

<!-- belli bir ile ait ilçeleri göstermek için kullanılıyor -->

<?php 

	include('config/db_connect.php');

	$province=$_GET["province"];

	if($province != "Select Province"){

	$sql=mysqli_query($conn, "SELECT * FROM town WHERE CityID=$province");
	
	echo '<select id="sdistrict" name="district" class="browser-default" style="color: grey;">';
	echo '<option>Select District</option>';
	//while içinde tek tek optionlar yaratılıyor.
	while($row=mysqli_fetch_array($sql)){
		echo "<option value=".$row['TownID'].">"; 
		echo $row['TownName'];
		echo "</option>";
	}
	echo "</select>";
	}

?>