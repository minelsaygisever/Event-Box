<!-- Minel SAYGISEVER -->

<?php 

	include('config/db_connect.php');


	//delete tuşuna basıldığında
	if(isset($_POST['delete'])){

		$event_id_to_delete = mysqli_real_escape_string($conn, $_POST['event_id_to_delete']);

		$sql = "DELETE FROM events WHERE event_id = $event_id_to_delete";

		if(mysqli_query($conn, $sql)){
			header('Location: index.php');
		}
		else{
			echo 'Query error: ' . mysqli_error($conn);
		}

	}

	//index.php'den geliyor
	if(isset($_GET['event_id'])){

		$event_id = mysqli_real_escape_string($conn, $_GET['event_id']);

	}

	//update tuşuna basıldığında update.php'ye geçip güncelleme yapıyorum
	if(isset($_POST['update'])){
		$event_id_to_update = mysqli_real_escape_string($conn, $_POST['event_id_to_update']);
		header('Location: update.php?event_id='. $event_id_to_update);
	}

	$sql = "SELECT * FROM events WHERE event_id = $event_id";

	$result = mysqli_query($conn, $sql);

	$event = mysqli_fetch_assoc($result);

	//mysqli_free_result($result);
	//mysqli_close($conn);

?>

<!DOCTYPE html>
<html>

	<?php include('template/header.php'); ?>

	<div class="container center">
		<?php if($event): ?>

			<h3><?php echo htmlspecialchars($event['event_name']); ?></h3>

			<?php if($event['event_image'] != null): ?>
				<img src="img/<?php echo htmlspecialchars($event['event_image']); ?>" size="width:420px;">
			<?php endif; ?>


			<br><br>
			<h5 style="display: inline;">Category </h5>
			<p style="display: inline;"><?php echo htmlspecialchars($event['event_category']); ?></p>

			<p><?php echo htmlspecialchars($event['event_description']); ?></p>

			
			<h5 style="display: inline;">Place </h5>
			<p style="display: inline;"><?php 
				$eventDist=$event['event_district'];
			    $dist = mysqli_fetch_all(mysqli_query($conn, "SELECT TownName FROM town WHERE TownID ='$eventDist'"), MYSQLI_ASSOC);
			    foreach ($dist as $d){
			    	echo $d['TownName'];
			    }
			    ?>/
				<?php
				$eventProv=$event['event_province'];
			    $prov = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM city WHERE CityID ='$eventProv'"), MYSQLI_ASSOC);
			    
			    foreach ($prov as $p){
			    	echo $p['CityName'];
			    }
				?></p>
			<br/>
			<br/>
			<h5 style="display: inline;">Dates </h5>
			<p style="display: inline;">
				<?php echo htmlspecialchars(str_replace('-', '/', date("d-m-Y", strtotime($event['event_starting_date']))));?> - <?php echo htmlspecialchars(str_replace('-', '/', date("d-m-Y", strtotime($event['event_end_date']))));?>
			</p>

			<br/>
			<br/>
			
			<p>Created at <?php echo date(str_replace('-', '/', date("d-m-Y h:i:s", strtotime($event['created_time'])))); ?></p>

			
			<!-- etkinlik güncellenmediyse zaman gözükmez -->
			<?php if($event['update_time'] != null):?>
				<p>Updated at <?php echo date(str_replace('-', '/', date("d-m-Y h:i:s", strtotime($event['update_time'])))); ?></p>
			<?php endif; ?>
			


			<form action="details.php" method="POST">
				<!-- update butonu-->
				<input type="hidden" name="event_id_to_update" value="<?php echo $event['event_id'] ?>">
				<input type="submit" name="update" value="Update" class="btn brand z-depth-0">

				<!-- delete butonu-->
				<input type="hidden" name="event_id_to_delete" value="<?php echo $event['event_id'] ?>">
				<input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
			</form>

		<?php else: ?>

			<h5>No such event exists!</h5>

		<?php endif; ?>
	</div>

	<?php include('template/footer.php'); ?>

</html>