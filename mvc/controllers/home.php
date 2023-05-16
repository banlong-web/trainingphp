<?php 
	require_once './mvc/Pagination.php';
	/**
	 * Class Home extends Controller
	 * Show list product
	 */
	class Home extends Controllers
	{		
		/**
		 *@var proteced products
		 *
		 * @var mixed
		 */
		protected $products;
		protected $properties;
		protected $pagination;
		/**
		 * __construct
		 *
		 * @return void
		 */
		function __construct()
		{
			$this->products = $this->model('productsModel');
			$this->properties = $this->model('PropertyModels');
			$this->pagination = new Pagination();
		}	    
	    /**
	     * Function show
	     *
	     * @return void
	     */
	    function show()
	    {
				  
			// echo $_SERVER['SERVER_NAME'];
			global $dataProperty, $typeProperty, $data;
			$dataProperty = $this->properties->getPropertyData();
			$typeProperty = $this->properties->getPropertyType();
			$data = $this->products->getAllProducts();
			require_once "./mvc/views/home.php";
	    }

		public function fetchData()
		{
			$limitVal = 4;
			$data = $this->products->getAllProducts();
			$typeProperty = $this->properties->getPropertyType();
			if($data) {
				$totalRecords = count($data);
				$current_page = isset($_GET['pageNum']) && is_numeric($_GET['pageNum']) ? intval($_GET['pageNum']) : 1;
				$numofpages = ceil($totalRecords / $limitVal);
				if ($current_page <= 0) {
					$page_num = 1;
				} else {
					if ($current_page <= $numofpages) {
						$page_num = $current_page;
					} else {
						$page_num = 1;
					}
				}
				$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? intval($_GET['offset']) : 0;
				$data = array_slice($data, $offset, $limitVal);
				foreach ($data as $value) {
					$getPropertyID[] = $this->products->getPropertyID($value['product_id']);
				}
				
				$showPagination = $this->pagination->pagination($numofpages, $page_num);
			
				$output = [
					'success' => [
						'product'           => $data,
						'property'          => $typeProperty,
						'propertyProduct'   => $getPropertyID,
						'page'              => 1,
						'pageLimit'         => $numofpages,
						'fetched'           => $limitVal,
						'totalRecords'      => $totalRecords,
						'offset'            => $offset,
						'pagination'        => $showPagination ? $showPagination : ''
					],
				];
				echo json_encode($output);
			} 
			
		}

		/**
		 * delete
		 *
		 * @param  mixed $value
		 * @return void
		 */
		function delete($value) {
			if($value) {
				$id = '';
				foreach ($value as $val) {
					$id .= $val;
				}
				if ($this->products->deleteProduct($id)) {
					echo ("<script type='text/javascript'>alert('Delete product successfully!');</script>");
					header("Refresh:0; url=".URL_SITE."");
				}
			}
		}

		/**
		 * search
		 *
		 * @param  mixed $params
		 * @return void
		 */
		function search()
		{
			$params = $_GET['s'] ? $_GET['s'] : '';
			$limit = 4;
			$data = $this->products->SearchProduct($params);
			if($data) {
				$totalRecords = count($data);
				$typeProperty = $this->properties->getPropertyType();
				foreach($data as $value) {
					$baseProperty[] = $this->products->getPropertyID($value['product_id']);
				}
				$current_page = isset($_GET['pageNum']) && is_numeric($_GET['pageNum']) ? intval($_GET['pageNum']) : 1;
				$numofpages = ceil($totalRecords / $limit);
				if ($current_page <= 0) {
					$page_num = 1;
				} else {
					if ($current_page <= $numofpages) {
						$page_num = $current_page;
					} else {
						$page_num = 1;
					}
				}
				$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? intval($_GET['offset']) : 0;
				$data = array_slice($data, $offset, $limit);
				$showPagination = $this->pagination->pagination($numofpages, $page_num);
				// print_r($baseProperty);
				$output = [
					'data' 				=> $data,
					'searchValue'		=> $params,
					'property'          => $typeProperty,
                    'propertyProduct'   => $baseProperty,
					'totalRecords'      => $totalRecords,
					'fetched'           => $limit,
					'offset'            => $offset,
					'pagination'		=> $showPagination ? $showPagination : ''
				];
				echo json_encode($output);
			} else {
				$output = [
					'data' => $data
				];
				echo json_encode($output);
			}
		}

		public function filter()
		{
			$dataFilter = $_GET['data'] ? $_GET['data'][0] : '';
			$dataProduct = $this->products->getAllProducts();
			$limit = 4;
			$dataShowProduct = [];
			$sort = strtoupper($dataFilter['sort']) == 'ASC' ? SORT_ASC : SORT_DESC;
			if($dataFilter['property_name'] !== '') {
				$params = explode(',', $dataFilter['property_name']);
				$propertyId = $this->products->getIDPropertyWithClause($params);
				$productIds = $this->products->getProductIDByPropertyID($propertyId);
				$dataProduct = $this->products->getProductByID($productIds);
			}
			if($dataFilter['date_from'] !== '' && $dataFilter['date_to']) {
				$fromDate = $dataFilter['date_from'];
				$toDate = $dataFilter['date_to'];
				$dataProduct = $this->products->getProductByDate($fromDate, $toDate);
			}
			if($dataProduct) {
				$typeProperty = $this->properties->getPropertyType();
				foreach($dataProduct as $value) {
					$baseProperty[] = $this->products->getPropertyID($value['product_id']);
				}
				if($dataFilter['field_attr'] !== '') {
					$fieldName = $dataFilter['field_attr'];
					$fieldAttr = array_column($dataProduct, $fieldName);
					array_multisort($fieldAttr, $sort, $dataProduct);
				}
				if($dataFilter['property_name'] !== '') { 
					$fieldAttr = array_column($dataProduct, 'product_name');
					array_multisort($fieldAttr, $sort, $dataProduct);
				}
				$totalRecords = count($dataProduct);
				$current_page = isset($_GET['pageNum']) && is_numeric($_GET['pageNum']) ? intval($_GET['pageNum']) : 1;
				$numofpages = ceil($totalRecords / $limit);
				if ($current_page <= 0) {
					$page_num = 1;
				} else {
					if ($current_page <= $numofpages) {
						$page_num = $current_page;
					} else {
						$page_num = 1;
					}
				}
				$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? intval($_GET['offset']) : 0;
				$dataShowProduct = array_slice($dataProduct, $offset, $limit);
				$showPagination = $this->pagination->pagination($numofpages, $page_num);
				$output = [
					'data' 				=> $dataShowProduct,
					'property'          => $typeProperty,
					'propertyProduct'   => $baseProperty,
					'totalRecords'      => $totalRecords,
					'fetched'           => $limit,
					'offset'            => $offset,
					'pagination'		=> $showPagination ? $showPagination : ''
				];
				echo json_encode($output);
			} else {
				$output = [
					'data' 				=> $dataProduct,
				];
				echo json_encode($output);
			}
		}

		/**
		 * filterByDate
		 *
		 * @param  mixed $fromDate
		 * @param  mixed $toDate
		 * @param  mixed $sort
		 * @return void
		 */
		public function filterByDate($fromDate, $toDate, $sort)
		{
			global $dataWithPagination, $dataProperty, $typeProperty;
			$dataProperty = $this->properties->getPropertyData();
			$typeProperty = $this->properties->getPropertyType();
			$data = $this->products->getProductByDate($fromDate, $toDate);
			if ($data) {
				$totalRecords = count($data);
				$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
				$totalPage = ceil($totalRecords /$this->limit);
				if ($current_page > $totalPage){
					$current_page = $totalPage;
				}
				else if ($current_page < 1){
					$current_page = 1;
				}
				$start = ($current_page - 1)*$this->limit;
				foreach ($data as $value) {
					$getPropertyID[] = $this->products->getPropertyID($value['product_id']);
				}
				if(!empty($_SESSION['from_date']) && !empty($_SESSION['to_date'])) {
					$dataWithPagination = $this->products->filterDataByDate($start, $this->limit, $fromDate, $toDate, $sort);
				}
				require_once "./mvc/views/home.php";
			} else {
				echo ("<script type='text/javascript'>alert('Not found!');</script>");
				unset($_SESSION['postFilter']);
				header("Refresh:0; url=/");
				session_unset();
			}
			
			return $dataWithPagination;
		}

	}