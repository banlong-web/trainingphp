<?php
/**
 * Class property
 * Add category, add tag name
 */
	class Property extends Controllers
	{	 	
	 	/**
	 	 * @var protected properties
	 	 * 
	 	 * @var mixed
	 	 */
	 	protected $properties; 	
	 	/**
	 	 * __construct
	 	 *
	 	 * @return void
	 	 */
	 	function __construct()
	 	{
	 		$this->properties = $this->model('PropertyModels');
	 	}	 	
	 	/**
	 	 * show
	 	 *
	 	 * @return void
	 	 */
	 	function show()
	 	{
	 		global $errorPropertyType, $errorPropertyName, $errorPropertySlug;
	 		if(isset($_POST['add_property'])) {
	 			// Add category
	 			$property_type = $_POST['property_type'];
	 			$property_name = $_POST['property_name'];
				$property_slug = $_POST['property_slug'];
				$property_description = $_POST['property_description'];
				$regexName = preg_match("/^[A-Za-z0-9 _-]+$/i", $property_name);
				$regexSlug = preg_match('/^[a-z][a-z0-9-]+$/i', $property_slug);
				if ($property_type == '') {
					$errorPropertyType = "Please choose type property";
				}
				if(!$regexName) {
					$errorPropertyName = "Invaild property name";
				}
				if(!$regexSlug && $property_slug !== '') {
					$errorPropertySlug = "Invaild property slug";
				}
				$arrData = [
					'property_type' 		=> $property_type,
					'property_name' 		=> $property_name,
					'property_slug'			=> $property_slug ? $property_slug : str_replace(' ', '_', strtolower($property_name)),
					'property_description' 	=> $property_description
				];
				if($this->properties->isPropertyName($property_name) === false) {
					$errorPropertyName = 'Property name has been added before!';
				}
				if(empty($errorPropertyType) && empty($errorPropertyName) && empty($errorPropertySlug)) {
					if($this->properties->InsertProperty($arrData)) {
						echo ("<script type='text/javascript'>alert('Add property successfully!');</script>");
						header("Refresh:0; url=/");
					} else {
						unset($_POST['add_property']);
					}
				}
	 		}
	 		require_once "./mvc/views/property/property-view.php";
	 	}
	 }
