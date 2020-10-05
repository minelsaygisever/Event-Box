<!-- Minel SAYGISEVER -->

<?php

	include('config/db_connect.php');
	
	$eventName = $eventDescription = $eventCategory = $eventImage = $eventProvince = $eventDistrict = $eventStartingDate = $eventEndDate = '';

	$errors = array('eventName'=>'', 'eventDescription'=>'', 'eventCategory'=>'', 'eventImage'=>'', 'eventProvince'=>'', 'eventDistrict'=>'', 'eventStartingDate'=>'','eventEndDate'=>'');

    
	if(isset($_POST['submit'])){

		//hata kontrolleri burada tek tek yapılıyor
		//eğer hatalar olursa bile verileri değişkenlere attığım için 
		//sayfa tekrar yüklendiğinde yeni girdiğim değerler kaybolmayacak
		if(empty($_POST['eventName'])){
			$errors['eventName'] = 'A Name is required <br />';
		}
		else{
			$eventName = $_POST['eventName'];
		}

		if(empty($_POST['eventDescription'])){
			$errors['eventDescription'] = 'A description is required <br />';
		}
		else{
			$eventDescription = $_POST['eventDescription'];
		}

		if(empty($_POST['eventCategory'])){
			$errors['eventCategory'] = 'A category is required <br />';
		}
		else{
			$eventCategory = $_POST['eventCategory'];
		}

		if(!empty($_FILES['eventImage'])){
			$eventImage = $_FILES['eventImage']['name'];
			$target = "img/".basename($eventImage);
		}




		if($_POST['province'] == 'Select Province'){
			$errors['eventProvince'] = 'You must enter the province <br />';
		}
		else {
			$eventProvince = $_POST['province'];
		}

		if($_POST['district'] == 'Select District'){
			$errors['eventDistrict'] = 'You must enter the district <br />';
		}
		else{
			$eventDistrict = $_POST['district'];
			
		}



		if(empty($_POST['eventStartingDate'])){
			$errors['eventStartingDate'] = 'A starting date is required <br />';
		} 
		else{
			$eventStartingDate = $_POST['eventStartingDate'];
			if(!preg_match('/^([0-9][0-9][0-9][0-9][-]([0][1-9]|[1][012])[-]([0][1-9]|[12][0-9]|[3][01]))+$/', $_POST['eventStartingDate'])){
			$errors['eventStartingDate'] = 'Wrong date!';
			} 
		}
		
			

		if(empty($_POST['eventEndDate'])){
			$errors['eventEndDate'] = 'An end date is required <br />';
		} 
		else {
			$eventEndDate = $_POST['eventEndDate'];
			if(!preg_match('/^([0-9][0-9][0-9][0-9][-]([0][1-9]|[1][012])[-]([0][1-9]|[12][0-9]|[3][01]))+$/', $_POST['eventEndDate'])){
			$errors['eventEndDate'] = 'Wrong date!';
			}
		}

		//eğer hata yoksa database'e yazıyorum
		if(!array_filter($errors)){

			$eventName = mysqli_real_escape_string($conn, $_POST['eventName']);
			$eventDescription = mysqli_real_escape_string($conn, $_POST['eventDescription']);
			$eventCategory = mysqli_real_escape_string($conn, $_POST['eventCategory']);
			$eventProvince = mysqli_real_escape_string($conn, $_POST['province']);
			$eventDistrict = mysqli_real_escape_string($conn, $_POST['district']);
			$eventStartingDate = mysqli_real_escape_string($conn, $_POST['eventStartingDate']);
			$eventEndDate = mysqli_real_escape_string($conn, $_POST['eventEndDate']);


			$sql = "INSERT INTO events(event_name, event_description, event_category, event_image, event_province, event_district, event_starting_date, event_end_date) VALUES('$eventName', '$eventDescription', '$eventCategory', '$eventImage', '$eventProvince', '$eventDistrict', '$eventStartingDate', '$eventEndDate')";

			if(mysqli_query($conn, $sql)){
				header('Location: index.php');
			} 
			else{
				echo 'Query error: ' . mysqli_error($conn);
			}

			if(move_uploaded_file($_FILES['eventImage']['tmp_name'], $target)){
				echo "Image uploaded successfully.";
			}
			else{
				echo "There was a problem uploading image.";
			}
		}
	}

	$provinces = mysqli_query($conn, 'SELECT * FROM city');


?>

<!DOCTYPE html>
<html>
	<?php include('template/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Add an Event</h4>
		<form class="white" action="add.php" method="POST" enctype="multipart/form-data">
			<p>Name of the event:</p>
			<div class="red-text"><?php echo $errors['eventName']?></div>
			<input type="text" name="eventName" value="<?php echo htmlspecialchars($eventName) ?>">

			<p>Description:</p>
			<div class="red-text"><?php echo $errors['eventDescription']?></div>
			<input type="text" name="eventDescription"value="<?php echo htmlspecialchars($eventDescription) ?>">

			<p>Category:</p>
			<div class="red-text"><?php echo $errors['eventCategory']?></div>
			<input type="text" name="eventCategory" value="<?php echo htmlspecialchars($eventCategory) ?>">


			<p>Image:</p>
			
			<div class="red-text"><?php echo $errors['eventImage']?></div>
			<input type="hidden" name="size" value="1000000">
			<div>
				<input type="file" name="eventImage">
			</div>
			

			<br>
				
			<table>
				<tr>
				<td>
					<select id="sprovince" name="province" class="browser-default" onChange="change_province()" style="color: grey;">
							<option>Select Province</option>
							<?php 
						
							while($row=mysqli_fetch_array($provinces)){?>
							<option value = "<?php echo $row['CityID'];?>"><?php echo $row['CityName'];?></option>
						<?php }?>
					</select>
					<div class="red-text"><?php echo $errors['eventProvince']?></div>
				</td>
				</tr>

				<tr>
				<td>
					<div id="sdistrict"  >
					<select name="district" class="browser-default" style="color: grey;">
						<option>Select District</option>
					</select>	
					
					</div>
					<div class="red-text"><?php echo $errors['eventDistrict']?></div>
				</td>
				</tr>
			</table>
						
		<br>
				
			<p>Starting date (yyyy-mm-dd):</p>
			<div class="red-text"><?php echo $errors['eventStartingDate']?></div>
			<input type="text" name="eventStartingDate" value="<?php echo htmlspecialchars($eventStartingDate) ?>">

			<p>End date (yyyy-mm-dd):</p>
			<div class="red-text"><?php echo $errors['eventEndDate']?></div>
			<input type="text" name="eventEndDate" value="<?php echo htmlspecialchars($eventEndDate) ?>">

			<div class="center">
				<input name="submit" type="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

	<?php include('template/footer.php')?>

</html>