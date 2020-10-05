<!-- Minel SAYGISEVER -->

<?php

	include('config/db_connect.php');

	$province = $district = $sql = '';
	$events = [];

	//search.php hem index.php den hem de kendi içerisinden değer alabilir.
	//$_GET kullanarak kontrol ettiğim index.php'den gelen değerler, $_POST kendi içerisinden gelen değereler

	//önce ilçe kontrolü yapıyorum, ilçe girilmeyip il girilme ihtimali var.
	//ilçe id'sini biliyorsak ile bakmaya gerek yok.
	if(isset($_GET['district']) && $_GET['district'] != 'Select District'){
		$eventDistrict = mysqli_real_escape_string($conn, $_GET['district']);
		$sql = "SELECT * FROM events WHERE event_district='$eventDistrict'";
		$result  = mysqli_query($conn, $sql);
	
		$events = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	else if (isset($_GET['province']) && $_GET['province'] != 'Select Province') {
		$eventProvince = mysqli_real_escape_string($conn, $_GET['province']);
		$sql = "SELECT * FROM events WHERE event_province='$eventProvince'";
		$result  = mysqli_query($conn, $sql);
	
		$events = mysqli_fetch_all($result, MYSQLI_ASSOC);
		
	}

	if(isset($_POST['searchProvince'])){

		//eğer hiçbir şey girilmemişse anasayfaya yönlendiriyorum.
		if($_POST['province'] == 'Select Province'){
			header('Location index.php');
		}
		//il girilip ilçe girilmediyse ile göre arıyorum
		else if($_POST['province'] != 'Select Province' && $_POST['district'] == 'Select District'){
			$eventProvince = mysqli_real_escape_string($conn, $_POST['province']);
			$sql = "SELECT * FROM events WHERE event_province='$eventProvince'";
			$result  = mysqli_query($conn, $sql);
	
			$events = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
		//ikisi de girildiyse ilçeye göre arıyorum
		else{
			$eventDistrict = mysqli_real_escape_string($conn, $_POST['district']);
			$sql = "SELECT * FROM events WHERE event_district='$eventDistrict'";
			$result  = mysqli_query($conn, $sql);
	
			$events = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

	}

	
	
	
	

	$provinces = mysqli_query($conn, 'SELECT * FROM city');

	//mysqli_free_result($result);

	//mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
	<?php include('template/header.php')?>

	<h4 class="center grey-text">Search Results</h4>

	<div class="container">
		<div class="row">

			<?php 
			foreach ($events as $event): ?>
				<div class="col s6 md3">
					<div class="card z-depth-0">

						<img src="img/<?php if($event['event_category'] == "Stage"){ echo 'theater.png'; }
						else if($event['event_category'] == "Music"){ echo 'microphone.png'; }	
						else if($event['event_category'] == "Sport"){ echo 'medal.png';}
						else {echo 'megaphone.png'; }?>" class="event">

						<div class="card-content center">
							<h6><?php echo htmlspecialchars($event['event_name']);?></h6>
							<div><?php echo htmlspecialchars($event['event_description']);?></div>
						</div>
						<div class="card-action right-align">
							<a class="brand-text" href="details.php?event_id=<?php echo $event['event_id']?>">More info</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

			<?php if(empty($events)): ?>
					<br><br>
				<h5 class="center">There are no events matching your criteria.</h5>
				<?php endif; ?>
		</div>
	</div>

	<form action="" method="POST" >
		<!-- il seçimi, change_province() fonksiyonu ile sonuçlarına göre ilçeler çıkıyor, fonksiyon header.php'de-->
		<select id="sprovince" name="province" class="browser-default" onChange="change_province()" style="color: grey;">
			<option>Select Province</option>
			<?php 
						
				while($row=mysqli_fetch_array($provinces)){?>
					<option value = "<?php echo $row['CityID'];?>"><?php echo $row['CityName'];?></option>
			<?php }?>
		</select>
			
		<!-- ilçe seçimi -->	
		<select class="browser-default" id="sdistrict"  name="district" style="color: grey;">
				<option>Select District</option>
		</select>	
					
			
		<br>
		<div class="center">
			<input type="submit" name="searchProvince" value="Search" class="btn  btn-small brand z-depth-0">
		</div>
	</form>	
		

	<?php include('template/footer.php')?>

</html>
