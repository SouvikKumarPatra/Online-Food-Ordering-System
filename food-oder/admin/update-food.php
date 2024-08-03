<?php include('partials/menu.php'); ?>

<?php 

		//Check whether id is set or not
		if(isset($_GET['id']))
		{
			//Get id and all other details
			//echo "Getting the data";
			$id = $_GET['id'];
			//SQL query to get all other details
			$sql2 = "SELECT * FROM tbl_food WHERE id=$id";

			//Execute the query
			$res2 = mysqli_query($conn, $sql2);

			$row2 = mysqli_fetch_assoc($res2);

			//Get all the data
			$title = $row2['title'];
			$description = $row2['description'];
			$price = $row2['price'];
			$current_image = $row2['image_name'];
			$current_category = $row2['category_id'];
			$featured = $row2['featured'];
			$active = $row2['active'];
		}
		else
		{
			//Redirect to manage food
			header('location:'.SITEURL.'admin/manage-food.php');
		}

		?>

<div class="main-content">
	<div class="wrapper">
		<h1>Update Food</h1>

		<br><br>

		<form action="" method="POST" enctype="multipart/form-data">
			<table class="tbl-30">
				<tr>
					<td>Title: </td>
					<td>
						<input type="text" name="title" value="<?php echo $title; ?>">
					</td>
				</tr>

				<tr>
					<td>Description: </td>
					<td>
						<textarea name="description" cols="30" rows="5" placeholder="Description of the Food"><?php echo $description; ?></textarea>
					</td>
				</tr>

				<tr>
					<td>Price: </td>
					<td>
						<input type="number" name="price" value="<?php echo $price; ?>">
					</td>
				</tr>

				<tr>
					<td>Current Image: </td>
					<td>
						<?php 
						if($current_image != "")
						{
							//Display the image
							?>
							<img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px">
							<?php
						}
						else
						{
							//Display message
							echo "<div class='error'>Image not Added.</div>";	
						}
					?>
					</td>
				</tr>

				<tr>
					<td>Select New Image: </td>
					<td>
						<input type="file" name="image">
					</td>
				</tr>

				<tr>
					<td>Category:</td>
					<td>
						<select name="category">

							<?php 
								$sql = "SELECT * FROM tbl_category WHERE active='Yes'";

								$res = mysqli_query($conn, $sql);

								$count = mysqli_num_rows($res);

								if($count>0)
								{
									while($row=mysqli_fetch_assoc($res))
									{
										$category_title = $row['title'];
										$category_id = $row['id'];

										?>
										<option <?php if($current_category==$category_id){echo "selected";} ?> value="<? echo $category_id; ?>"><?php echo $category_title ?></option>
										<?php
									}
								}
								else
								{
									echo "<option value='0'>Category not Available.</option>";
								}
							?>

							
						</select>
					</td>
				</tr>

				<tr>
					<td>Featured: </td>
					<td>
						<input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes

						<input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No"> No
					</td>
				</tr>

				<tr>
					<td>Active: </td>
					<td>
						<input <?php if($active=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes"> Yes

						<input <?php if($active=="No"){echo "checked";} ?> type="radio" name="active" value="No"> No
					</td>
				</tr>

				<tr>
					<td>
						<input type="hidden" name="current_image"  value="<?php echo $current_image; ?>">

						<input type="hidden" name="id" value="<?php echo $id; ?>">

						<input type="submit" name="submit" value="Update Category" class="btn-secondary">
					</td>
				</tr>

			</table>
		</form>

		<?php 
			
			//Check whether the submit button is clicked or not
			if(isset($_POST['submit']))
			{
			 	//echo "Clicked";

				//1.Get all the details from form
				$id = $_POST['id'];
				$title = $_POST['title'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$current_image = $_POST['current_image'];
				$category = $_POST['category'];

				$featured = $_POST['featured'];
				$active = $_POST['active'];

				//2.Upload the image if selected

				//Checked wheather upload button is clicked or not
				if(isset($_FILES['image']))
				{
					//Upload button clicked
					$image_name = $_FILES['image']['name']; //New image name

					//Check whether the file is available or not
					if($current_image!="")
					{
						//Image is available
						//A.Uploading new image

						//Rename the image
						$ext =  end(explode('.', $image_name)); //Get the extension of the image

						$image_name = "Food-Name-".rand(0000, 9999).'.'.$ext; //This will be reaname the image

						//Get the source path and destination path
						$src_path = $_FILES['image']['tmp_name'];
						$dest_path = "../images/food/".$image_name;

						//Upload the image
						$upload = move_uploaded_file($src_path, $dest_path);

						//Check whether the image is uploaded or not
						if($upload==false)
						{
							//failed to upload
							$_SESSION['upload'] = "<div class='error'>Failed to Upload New Image.</div>";
							//Redirect to Mange Food
							header('location:'.SITEURL.'admin/manage-food.php');
							//Stop the proccess
							die();
						}
						//3.Remove the image if new image is uploaded and current image exists
						//B.Remove current image if available
						if($current_image!="")
						{
							//Current image is available
							//Remove the image
							$remove_path = "../images/food/".$current_image;

							$remove = unlink($remove_path);

							//Check whether the image is remove or not
							if($remove==false)
							{
								//Failed to remove current image
								$_SESSION['remove-failed'] = "<div class='error'>Failed to Remove Current Image.</div>";
								//Redirect to mange food
								header('location:'.SITEURL.'admin/manage-food.php');
								//Stop the process
								die();
							}
						}
					}
					else
					{
						$image_name = $current_image;
					}
				}
				else
				{
					$image_name = $current_image;
				}

				//4.Update the food in database
				$sql3 = "UPDATE tbl_food SET
					title = '$title',
					description = '$description',
					price = $price,
					image_name = '$image_name',
					category_id = '$category',
					featured = '$featured',
					active = '$active'
					WHERE id=$id
				";

				//Execute the query
				$res3 = mysqli_query($conn, $sql3);

				//Check whether query is executed or not
				if($res3==true)
				{
					// Executed
					$_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>";
					header('location:'.SITEURL.'admin/manage-food.php');
				}
				else
				{
					//Failed
					$_SESSION['update'] = "<div class='error'>Failed to Update Food.</div>";
					header('location:'.SITEURL.'admin/update-food.php');
				} 
			}
		?>	

	</div>
</div>

<?php include('partials/footer.php'); ?>