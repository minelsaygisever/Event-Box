<!-- Minel SAYGISEVER -->

<?php

	include('config/db_connect.php');

	//tüm eventleri göstermek için database'den bütün event'leri çekiyorum
	$sql = 'SELECT * FROM events';

	$result  = mysqli_query($conn, $sql);

	$events = mysqli_fetch_all($result, MYSQLI_ASSOC);

	//search işlemleri için
	//seçilen değerlere göre search.php'ye yönlendiriyorum, gerekli parametreleri de gönderiyorum
	if(isset($_POST['searchProvince'])){
		if($_POST['province'] == 'Select Province'){
			header('Location: index.php');
		}
		else{
			
			if($_POST['district'] != 'Select District'){
				
				$district = mysqli_real_escape_string($conn, $_POST['district']);
				header('Location: search.php?district='.$district); 
				
			}
			else if($_POST['province'] != 'Select Province'){

				$province = mysqli_real_escape_string($conn, $_POST['province']);
				header('Location: search.php?province='.$province); 
			}
		}
		
		
	}

	
    $provinces = mysqli_query($conn, 'SELECT * FROM city');
	

?>

<!DOCTYPE html>
<html>
	<?php include('template/header.php')?>

	<h4 class="center grey-text">EVENTS</h4>
	
	<!-- search bölümü -->
	<form action="" method="POST" >
		<select id="sprovince" name="province" class="browser-default" onChange="change_province()" style="color: grey;">
			<option>Select Province</option>
			<?php 
						
				while($row=mysqli_fetch_array($provinces)){?>
					<option value = "<?php echo $row['CityID'];?>"><?php echo $row['CityName'];?></option>
			<?php }?>
		</select>
				
		<select class="browser-default" id="sdistrict"  name="district" style="color: grey;">
				<option>Select District</option>
		</select>	
					
			
		<br>
		<div class="center">
			<input type="submit" name="searchProvince" value="Search" class="btn  btn-small brand z-depth-0">
		</div>
		
		
	</form>

	
	<!-- tüm eventler burada -->
	<div class="container">
		<div class="row">

			<!-- $events üst kısımda tanımlanıyor -->
			<?php foreach ($events as $event): ?>
			
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

		</div>
	</div>

	<?php include('template/footer.php')?>

</html>
