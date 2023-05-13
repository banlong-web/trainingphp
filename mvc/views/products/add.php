<?php $pageTitle = 'Add Product';
include_once './mvc/views/header.php'; ?>

<div class="container">
	<div class="title">
		<h2><?= "Add Product"; ?></h2>
	</div>
	<div class="form-add" id="form-add">
		<form method="post" class="form" enctype="multipart/form-data">
			<div class="form-layout">
				<div class="form-group" id="product_name_form">
					<label>Product Name</label>
					<input type="text" name="product_name" class="product-name">
					<div class="message-error"></div>
				</div>
				<div class="form-group form-div product_sku">
					<label>Product SKU</label>
					<input type="text" name="product_sku" class="product-sku">
					<div class="message-error"></div>
				</div>
				<div class="form-group form-div product_price">
					<label>Product Price</label>
					<input type="number" name="product_price" class="product-price">
					<div class="message-error"></div>
				</div>
				<div class="form-group">
					<?php
					if (isset($propertyTypes)) { 
						$type = '';
						?>
						<label for="properties">Select Property Of Product:</label>
						<div class="form-property row">
							<?php
							foreach ($propertyTypes as $propertyType) { 
								$type .= $propertyType['property_type'].',';
								?>
								<div class="select-property col-xl-6">
									<h5><?= ucfirst($propertyType['property_type']); ?></h5>
									<select name="<?= $propertyType['property_type']; ?>[]" class="properties" 
									id="<?= $propertyType['property_type']; ?>" multiple>
										<?php
										if (isset($propertyData)) {
											foreach ($propertyData as $value) {
												if ($propertyType['property_type'] == $value['property_type']) { ?>
													<option value="<?= $value['property_slug']; ?>">
														<?= $value['property_name']; ?>
													</option>
													<?php
												}
											}
										} ?>
									</select>
								</div>
							<?php
							}
							?>
							<input type="hidden" id="type-property" value="<?= rtrim($type,','); ?>">
						</div>
					<?php }
					?>
				</div>
				<div class="form-group form-div product_img">
					<span class="label">Product Image</span>
					<label for="product_img" class="file-img"></label>
					<input id="product_img" type="file" name="product_img">
					<div class="message-error"></div>
				</div>
				<div class="form-group form-div product_gallery">
					<span class="label">Product Gallery</span>
					<label for="product_gallery" class="file-img"></label>
					<input type="file" id="product_gallery" name="product_gallery[]" multiple>
					<div class="message-error"></div>
				</div>
			</div>
			<div class="action-add">
				<button type="submit" name="add_product" id="add-product">Add Product</button>
			</div>
		</form>
	</div>
</div>
<?php
include_once './mvc/views/footer.php';