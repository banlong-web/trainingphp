<?php 
	/**
	 * summary
	 */
	class SyncVillaTheme extends Controllers
	{
		protected $products;
		protected $properties;
	    /**
	     * summary
	     */
	    public function __construct()
	    {
	        $this->products = $this->model('productsModel');
			$this->properties = $this->model('PropertyModels');
	    }
	    public function syncData() {
			$success = false;
			$update = false;
			header("Content-Type: text/plain");
			$targetDir = UPLOAD_ROOT;
			$newdir = $targetDir.'products/';
			is_dir($newdir) || @mkdir($newdir) || die("Can't Create folder");
			$url_site_src = URL_BASE.'products/';
			$url = "https://villatheme.com/extensions/";
			$options = [
				CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
				CURLOPT_POST           =>false,        //set to GET
				CURLOPT_RETURNTRANSFER => true,     // return web page
				CURLOPT_HEADER         => false,    // don't return headers
				CURLOPT_FOLLOWLOCATION => true,     // follow redirects
				CURLOPT_ENCODING       => "",       // handle all encodings
				CURLOPT_AUTOREFERER    => true,     // set referer on redirect
				CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
				CURLOPT_TIMEOUT        => 120,      // timeout on response
				CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
			];
			$ch      = curl_init( $url );
			curl_setopt_array( $ch, $options );
			$content = curl_exec( $ch );
			// $err     = curl_errno( $ch );
			// $errmsg  = curl_error( $ch );
			// $header  = curl_getinfo( $ch );
			curl_close( $ch );
			$reg = '/<a href="https:\/\/villatheme.com\/extensions\/(.*)">(.*)<\/a>/';
			preg_match_all($reg, $content, $testHtml, PREG_SET_ORDER, 0);
			if($testHtml) {
				foreach ($testHtml as $list) {
					$array_list[] = $list[1];
				}
			}
			$array_list = array_unique(array_filter(array_values($array_list)));
			$regTitle = '/<h1 class="product_title entry-title">(.*)<\/h1>/';
			$regSku = '/<span class="sku">(.*)<\/span><\/span>/';
			$regPrice = '/<p class="price">(.*)<\/p>/';
			$regBasePrice = '/<del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">&#36;<\/span>(.*)<\/bdi><\/span><\/del>/';
			$regDiscountPrice = '/<ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">&#36;<\/span>(.*)<\/bdi><\/span><\/ins>/';
			if($array_list) {
				foreach($array_list as $list) {
					$nameCate = '';
					$nameTag = '';
					$productGallery = '';
					$url = "https://villatheme.com/extensions/".$list;
					$dt = curl_init($url);
					curl_setopt_array( $dt, $options );
					$dtcontent = curl_exec( $dt );
					curl_close($dt);
					preg_match_all($regTitle, $dtcontent, $titleProduct, PREG_SET_ORDER, 0);
					preg_match_all($regPrice, $dtcontent, $htmlPrice, PREG_SET_ORDER, 0);
					if($htmlPrice) {
						preg_match_all($regBasePrice, $htmlPrice[0][1], $basePrice, PREG_SET_ORDER, 0);
						preg_match_all($regDiscountPrice, $htmlPrice[0][1], $discountPrice, PREG_SET_ORDER, 0);
					}
					preg_match_all($regSku, $dtcontent, $skuProduct, PREG_SET_ORDER, 0);
					preg_match_all('#<div class="panel entry-content" id="tab-description">(.+?)</div>#si', $dtcontent, $productDesciption, PREG_SET_ORDER, 0);
					if($productDesciption) {
						preg_match_all('#<p>(.+?)</p>#si', $productDesciption[0][1], $description);
					}
					preg_match_all('#<figure class="woocommerce-product-gallery__wrapper">(.+?)</figure>#si', $dtcontent, $images, PREG_SET_ORDER, 0);
					if($images) {
						preg_match_all('#<div .*?><a .*?>(.+?)</a></div>#si', $images[0][1], $imageHTML, PREG_SET_ORDER, 0);
					}
					if($imageHTML) {
						preg_match_all('#<img .*? src="https://villatheme.com/wp-content/uploads/(.+?)/(.+?)/(.+?).jpg" class="wp-post-image" .*?>#si', $imageHTML[0][1], $imgs, PREG_SET_ORDER, 0);
					}
					if (!empty($imgs[0])) {
						$year = $imgs[0][1];
						$month = $imgs[0][2];
						$dirYear = $newdir.'/'.$year;
						is_dir($dirYear) || @mkdir($dirYear) || die("Can't Create folder");
						$dirFinal = $dirYear.'/'.$month;
						is_dir($dirFinal) || @mkdir($dirFinal) || die("Can't Create folder");
						$imgFull = $imgs[0][3].'.jpg';
						$featuredImg = $url_site_src.$year.'/'.$month.'/'.$imgFull;
						$imgUrl = "https://villatheme.com/wp-content/uploads/".$year.'/'.$month.'/'.$imgFull;
						$imagename = basename($imgFull);
						copy($imgUrl, $dirFinal . DIRECTORY_SEPARATOR . $imagename);
					}
					if(!empty($imageHTML)) {
						foreach ($imageHTML as $value) {
							preg_match_all('#<img .*? src="https://villatheme.com/wp-content/uploads/(.+?)/(.+?)/(.+?).jpg" class="" .*?>#si', $value[1], $gallery, PREG_SET_ORDER, 0);
							if(!empty($gallery)) {
								$galleryFull = $gallery[0][3].'.jpg';
								$productGallery .= $url_site_src.$year.'/'.$month.'/'.$galleryFull.', ';
								$galleryUrl = "https://villatheme.com/wp-content/uploads/".$year.'/'.$month.'/'.$galleryFull;
								$galleryname = basename($galleryFull);
								copy($galleryUrl, $dirFinal . DIRECTORY_SEPARATOR . $galleryname);
							}
						}
					}
					preg_match_all('#<span class="posted_in">(.+?)</span>#si', $dtcontent, $cateProducts, PREG_SET_ORDER, 0);
					if($cateProducts) {
						preg_match_all('#<a .+?>(.*?)</a>#si', $cateProducts[0][1], $cateProduct, PREG_SET_ORDER, 0);
					}
					preg_match_all('#<span class="tagged_as">(.+?)</span>#si', $dtcontent, $tagProducts, PREG_SET_ORDER, 0);
					if($tagProducts) {
						preg_match_all('#<a .+?>(.*?)</a>#si', $tagProducts[0][1], $tagProduct, PREG_SET_ORDER, 0);
					}
					preg_match_all('#<strong class="rating">(.+?)</strong>#si', $dtcontent, $rating, PREG_SET_ORDER, 0);
					if($cateProduct) {
						foreach ($cateProduct as $cate) {
							$nameCate .= $cate[1].', ';
							$arrCateData = [
								'property_type' 		=> 'category',
								'property_name' 		=> $cate[1],
								'property_slug'			=> str_replace(' ', '_', strtolower($cate[1])),
								'property_description' 	=> ''
							];
							$baseProperty = $this->properties->getPropertyChecked($cate[1]);
							if(empty($baseProperty) && !in_array($cate[1], $baseProperty)) {
								if($this->properties->InsertProperty($arrCateData)) {
									$newProperty = $this->properties->getPropertyChecked($cate[1]);
									$baseProperty = $newProperty;
								}
							}
						}
					}
					if($tagProduct) {
						foreach ($tagProduct as $tags) {
							$nameTag .= $tags[1].', ';
							$arrTagData = [
								'property_type' 		=> 'tag',
								'property_name' 		=> $tags[1],
								'property_slug'			=> str_replace(' ', '_', strtolower($tags[1])),
								'property_description' 	=> ''
							];
							$baseProperty = $this->properties->getPropertyChecked($tags[1]);
							if(empty($baseProperty) && !in_array($tags[1], $baseProperty)) {
								if($this->properties->InsertProperty($arrTagData)) {
									$newProperty = $this->properties->getPropertyChecked($tags[1]);
									$baseProperty = $newProperty;
								}
							}
						}
					}
					
					$productsList[] = [
						'product_name'			=> html_entity_decode($titleProduct[0][1]),
						'sku'					=> !empty($skuProduct[0]) ? $skuProduct[0][1] : str_replace([' – ', ' '], [' ', '-'],strtolower(html_entity_decode($titleProduct[0][1]))),
						'description'			=> $description[1] ? strip_tags(implode(' ', $description[1])) : '',
						'price'					=> !empty($basePrice[0]) ? $basePrice[0][1] : '',
						'discount'				=> !empty($discountPrice[0]) ? $discountPrice[0][1]: '',
						'featured_img'			=> !empty($featuredImg) ? $featuredImg : '',
						'gallery'				=> !empty($gallery) ? $featuredImg.', '.rtrim($productGallery, ', ') : '',
						'brand'					=> '',
						'category'				=> !empty($nameCate) ? rtrim($nameCate, ', ') : '',
						'tag'					=> !empty($nameTag) ? rtrim($nameTag, ', ') : '',
						'rate'					=> !empty($rating[0]) ? $rating[0][1]: '' 
					];
				}
				if($productsList) {
					foreach($productsList as $productList) {
						$baseProduct = $this->products->getProductsChecked($productList['sku']);
						$arrayPropertyTagName = explode(', ', $productList['tag']);
						$arrayPropertyCateName = explode(', ', $productList['category']);
						$mergeArrayPropertyName = array_merge($arrayPropertyCateName, $arrayPropertyTagName);
						$idProperties = $this->properties->getIDPropertyByName($mergeArrayPropertyName);
						if(empty($baseProduct)) {
							if($this->products->InsertProducts($productList)) {
								$newAllProducts = $this->products->getProductsChecked($productList['sku']);
								$baseProduct = $newAllProducts;
								$dataId = $this->products->getProductID($productList['product_name']);
								$productID = $dataId['product_id'];
								$this->products->insertProductDetails($productID, $idProperties);
								$success = true;
							}
						} else {
							if(!empty($baseProduct) && in_array($productList['sku'], $baseProduct)) {
								if($this->products->updateProduct($productList)) { 
									$dataId = $this->products->getProductID($productList['product_name']);
									$productID = $dataId['product_id'];
									$baseProductProperty = $this->products->getPropertyID($productID);
									$nameProperty = '';
									if($baseProductProperty) {
										foreach($baseProductProperty as $valueOld) { 
											$nameProperty .= $valueOld['property_name'].',';
										}
									}
									
									$newBaseProductProperty = array_filter(explode(',', $nameProperty));
									
									foreach($mergeArrayPropertyName as $valueNew) {
										$valueName = '';
										if (!in_array($valueNew, $newBaseProductProperty)) {
											$valueName .= $valueNew.',';
											$arrName = array_filter(explode(',', $valueName));
											$dataProperty = $this->properties->getIDPropertyByName($arrName);
											$this->products->updateProductProperty($productID, $dataProperty);
										}
									}
									$update = true;
								}
							}
						} 
					}
				}
			}
			$output = [
				'add' => $success,
				'update' => $update
			];
			echo json_encode($output);
	    }
	}
?>