<?php $pageTitle = 'Add Property';
include_once './mvc/views/header.php'; ?>
<div class="container">
	<div class="actions-property">
		<div class="title">
			<h2><?php echo "Add Property"; ?></h2>
		</div>
		<div class="form-add">
			<form method="post" class="form" enctype="multipart/form-data">
				<div class="form-layout">
					<div class="add-property">
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
									<label>Property Type</label>
									<select name="property_type" id="" class="form-select">
										<option value="">Choose a type property</option>
										<option value="brand" <?php isset($_POST['property_type']) && $_POST['property_type'] == 'brand' ? "selected" :'';?>>Brand</option>
										<option value="category"<?php isset($_POST['property_type']) && $_POST['property_type'] == 'category' ? "selected" :'';?>>Category</option>
										<option value="tag" <?php isset($_POST['property_type']) && $_POST['property_type'] == 'tag' ? "selected" :'';?>>Tag</option>
									</select>
									<?php
									if (isset($errorPropertyType)) {
										echo "<div class='message-error'>" . $errorPropertyType . "</div>";
									}
									?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Property Name</label>
							<input type="text" name="property_name" 
							class="<?php if (isset($errorPropertyName)) { echo 'error'; } ?>" 
							value="<?php if (isset($errorPropertyName) || isset($_POST['add_property'])) { echo $_POST['property_name']; } ?>" aria-required="true" required>
							<?php
							if (isset($errorPropertyName)) {
								echo "<div class='message-error'>" . $errorPropertyName . "</div>";
							}
							?>
						</div>
						<div class="form-group">
							<label>Property Slug</label>
							<input type="text" name="property_slug"
							class="<?php if (isset($errorPropertySlug)) { echo 'error';} ?>" 
							value="<?php if (isset($errorPropertySlug) || isset($_POST['add_property'])) { echo $_POST['property_slug']; } ?>">
							<?php 
								if (isset($errorPropertySlug)) {
									echo "<div class='message-error'>" . $errorPropertySlug . "</div>";
								}
							?>
						</div>
						<div class="form-group">
							<label>Property Description</label>
							<textarea name="property_description"></textarea>
						</div>
					</div>
				</div>
				<div class="action-add">
					<button type="submit" name="add_property">Add Property</button>
				</div>
			</form>
		</div>
	</div>
</div>

</body>

</html>