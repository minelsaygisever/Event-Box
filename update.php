<!-- Minel SAYGISEVER -->

<?php
	
	include('config/db_connect.php');

	if(isset($_GET['event_id'])){

		$event_id = mysqli_real_escape_string($conn, $_GET['event_id']);

	}

	
	//sayfanın, başlangıçta ilgili etkinliğin bilgilerinin dolu hali ile açılmasını istedim
	$sqlSelect = "SELECT * FROM events WHERE event_id = $event_id";

	$result = mysqli_query($conn, $sqlSelect);

	$event = mysqli_fetch_assoc($result);
	
	$eventName = $event['event_name'];
	$eventDescription = $event['event_description'];
	$eventCategory = $event['event_category'];
	$eventImage = $event['event_image'];
	$eventProvince = $event['event_province'];
	$eventDistrict = $event['event_district'];
	$eventStartingDate = $event['event_starting_date'];
	$eventEndDate = $event['event_end_date'];

	//hata kontrollerini bir arrayde topladım
	$errors = array('eventName'=>'', 'eventDescription'=>'', 'eventCategory'=>'', 'eventImage'=>'', 'eventProvince'=>'', 'eventDistrict'=>'', 'eventStartingDate'=>'','eventEndDate'=>'');



	if(isset($_POST['update'])){

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

			date_default_timezone_set('Europe/Istanbul');
				$sqlDate = date('Y-m-d h:i:s', time());

			//resim koymayı opsiyonel yaptığım içim, resim olması ve olmaması durumunu farklı query'ler kullanırak hallettim
			if($eventImage != null){
				$sql = "UPDATE events SET event_name='$eventName', event_description='$eventDescription', event_category='$eventCategory', event_image='$eventImage', event_province='$eventProvince', event_district='$eventDistrict', event_starting_date='$eventStartingDate', event_end_date='$eventEndDate', update_time='$sqlDate' WHERE event_id='$event_id'";

				if(move_uploaded_file($_FILES['eventImage']['tmp_name'], $target)){
					echo "Image uploaded successfully.";
				}
				else{
					echo "There was a problem uploading image.";
				}
					
			}
			else{

				$sql = "UPDATE events SET event_name='$eventName', event_description='$eventDescription', event_category='$eventCategory', event_image='',event_province='$eventProvince', event_district='$eventDistrict', event_starting_date='$eventStartingDate', event_end_date='$eventEndDate', update_time='$sqlDate' WHERE event_id=$event_id";
			}
			
			if(mysqli_query($conn, $sql)){
				header('Location: details.php?event_id='.$event_id);
			} 
			else{
				echo 'Query error: ' . mysqli_error($conn);
			}


		}
	}

	//il seçiminde kullanılacak
	$provinces = mysqli_query($conn, 'SELECT * FROM city');
?>

<!DOCTYPE html>
<html>
	<?php include('template/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Update an Event</h4>
		<?php if($event): ?>
			<form class="white" action="update.php?event_id=<?php echo $event_id ?>"" method="POST" enctype="multipart/form-data">
				<label>Name of the event:</label>
				<div class="red-text"><?php echo $errors['eventName']?></div>
				<input type="text" name="eventName" value="<?php echo htmlspecialchars($eventName) ?>">

				<label>Description:</label>
				<div class="red-text"><?php echo $errors['eventDescription']?></div>
				<input type="text" name="eventDescription"value="<?php echo htmlspecialchars($eventDescription) ?>">

				<label>Category:</label>
				<div class="red-text"><?php echo $errors['eventCategory']?></div>
				<input type="text" name="eventCategory" value="<?php echo htmlspecialchars($eventCategory) ?>">


				<label>Image:</label>
				<br><br>
				<div class="red-text"><?php echo $errors['eventImage']?></div>
				<input type="hidden" name="size" value="1000000">
				<div>
					<input type="file" name="eventImage">
				</div>
				

				<br>
				<table>
				<tr>
				<td>
					<!-- il seçimi, change_province() fonksiyonu ile sonuçlarına göre ilçeler çıkıyor, fonksiyon header.php'de-->
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
					<!-- ilçe seçimi -->	
					<select name="district" class="browser-default" style="color: grey;">
						<option>Select District</option>
					</select>	
					
					</div>
					<div class="red-text"><?php echo $errors['eventDistrict']?></div>
				</td>
				</tr>
			</table>

				<label>Starting date (yyyy-mm-dd):</label>
				<div class="red-text"><?php echo $errors['eventStartingDate']?></div>
				<input type="text" name="eventStartingDate" value="<?php echo htmlspecialchars($eventStartingDate) ?>">

				<label>End date (yyyy-mm-dd):</label>
				<div class="red-text"><?php echo $errors['eventEndDate']?></div>
				<input type="text" name="eventEndDate" value="<?php echo htmlspecialchars($eventEndDate) ?>">

				<div class="center">
					<input name="update" type="submit" value="Update" class="btn brand z-depth-0">
				</div>
			</form>
		<?php else: ?>

		<h5>No such event exists!</h5>

		<?php endif; ?>
	</section>

	<?php include('template/footer.php')?>

</html>