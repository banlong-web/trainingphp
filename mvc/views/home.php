<?php 
	$pageTitle = 'Home';
	include './mvc/views/header.php'; 
?>
<header>
	<div class="container">
		<h2>PHP 1</h2>
		<div class="action-header">
			<div class="action-top">
				<div class="action-add">
					<div class="btn btn-add-product">
						<a href="add-products" id="go-add">Add product</a>
					</div>
					<div class="btn btn-add-property">
						<a href="property" id="">Add property</a>
					</div>
					<div class="btn btn-sync-villatheme">
						<button type="submit" class="sync-villatheme">Sync from Villa Theme</button>
						<!-- <a href="sync-villatheme/syncData">Sync from Villa Theme</a> -->
					</div>
				</div>
				<div class="search-product">
					<div class="form-search">
						<form method="post">
							<input type="text" class="search-value" value="" name="search" placeholder="Search product....">
							<button type="submit" class="submit-search"><span class='bx bx-search-alt-2'></span></button>
						</form>
					</div>
				</div>
			</div>
			<?php if ($data) { ?>
			<div class="filter">
				<form method="post" class="form" enctype="multipart/form-data">
					<div class="filter-layout form-layout">
						<div class="form-group form-div">
							<select name="nameattr" id="field_attr" class="form-select">
								<option value="">Choose an item</option>
								<?php
								$keytable = '';
								if ($data) {
									foreach ($data as $array) {
										foreach ($array as $key => $val) {
											$keytable .= $key . ',';
										}
									}
								}
								$keytableUnique = array_unique(explode(',', $keytable));
								foreach ($typeProperty as $value) {
									$arrayProperty[] = $value['property_type'];
								}
								foreach (array_filter(array_values($keytableUnique)) as $keytableVal) {
									if ($keytableVal !== 'product_id' && $keytableVal !== 'featured_img' && $keytableVal !== 'gallery' 
										&& !in_array($keytableVal, $arrayProperty) 
										&& $keytableVal !== 'create_date' && $keytableVal !== 'modified_date' && $keytableVal !== 'description') { ?>
										<option value="<?= $keytableVal ?>">
											<?php echo ucwords(str_replace('_', ' ', $keytableVal)); ?>
										</option>
										<?php 
									}
								}
								?>
							</select>
						</div>
						<div class="form-group form-div">
							<select name="sorting" id="sorting" class="form-select">
								<option value="asc">
									ASC
								</option>
								<option value="desc">
									DESC
								</option>
							</select>
						</div>
						<div class="form-date">
							<div class="form-group">
								<input type="date" name="from_date" id="from_date">
							</div>
							<div class="form-group">
								<input type="date" name="to_date" id="to_date">
							</div>
						</div>
						<?php
						if ($typeProperty) { ?>
							<div class="form-group" style="display: flex; justify-content: space-evenly;">
							<?php	
							$type = '';
							foreach ($typeProperty as $value) { 
								$type .= $value['property_type'].',';
								?>
									<select name="<?= $value['property_type']; ?>" id="<?= $value['property_type']; ?>" class="form-select">
										<option value="">Choose <?= $value['property_type']?></option>
										<?php
										if (isset($dataProperty)) {
											foreach ($dataProperty as $dataVal) {
												if ($value['property_type'] == $dataVal['property_type']) { ?>
													<option value="<?= $dataVal['property_slug']; ?>">
														<?= $dataVal['property_name']; ?>
													</option>
										<?php
												}
											}
										}
										?>
									</select>
								<?php 
							} ?>
							<input type="hidden" id="type-property" value="<?= rtrim($type,','); ?>">
							</div>
							<?php 
						} ?>
						<div class="btn">
							<button type="submit" class="submit-filter" name="filter"><i class='bx bx-filter-alt'></i></button>
						</div>
					</div>
				</form>
			</div>
			<?php } ?>
		</div>
	</div>
</header>
<div class="list-products">
	<div class="container">
		<table border="1px">
			<thead>
				<tr>
					<!-- <th><div class="overflow">Product Name</div></th>
					<th><div class="overflow">SKU</div></th>
					<th><div class="overflow">Price</div></th>
					<th><div class="overflow">Discount<div></th>
					<th><div class="overflow">Rated</div></th>
					<th><div class="overflow">Feature Image</div></th>
					<th><div class="overflow">Gallery Image</div></th> -->
					<?php 
						$keytable = '';
						if ($data) {
							echo '<th><div class="overflow">Date</div></th>';
							foreach ($data as $array) {
								foreach ($array as $key => $val) {
									$keytable .= $key . ',';
								}
							}
							$keytableUnique = array_unique(explode(',', $keytable));
							foreach ($typeProperty as $value) {
								$arrayProperty[] = $value['property_type'];
							}
							foreach (array_filter(array_values($keytableUnique)) as $keytableVal) {
								if ($keytableVal !== 'product_id' && $keytableVal !== 'create_date' && $keytableVal !== 'modified_date' && $keytableVal !== 'description') { ?>
									<th>
										<div class="overflow">
											<?php echo ucwords(str_replace('_', ' ', $keytableVal)); ?>
										</div>
									</th>
									<?php 
								}
							}
							echo '<th>Actions</th>';
						}
					
					?>
					
				</tr>
			</thead>
			<tbody id="table_products"></tbody>
		</table>
		<div class="pagination" id="pagination"></div>
	</div>
	<div class="message-error" style="text-align: center; font-size: 25px;"></div>
</div>
<?php	
include_once './mvc/views/footer.php';