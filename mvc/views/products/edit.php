<?php $pageTitle = 'Edit Product';  include_once './mvc/views/header.php'; ?>
<div class="container">
    <div class="title">
        <h2><?php echo "Edit Product"; ?></h2>
    </div>
    <div class="form-add">
        <form method="post" class="form" enctype="multipart/form-data">
            <div class="form-layout">
                <div class="form-group product_name_form">
                    <label>Product Name</label>
                    <input type="text" name="product_name" value="<?php echo $data['product_name']; ?>" class="product-name">
                   	<div class='message-error'></div>
                </div>
                <div class="form-group product_sku_form">
                    <label>Product SKU</label>
                    <input type="text" name="product_sku" value="<?php echo $data['sku'];?>" class="product-sku">
                   	<div class='message-error'></div>
                </div>
				<div class="form-group product_description">
					<label>Product Description</label>
					<textarea name="product_description" class="product-description">
					</textarea>
					<div class="message-error"></div>
				</div>
                <div class="form-group form-div product_price_form">
                    <label>Product Price</label>
                    <input type="number" name="product_price" value="<?php echo $data['price'];?>" class="product-price">
                   	<div class='message-error'></div>
                </div>
				<div class="form-group form-div product_discount_form">
					<label>Product Discount</label>
					<input type="number" name="product_discount" class="product-discount">
					<div class="message-error"></div>
				</div>
                <div class="form-group">
					<?php
					if (isset($propertyTypes)) { 
						$type = '';
						?>
						<label>Select Property Of Product:</label>
						<div class="form-property row">
						    <?php
							foreach ($propertyTypes as $propertyType) { 
								$type .= $propertyType['property_type'].',';
								?>
								<div class="select-property col-xl-6">
									<h5><?= ucfirst($propertyType['property_type']); ?></h5>
									<select name="<?= $propertyType['property_type']; ?>[]" class="properties" id="<?= $propertyType['property_type']; ?>" multiple>
										<?php
										if (isset($propertyData)) {
											foreach ($propertyData as $value) {
												if ($propertyType['property_type'] == $value['property_type']) { ?>
													<option value="<?= $value['property_slug']; ?>" 
													<?php 
													if(!empty($baseProperty)) { 
														foreach($baseProperty as $baseVal) {
															if($value['property_slug'] === $baseVal['property_slug']) {
																echo 'selected'; 
															}
														}
													} 
													?>>
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
                <div class="form-group form-div form-edit-img">
                    <span class="label">Product Image</span>
                    <label for="product_img_edit" class="file-img">
                        <?php 
							$src = "";
							$dataImg = "";
							if($data['featured_img']){
								$src .= $data['featured_img'];
								$dataImg .= $data['featured_img'];
							}
							if(!empty($src)) {
								echo '<img class="product-render-img" src="'.$src.'" alt="" data-img="'.$dataImg.'"';
							}
						?>
						
                    </label>
                    <input id="product_img_edit" type="file" name="product_img">
					<div class="message-error"></div>
                </div>
                <div class="form-group form-div form-edit-gallery">
                    <span class="label">Product Gallery</span>
                    <label for="product_gallery_edit" class="file-img">
							<?php 
								$productGallery = explode(',',$data['gallery']);
								if($data['gallery']) { ?>
									<div class="gallery">
										<?php
										if ($productGallery) {
											foreach ($productGallery as $value) {
												echo '<img class="product-render-img" src="'.$value.'" data-gallery="'.$value.'">';
											}
										} else {
											echo '<img class="product-render-img" src="" data-gallery="">';
										} 
										?> 
									</div>	
							<?php } ?>
                    </label>
                    <input type="file" id="product_gallery_edit" name="product_gallery[]" multiple>
					<div class="message-error"></div>
                </div>
            </div>
			<input type="hidden" id="product-id" value="<?= $data['product_id']; ?>">
            <div class="action-add">
                <button type="submit" name="edit_product" class="edit-product">Edit Product</button>
            </div>
        </form>
    </div>
</div>
<?php 
include_once './mvc/views/footer.php';