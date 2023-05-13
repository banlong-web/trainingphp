<?php

class Pagination
{
     /**
	 * pagination
	 *
	 * @param  mixed $self
	 * @param  mixed $numofpages
	 * @param  mixed $page_num
	 * @return void
	 */
	public function pagination($numofpages, $page_num)
	{
		$page_pagination = '';
		if($numofpages > 1) {
			$range = 4;
			$range_min = ($range % 2 == 0) ? ($range / 2) - 1 : ($range - 1 ) / 2;
			$range_max = ($range % 2 == 0) ? $range_min + 1 : $range_min;
			$page_max = $page_num + $range_max;
			$page_min = $page_num - $range_min;

			$page_min = ($page_min < 1) ? 1 : $page_min;
			$page_max = ($page_max < ($page_min + $range - 1)) ? $page_min + $range - 1 : $page_max;

			if ($page_max > $numofpages)
			{
				$page_min = ($page_min > 1) ? $numofpages - $range + 1 : 1;
				$page_max = $numofpages;
			}

			$page_min = ($page_min < 1) ? 1 : $page_min;
			$page_min = ($page_min < 1) ? 1 : $page_min;

			// if ( ($page_num > ($range - $range_min)) && ($numofpages > $range) ) {
			// 	$page_pagination .= '<a title="First" href="'.$self.'?page=1">First</a> ';
			// }

			if ($page_num != 1) {
				$page_pagination .= '<a class="prev-page" 
				href="'.(isset($_GET['search']) ? '?search='.$_GET["search"].'&page='.($page_num - 1).'' : '?page='.($page_num - 1).'').'"
				data-page="'.($page_num - 1).'"
				>
				<i class="bx bx-chevrons-left"></i></a> ';
			}
			$page_pagination .= '<span>Page '.$page_num.' Of '.$numofpages.'</span>';
			// for ($i = $page_min;$i <= $page_max;$i++) {
			// 	if ($i == $page_num)
			// 		$page_pagination .= "<span class='current-page active'>" . $i . '</span> ';
			// 	else {
			// 		$page_pagination.= '<a class="page-item" 
			// 		href="'.(isset($_GET['search']) ? '?search='.$_GET["search"].'&?page='.$i.'' : '?page='.$i.'').'" data-page="'.$i.'">'.$i.'</a> ';
			// 	}
			// }

			if ($page_num < $numofpages) {
				$page_pagination.= ' <a class="next-page" 
				href="'.(isset($_GET['search']) ? '?search='.$_GET["search"].'&page='.($page_num + 1).'' : '?page='.($page_num + 1).'').'" data-page="'.($page_num + 1).'">
				<i class="bx bx-chevrons-right"></i></a>';
			}

			// if (($page_num< ($numofpages - $range_max)) && ($numofpages > $range)) {
			// 	$page_pagination .= ' <a title="Last" href="'.$self.'?page='.$numofpages. '">Last</a> ';
			// }             
		}
			return $page_pagination;
	}
}