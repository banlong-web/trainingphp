<?php 
	class PropertyModels extends Database
	{
		public function getPropertyType()
		{
			$sql = "SELECT DISTINCT `property_type` FROM `properties`";
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
		public function getPropertyData()
		{
			$sql = "SELECT * FROM `properties` ORDER BY `property_type`";
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
		public function InsertProperty($data)
		{
			if($data) {
				// $property_id = $data['property_id'];
				$property_type = $data['property_type'];
				$property_name = $data['property_name'];
				$property_slug = $data['property_slug'];
				$property_description = $data['property_description'];
				$sql = "INSERT INTO  `properties`(`property_id`, `property_type`, `property_name`, `property_slug`, `property_description`)
	            VALUES(null, '$property_type', '$property_name', '$property_slug', '$property_description')";
			}
			return $this->execute($sql);
		}
		public function isPropertyName($data) {
	    	$isPropertyName = false;
	    	if ($data) {
				$sql = "SELECT `property_name` FROM `properties` WHERE `property_name` = '$data'";
				$result = $this->execute($sql);
				if(mysqli_num_rows($result) == 0) {
					$isPropertyName = true;
				}
			}
			return $isPropertyName;
	    }
		public function isPropertyID($data) {
	    	$isPropertyID = false;
	    	if ($data) {
				$sql = "SELECT `property_id` FROM `properties` WHERE `property_id` = '$data'";
				$result = $this->execute($sql);
				if(mysqli_num_rows($result) == 0) {
					$isPropertyID = true;
				}
			}
			return $isPropertyID;
	    }
		public function getIDProperty($args)
		{
			if($args) {
				foreach ($args as $val) {
					$sql = "SELECT `property_id` FROM `properties` WHERE `property_slug` = '$val';";
					$result = $this->execute($sql);
					if($this->num_rows() == 0) {
						$data = 0;
					} else {
						while ($datas = mysqli_fetch_assoc($result)) {
							$data[] = implode(",", $datas);
						}
					}
				}
				return $data;
			}
		}
		public function getNameProperty($args)
		{
			if($args) {
				foreach ($args as $val) {
					$sql = "SELECT `property_type`, `property_name` FROM `properties` WHERE `property_slug` = '$val';";
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
	}
?>