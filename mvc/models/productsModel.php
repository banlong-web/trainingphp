<?php 
	/**
	 * summary
	 */
	class ProductsModel extends Database
	{
	    /**
	     * summary
	     */
	    public function getProduct($data) 
	    {
	    	$sql = "SELECT * FROM `products` WHERE `product_id` = '$data'";
	    	$result = $this->execute($sql);
	    	if($result){
	    		$data = mysqli_fetch_assoc($result);
	    	} else {
	    		$data = 0;
	    	}
	    	return $data;
	    }
		public function getProductID($data) {
			$sql = "SELECT `product_id` FROM `products` WHERE `product_name` = '$data'";
	    	$result = $this->execute($sql);
	    	if($result){
	    		$data = mysqli_fetch_assoc($result);
	    	} else {
	    		$data = 0;
	    	}
	    	return $data;
		}
		public function getProductByID($args) 
	    {
			if ($args) {
				foreach ($args as $value) {
					$productid = $value['product_id'];
					$sql = "SELECT * FROM `products` WHERE `product_id` = '$productid';";
					$result = $this->execute($sql);
					if($this->num_rows() == 0) {
						$data = 0;
					} else {
						while ($datas = mysqli_fetch_assoc($result)) {
							$data[] = $datas;
						}
					}
				}
				return $data;
			}
	    }
	    public function getAllProducts()
	    {
			$sql = "SELECT * FROM `products`";
			$result = $this->execute($sql);
			if($this->num_rows() == 0) {
				$data = 0;
			} else {
				while ($datas = mysqli_fetch_assoc($result)) {
					$data[] = $datas;
				}
			}
	    	return $data;
	    }
		public function getProductsChecked($productSKU)
	    {
			$sql = "SELECT `product_name`, `sku`, `description`, `price`, `discount`, `featured_img`, `gallery`, `brand`, `category`, `tag`, `rate` FROM `products` WHERE `sku` = '$productSKU'";
			$result = $this->execute($sql);
			if($this->num_rows() == 0) {
				$data = [];
			} else {
				while ($datas = mysqli_fetch_assoc($result)) {
					$data = $datas;
				}
			}
	    	return $data;
	    }
		public function SearchProduct($param)
		{
			$sql = "SELECT * FROM `products` WHERE `product_name` LIKE '%$param%'";
			$result = $this->execute($sql);
	    	if($this->num_rows() == 0) {
	    		$data = 0;
	    	} else {
	    		while ($datas = mysqli_fetch_assoc($result)) {
	    		    $data[] = $datas;
	    		}
	    	}
	    	return $data;
		}
	    public function InsertProducts($data)
	    {
	    	date_default_timezone_set("Asia/Bangkok");
	    	if ($data) {
	            $productName = $data['product_name'];
	            $productSKU = $data['sku'];
				$productDescription = $data['description'];
	            $productPrice = $data['price'];
				$productDiscount = $data['discount'];
	            $productImage = $data['featured_img'];
	            $productGallery = $data['gallery'];
				$brand = $data['brand'];
				$category = $data['category'];
				$tag = $data['tag'];
				$rated = $data['rate'];
	            $timeCreate = date('Y-m-d H:i:s');
	            $sql = 
				"INSERT INTO `products`(`product_id`, `product_name`, `sku`, `description`, `price`, `discount`, `featured_img`, `gallery`, `brand`, `category`, `tag`, `rate`, `create_date`, `modified_date`)
	            VALUES(null, '$productName', '$productSKU', '$productDescription', '$productPrice', '$productDiscount', '$productImage', '$productGallery', '$brand', '$category', '$tag', '$rated', '$timeCreate', null)";
        	}
	        return $this->execute($sql);
	    }
	    public function isHasProduct($data) {
	    	$isProduct = false;
	    	if ($data) {
				$sql = "SELECT `product_name` FROM `products` WHERE `product_name` = '$data'";
				$result = $this->execute($sql);
				if(mysqli_num_rows($result) == 0) {
					$isProduct = true;
				}
			}
			return $isProduct;
	    }
	    public function isHasProductSKU($data) {
	    	$isSKU = false;
	    	if ($data) {
				$sql = "SELECT `sku` FROM `products` WHERE `sku` = '$data'";
				$result = $this->execute($sql);
				if(mysqli_num_rows($result) == 0) {
					$isSKU = true;
				}
			}
			return $isSKU;
	    }
		public function isHasProductID($data) {
	    	$isID = false;
	    	if ($data) {
				$sql = "SELECT `sku` FROM `products` WHERE `product_id` = '$data'";
				$result = $this->execute($sql);
				if(mysqli_num_rows($result) == 0) {
					$isID = true;
				}
			}
			return $isID;
	    }
	    public function updateProduct($data) {
	    	date_default_timezone_set("Asia/Bangkok");
	    	if($data) {
				if(isset($data['product_id'])) {
					$product_id = $data['product_id'];
				}
				$productName = $data['product_name'];
	            $productSKU = $data['sku'];
				$productDescription = $data['description'];
	            $productPrice = $data['price'];
				$productDiscount = $data['discount'];
	            $productImage = $data['featured_img'];
	            $productGallery = $data['gallery'];
				$brand = $data['brand'];
				$category = $data['category'];
				$tag = $data['tag'];
	            $timeModified = date('Y-m-d H:i:s');
				if(!empty($product_id)) {
					$sql = "UPDATE `products` 
					SET `product_name`='$productName',`sku`='$productSKU', `description` = '$productDescription', `price`='$productPrice',
					`discount`='$productDiscount', `featured_img`='$productImage',`gallery`='$productGallery', `brand`='$brand',
					`category`='$category', `tag`='$tag', `modified_date`='$timeModified' WHERE `product_id` = '$product_id'";
				} else {
					$sql = "UPDATE `products` 
	    				SET `product_name`='$productName',`sku`='$productSKU', `description` = '$productDescription', `price`='$productPrice',
						`discount`='$productDiscount', `featured_img`='$productImage',`gallery`='$productGallery', `brand`='$brand',
						`category`='$category', `tag`='$tag', `modified_date`='$timeModified' WHERE `sku` = '$productSKU'";
				}
	    	}
	    	return $this->execute($sql);
	    }
	    public function deleteProduct($value) {
	    	if($value) {
	    		$sql = "DELETE FROM `products` WHERE `product_id` = '$value'";
	    	}
	    	$result = $this->execute($sql);
	    	return $result;
	    }
		public function getFilterProduct($param, $sort)
		{
			$sql = "SELECT * FROM `products` ORDER BY `$param` $sort";
			$result = $this->execute($sql);
			if($this->num_rows() == 0) {
	    		$data = 0;
	    	} else {
	    		while ($datas = mysqli_fetch_assoc($result)) {
	    		    $data[] = $datas;
	    		}
	    	}
	    	return $data;
		}
		public function filterProduct($start, $limit, $param, $sort)
		{
			$sql = "SELECT * FROM `products` ORDER BY `$param`  $sort LIMIT $start, $limit";
			$result = $this->execute($sql);
			if($this->num_rows() == 0) {
	    		$data = 0;
	    	} else {
	    		while ($datas = mysqli_fetch_assoc($result)) {
	    		    $data[] = $datas;
	    		}
	    	}
	    	return $data;
		}
		public function filterProductProperty($start, $limit, $args, $sort)
		{
			$productId = "";
			if ($args) {
				foreach ($args as $value) {
					$productId .= "'".$value['product_id']."',";
				}
			}
			$rebuildId = rtrim($productId, ',');
			$sql = "SELECT * FROM `products` WHERE `product_id` IN ($rebuildId) ORDER BY `product_id` $sort LIMIT $start, $limit";
			$result = $this->execute($sql);
			if($this->num_rows() == 0) {
				$data = 0;
			} else {
				while ($datas = mysqli_fetch_assoc($result)) {
					$data[] = $datas;
				}
			}
	    	return $data;
		}
		public function insertProductDetails($productId, $propertiesID)
		{
			$sqlVal = "";
			if($propertiesID) {
				foreach ($propertiesID as $value) {
					$sqlVal .= "(null,'$value','$productId'),";
				}
			}
			$result = "";
			$rebuildVal = rtrim($sqlVal, ',');
			if(!empty($rebuildVal)) {
				$sql = "INSERT INTO `product_property`(`id`, `property_id`, `product_id`) VALUES  $rebuildVal";
				$result = $this->execute($sql);
			}
			return $result;
		}
		public function updateProductProperty($productId, $args)
		{
			$selectVal = "SELECT `property_id`, `product_id` FROM `product_property` AS t1
			WHERE EXISTS
			  (SELECT `property_id`, `product_id` FROM `product_property`
			   WHERE `product_id`='$productId' AND t1.product_id = '$productId')";
			$resultSelect = $this->execute($selectVal);
			if($this->num_rows() == 0) {
	    		$data = 0;
	    	} else {
	    		while ($datas = mysqli_fetch_assoc($resultSelect)) {
	    		    $data[] = $datas;
	    		}
	    	}
			$sqlVal = "";
			$arrBase = [];
			if($data) {
				foreach ($data as $value) {
					$arrBase[] = $value['property_id'];
				}
			}
			if ($args) {
				foreach ($args as $values) {
					if(isset($values['property_id'])) {
						if(!in_array($values['property_id'], $arrBase)) {
							$id = $values['property_id'];
							$sqlVal .= "(null,'$id','$productId'),";
						} 
					} elseif ($values) {
						if(!in_array($values, $arrBase)) {
							$id = $values;
							$sqlVal .= "(null,'$id','$productId'),";
						} 
					}
				}
			}
			
			$rebuildVal = rtrim($sqlVal, ',');
			$result = "";
			if(!empty($rebuildVal)) {
				$sql = "INSERT INTO `product_property`(`id`, `property_id`, `product_id`) VALUES $rebuildVal";
				$result = $this->execute($sql);
			}
			
			return $result;
		}
		public function removeAfterUpdateProductProperty($productId, $args)
		{
			$selectVal = "SELECT `property_id`, `product_id` FROM `product_property` AS t1
			WHERE EXISTS
			  (SELECT `property_id`, `product_id` FROM `product_property`
			   WHERE `product_id`='$productId' AND t1.product_id = '$productId')";
			$resultSelect = $this->execute($selectVal);
			if($this->num_rows() == 0) {
	    		$data = 0;
	    	} else {
	    		while ($datas = mysqli_fetch_assoc($resultSelect)) {
	    		    $data[] = $datas;
	    		}
	    	}
			$arrBase = [];
			if($data) {
				foreach ($data as $value) {
					$arrBase[] = $value['property_id'];
				}
			}
			$arrIdNotChooses = array_diff($arrBase, $args);
			$result = '';
			foreach ($arrIdNotChooses as $val) {
				$sql = "DELETE FROM `product_property` WHERE `product_id` = '$productId' AND `property_id` = '$val';";
				$result .= $this->execute($sql);
				
			}
			return $result;
		}
		public function getPropertyID($productId)
		{
			$sql = "SELECT DISTINCT `properties`.`property_name`, `products`.`product_id`, `properties`.`property_type`, `properties`.`property_slug`
					FROM (`product_property` INNER JOIN `properties` ON `product_property`.`property_id` = `properties`.`property_id`)
					INNER JOIN `products` ON `product_property`.`product_id` = `products`.`product_id`
					WHERE `product_property`.`product_id` = '$productId'";
			$result = $this->execute($sql);
	    	if($this->num_rows() == 0) {
	    		$data = 0;
	    	} else {
	    		while ($datas = mysqli_fetch_assoc($result)) {
	    		    $data[] = $datas;
	    		}
	    	}
	    	return $data;
		}
		public function getProductIDByPropertyID($args)
		{
			$clause = "";
			if($args) {
				if(count($args) > 1) {
					$max = count($args);
					$clause = "`product_property`.`property_id` IN (".implode(',', $args).") GROUP BY `product_id`
					HAVING COUNT(`product_id`) >= $max";
				} elseif (count($args) == 1) {
					$clause = "`product_property`.`property_id` ='".$args[0]."' GROUP BY `product_id`";
				}
			}
			if(!empty($clause)) {
				$sql = "SELECT `product_id`, COUNT(`product_id`) AS count FROM `product_property` WHERE $clause";
				$result = $this->execute($sql);
				if($this->num_rows() == 0) {
					$data = 0;
				} else {
					while ($datas = mysqli_fetch_assoc($result)) {
						$data[] = $datas;
					}
				}
				return $data;
			}
		}
		public function getIDPropertyWithClause($args)
		{
			$OPERATORS = "OR";
			$clause = "";
			// print_r($args);
			if($args)  {
				foreach ($args as $key => $val) {
					$clause .= "`properties`.`property_slug` ='".$val."' ".$OPERATORS." ";
				}
			}
			// if(count($args) == 1) {
			// 	foreach ($args as $key => $val) {
			// 		$clause .= "`properties`.`property_slug` ='".$val."'";
			// 	}
			// }
			$rebuildSql = rtrim($clause,($OPERATORS." "));
			if(!empty($rebuildSql)) {
				$sql = "SELECT `property_id` FROM `properties` 
				WHERE $rebuildSql";
				$result = $this->execute($sql);
				if($this->num_rows() == 0) {
					$data = 0;
				} else {
					while ($datas = mysqli_fetch_assoc($result)) {
						$data[] =  implode(",", $datas);
					}
				}
				
			}
	    	return $data;
		}
		public function getProductByDate($dateFrom, $dataTo)
		{
			$sql = "SELECT * FROM `products` WHERE `create_date` BETWEEN '$dateFrom 00:00:00' AND '$dataTo 23:59:59'";
			$result = $this->execute($sql);
			
			if($this->num_rows() == 0) {
	    		$data = 0;
	    	} else {
	    		while ($datas = mysqli_fetch_assoc($result)) {
	    		    $data[] = $datas;
	    		}
	    	}
	    	return $data;
		}
		public function filterDataByDate($start, $limit, $dateFrom, $dataTo, $sort)
		{
			$sql = "SELECT * FROM `products` WHERE `create_date` BETWEEN '$dateFrom 00:00:00' AND '$dataTo 23:59:59' ORDER BY `create_date` $sort LIMIT $start, $limit";
			$result = $this->execute($sql);
			if($this->num_rows() == 0) {
	    		$data = 0;
	    	} else {
	    		while ($datas = mysqli_fetch_assoc($result)) {
	    		    $data[] = $datas;
	    		}
	    	}
	    	return $data;
		}
		public function updateProductPropertyRelation($productID, $propertiesID)
		{
			if ($propertiesID) {
				foreach ($propertiesID as $value) {
					$sql = "UPDATE `product_property` SET `property_id` = '$value' WHERE `product_id` = '$productID';";
					return $this->execute($sql);
				}
			}
		}
	}
?>