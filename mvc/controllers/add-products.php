<?php 
	/**
	 * Class product extends controller
	 * Add product
	 * Edit product
	 * Delete product
	 */
	class AddProducts extends Controllers
	{		
		/**
		 * @var protected products
		 * 
		 * @var mixed
		 */
		protected $products;
		protected $properties;
		/**
		 * Function base __construct
		 *
		 * @return void
		 */
		function __construct()
		{
			$this->properties = $this->model('PropertyModels');
			$this->products = $this->model('productsModel');
		}	
		public function show()
		{
			global $propertyTypes, $propertyData;
			$propertyTypes = $this->properties->getPropertyType();
			$propertyData = $this->properties->getPropertyData();
			require_once "./mvc/views/products/add.php";
		}	
		/**
		 * add
		 *
		 * @return void
		 */
		function add()
		{
			$allData = $this->products->getAllProducts();
			$data = $_POST['data'] ? $_POST['data'][0] : '';
			$success = false;
			if($data) {
				$arrProperty = array_filter(explode(',', $data['property_name']));
				$idProperties = $this->properties->getIDProperty($arrProperty);
				$getNameProperty = $this->properties->getNameProperty($arrProperty);
				if($getNameProperty) {
					foreach ($getNameProperty as $valueKeys) {
						if($valueKeys['property_type'] == 'brand') {
							$brandName[] = $valueKeys['property_name'];
						}
						if($valueKeys['property_type'] == 'category') {
							$cateName[] = $valueKeys['property_name'];
						}
						if($valueKeys['property_type'] == 'tag') {
							$tagName[] = $valueKeys['property_name'];
						}
					}
				}
				$arrDataProduct = 
				[
					'product_name' 	=> $data['product_name'],
					'sku' 			=> $data['product_sku'],
					'price' 		=> $data['product_price'],
					'discount'		=> $data['discount'],
					'featured_img' 	=> $data['product_img'],
					'gallery' 		=> $data['product_gallery'],
					'brand'			=> !empty($brandName) ? implode(',', $brandName) : '',
					'category'		=> !empty($cateName) ? implode(',', $cateName) : '',
					'tag'			=> !empty($tagName) ? implode(',', $tagName) : '',
					'description'	=> $data['description'],
					'rate'			=> 0 
				];
				if($allData) {
					foreach ($allData as $value) {
						$allDataProductName[] = $value['product_name'];
					}
				} else {
					$allDataProductName[] = '';
				}
				if (!in_array($arrDataProduct['product_name'], $allDataProductName)) {
					if($this->products->InsertProducts($arrDataProduct)) {
						$success = true;
						$dataId = $this->products->getProductID($arrDataProduct['product_name']);
						$productID = $dataId['product_id'];
						if($this->products->insertProductDetails($productID, $idProperties)) {
							$success = true;
						}
					}
				} else {
					$success = false;
				}
				
			}
			$output = [
				'success' => $success,
				'allData' => $allData
			];
			echo json_encode($output);
		}
	}
