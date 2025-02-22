<?php include('partials/menu.php'); ?>

<div class="main-content">
	<div class="wrapper">
		<h1>Add Food</h1>
		
		<br><br>

		<?php
			if(isset($_SESSION['upload']))
			{
				echo $_SESSION['upload'];
				unset($_SESSION['upload']);
			}
		?>

		<br><br>

		<form action="" method="POST" enctype="multipart/form-data">

			<table class="tbl-30">
				<tr>
					<td>Title: </td>
					<td>
						<input type="text" name="title" placeholder="Title of the Food">
					</td>
				</tr>

				<tr>
					<td>Description: </td>
					<td>
						<textarea name="description" cols="30" rows="5" placeholder="Description of the Food"></textarea>
					</td>
				</tr>

				<tr>
					<td>Price: </td>
					<td>
						<input type="number" name="price">
					</td>
				</tr>

				<tr>
					<td>Select Image: </td>
					<td>
						<input type="file" name="image">
					</td>
				</tr>

				<tr>
					<td>Category: </td>
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
										$id = $row['id'];
										$title = $row['title'];
										?>

										<option value="<?php echo $id; ?>"><?php echo $title; ?></option>

										<?php
									}
								}
								else
								{
									?>
									<option value="0">No Category Found</option>
									<?php
								}
							?>

						</select>
					</td>
				</tr>

				<tr>
					<td>Featured: </td>
					<td>
						<input type="radio" name="featured" value="Yes"> Yes
						<input type="radio" name="featured" value="No"> No
					</td>
				</tr>

				<tr>
					<td>Active: </td>
					<td>
						<input type="radio" name="active" value="Yes"> Yes
						<input type="radio" name="active" value="No"> No
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<input type="submit" name="submit" value="Add Food" class="btn-secondary">
					</td>
				</tr>

			</table>
			
		</form>

		<?php 

			//Check whether the submit button is clicked or not
			if(isset($_POST['submit']))
			{
				//echo "Clicked";

				$title = $_POST['title'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$category = $_POST['category'];


				//For radio input, we need to check whether the button is clicked or not
				if(isset($_POST['featured']))
				{

					$featured = $_POST['featured'];
				}
				else
				{
					$featured = "No";
				}

				if(isset($_POST['active']))
				{
					$active = $_POST['active'];
				}
				else
				{
					$active = "No";
				}

				//Check whether the image is selected or not and sey the value for image name accordingly
				//print_r($_FILES['image']);

					//die();  //Break the code here

					if(isset($_FILES['image']['name']))
					{
						//Upload the image
						//To upload image we need image name, source path and destination path
						$image_name = $_FILES['image']['name'];

						//Upload the image only if image is selected
						if($image_name != "")
						{

							//Auto rename our image
							//Get the extension of our image(jpg, png, gif, etc.)
							$ext = end(explode('.', $image_name));

							//Rename the image
							$image_name = "Food-Name-".rand(000, 999).'.'.$ext;
							
							$source_path = $_FILES['image']['tmp_name'];

							$destination_path = "../images/food/".$image_name;

							//Finally upload the image
							$upload = move_uploaded_file($source_path,  $destination_path);

							//Check whether image is uploaded or not
							//And if the image is not uploaded then we will stop the precess and redirect with error message
							if($upload==false)
							{
								$_SESSION['upload'] = "<div class='error'>Failed to Upload Image. </div>";
								header('location:'.SITEURL.'admin/add-food.php');
								die();
							}

						}

					}
					else
					{
						//Don't upload image and set the image_name value as blank
						$image_name ="";
					}

				//2.Create sql query to insert category into database
				$sql2 = "INSERT INTO tbl_food SET 
					title = '$title',
					description = '$description',
					price = $price,
					image_name='$image_name',
					category_id = $category,
					featured = '$featured',
					active = '$active'
				";

				//3. Execute the query and save into database
				$res2 = mysqli_query($conn, $sql2);

				//4. Check whether the query is executed or not data inserted or not
				if($res2==true)
				{
					//Query executed and category added
					$_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
					header('location:'.SITEURL.'admin/manage-food.php');
				}
				else
				{
					//Failed to add category 
					$_SESSION['add'] = "<div class='error'>Failed to Add Food.</div>";
					header('location:'.SITEURL.'admin/add-food.php');
				}
			}

		?>
	</div>
</div>

<?php include('partials/footer.php'); ?>