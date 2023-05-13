<?php
				if (isset($dataWithPagination)) {
					foreach ($dataWithPagination as $key => $data) { ?>
						<tr>
							<td>
								<?php
								$create_date = $data['create_date'];
								echo date_format(date_create($create_date), 'd/m/Y');
								?>
							</td>
							<td>
								<?php
								echo $data['product_name'];
								?>
							</td>
							<td>
								<?php
								echo $data['sku'];
								?>
							</td>
							<td>
								<?php
								echo $data['price'];
								?>
							</td>
							<td>
								<?php
								if( $data['featured_img'] ) {
									$srcImg = $data['featured_img'];
									echo '<img class="product-render-img" src="public/uploads/' . $srcImg . '">';
								}
								?>
							</td>
							<td>
								<div class="gallery">
									<?php
									if( $data['gallery'] ) {
										$galleryImgs = explode(',', $data['gallery']);
										foreach ($galleryImgs as $value) {
											echo '<img class="product-render-img" src="public/uploads/' . $value . '">';
										}
									}
									?>
								</div>
							</td>
							<?php 
							if($typeProperty) {
								foreach($typeProperty as $value) {?>
									<td><?php
										$propertyName = "";
										if(!empty($getPropertyID)) {
											foreach ($getPropertyID as $arrDataProperty) {
												if(!empty($arrDataProperty)) {
													foreach ($arrDataProperty as $dataProperty) {
														if($data['product_id'] === $dataProperty['product_id'] 
														&& $value['property_type'] === $dataProperty['property_type']) {
															$propertyName .= $dataProperty['property_name'].", ";
														}
													}
												}
											}
										}
										echo "<span>". rtrim($propertyName,', ')."</span>";
									?></td>
								<?php }
							}?>
							<td>
								<div class="action-product">
									<a href="products/edit/<?php echo $data['product_id']; ?>"><i class='bx bx-edit'></i></a>
									<a href="products/delete/<?php echo $data['product_id']; ?>"><i class='bx bxs-trash'></i></a>
								</div>
							</td>
						</tr>
				<?php } 
				} ?>



<?php // Form add?>
<form method= "post" class="form" enctype="multipart/form-data">
			<div class="form-layout">
				<div class="form-group">
					<label>Product Name</label>
					<input type="text" name="product_name" 
						class="product-name <?php if (!empty($isProduct)) {echo "error";} ?>" 
						value="<?php if (isset($isProduct) || isset($productName)) { echo $productName; }?>"
						aria-required="true" required
					>
					<?php
					if (!empty($isProduct)) {
						echo "<div class='message-error'>" . $isProduct . "</div>";
					}
					?>
				</div>
				<div class="form-group form-div">
					<label>Product SKU</label>
					<input type="text" name="product_sku" 
						class="product-sku <?php if (!empty($isProductSKU)) {echo "error";} ?>"
						value="<?php if (isset($isProductSKU) || isset($_POST['add_product'])) { echo $_POST['product_sku']; } ?>"
					>
					<?php
					if (!empty($isProductSKU)) {
						echo "<div class='message-error'>" . $isProductSKU . "</div>";
					}
					?>
				</div>
				<div class="form-group form-div">
					<label>Product Price</label>
					<input type="number" name="product_price" 
					class="product-price <?php if (!empty($isProductPrice)) {echo "error";} ?>" 
					value="<?php if (isset($_POST['add_product']) && isset($_POST['product_price'])) { echo $_POST['product_price']; }?>"
					min="0"
					>
					<?php
						if(!empty($isProductPrice)) {
							echo "<div class='message-error'>" . $isProductPrice . "</div>";
						}
					?>
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
													<option value="<?= $value['property_slug']; ?>" 
													<?php 
													if(isset($_POST['add_product']) && isset($_POST[$propertyType['property_type']])) { 
														foreach($_POST[$propertyType['property_type']] as $postVal) {
															if($value['property_slug'] === $postVal) {
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
				<div class="form-group form-div">
					<span class="label">Product Image</span>
					<label for="product_img" class="file-img">
						<?php
						if (isset($_POST['add_product']) && !empty($_FILES['product_img']['name'])) {
							echo $_FILES['product_img']['name'];
						}
						?>
					</label>
					<input id="product_img" type="file" name="product_img">
					<?php
					if ($errorUploadFeature !== '' && !empty($_FILES['product_img']['name'])) {
						echo "<div class='message-error'>" . $errorUploadFeature . "</div>";
					}
					if ($errorEmptyImg) {
						echo "<div class='message-error'>" . $errorEmptyImg . "</div>";
					}
					?>
				</div>
				<div class="form-group form-div">
					<span class="label">Product Gallery</span>
					<label for="product_gallery" class="file-img">
						<?php
						if (isset($_POST['add_product']) && count(array_filter($_FILES['product_gallery']['name'])) !== 0) {
							foreach ($_FILES['product_gallery']['name'] as $key => $val) {
								$gallery = basename($_FILES['product_gallery']['name'][$key]);
								echo "<p>" . $gallery . "</p>";
							}
						}
						?>
					</label>
					<input type="file" id="product_gallery" name="product_gallery[]" multiple>
					<?php
					if ($errorUploadGallery !== '' && count(array_filter($_FILES['product_gallery']['name'])) !== 0) {
						echo "<div class='message-error'>" . $errorUploadGallery . "</div>";
					}
					if ($errorEmptyGallery) {
						echo "<div class='message-error'>" . $errorEmptyGallery . "</div>";
					}
					?>
				</div>
			</div>
			<div class="action-add">
				<button type="submit" name="add_product" id="add-product">Add Product</button>
			</div>
		</form>