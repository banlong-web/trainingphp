<?php

class EditProduct extends Controllers 
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
        global $propertyTypes, $propertyData, $data, $baseProperty;
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $data = $this->products->getProduct($id);
            $propertyTypes = $this->properties->getPropertyType();
            $propertyData = $this->properties->getPropertyData();
            $baseProperty = $this->products->getPropertyID($id);
            require_once "./mvc/views/products/edit.php";
        }
      
    }
    public function edit()
    {   
        $id = $_POST['id'] ? $_POST['id'] : '';
        $dataDisplay = $this->products->getProduct($id);
        $baseProperty = $this->products->getPropertyID($id);
        $dataUpdate = $_POST['dataProduct'] ? $_POST['dataProduct'][0] : '';
        $success = false;
        $same = '';
        if($dataUpdate) {
            $arrProperty = array_filter(explode(',', $dataUpdate['property_name']));
            $idProperties = $this->properties->getIDProperty($arrProperty);
            $getNameProperty = $this->properties->getNameProperty($arrProperty);
            if(isset($getNameProperty)) {
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
            if(!empty($idProperties)) {
                $dataProperty = $idProperties;
            } else {
                $dataProperty = $baseProperty;
            }
            $arrDataProduct = 
            [
                'product_id'		=> $id,
                'product_name' 		=> $dataUpdate['product_name'],
                'sku' 		        => $dataUpdate['product_sku'],
                'description'		=> $dataUpdate['description'],
                'price' 	        => $dataUpdate['product_price'],
                'discount' 	        => $dataUpdate['discount'],
                'featured_img' 		=> $dataUpdate['product_img'],
                'gallery' 	        => $dataUpdate['product_gallery'],
                'brand'				=> isset($brandName) ? implode(',', $brandName) : '',
                'category'			=> isset($cateName) ? implode(',', $cateName) : '',
                'tag'				=> isset($tagName) ? implode(',', $tagName) : '',
                'rate'			    => '0' 
            ];
            foreach ($dataDisplay as $key => $val) {
                if($key !== 'modified_date' && $key !== 'create_date') {
                    $newDataDisplay[$key] = $val; 
                }
            }
            $nameProperty = '';
            if($baseProperty) {
                foreach ($baseProperty as $values) {
                    foreach($values as $key => $val) {
                        if($key === 'property_slug') {
                            $nameProperty .= $val.',';
                        }
                        
                    }
                }
            }
            $newBaseProperty = array_filter(explode(',', $nameProperty));
            if(($newDataDisplay == $arrDataProduct) && ($newBaseProperty == $arrProperty)) {
                $same = 'same';
            } 
            if (($newDataDisplay !== $arrDataProduct) || ($newBaseProperty !== $arrProperty)) {
                if($this->products->updateProduct($arrDataProduct)){
                    if($dataProperty) {
                        $this->products->updateProductProperty($id, $dataProperty);
                        $this->products->removeAfterUpdateProductProperty($id, $dataProperty);
                    }
                    $success = true;
                } else {
                    $success = false;
                }
            }
            $output = [
                'base_property' => $baseProperty,
                'data_product'  => $dataDisplay,
                'success'       => $same ? $same : $success,
            ];
        }
       
        echo json_encode($output);
    }
}