<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Template {
	
	public $datetemp = NULL;
	public $limit = NULL;
	public $offset = NULL;
	public $totalrow = NULL;
	public $templatesetting = NULL;
	public $commentarea = NULL;
	public $title;
	public $metakeyword;
	public $metadescription;
	public $slide = NULL;
	public $categoryAtr = NULL;
	public $dir = NULL;

	function checkTemplate($display){
		global $SConfig;
		$_this =& get_instance();		
		$homepattern = '#<\s*?kaffah_ishome\b[^>]*>(.*?)</kaffah_ishome\b[^>]*>#s';
		$pagepattern = '#<\s*?kaffah_ispage\b[^>]*>(.*?)</kaffah_ispage\b[^>]*>#s';
		$categorypattern = '#<\s*?kaffah_iscategory\b[^>]*>(.*?)</kaffah_iscategory\b[^>]*>#s';
		$categoryproductpattern = '#<\s*?kaffah_iscategory_product\b[^>]*>(.*?)</kaffah_iscategory_product\b[^>]*>#s';		
		$singlepattern = '#<\s*?kaffah_issingle\b[^>]*>(.*?)</kaffah_issingle\b[^>]*>#s';
		$singleproductpattern = '#<\s*?kaffah_issingle_product\b[^>]*>(.*?)</kaffah_issingle_product\b[^>]*>#s';
		$searchpattern = '#<\s*?kaffah_issearch\b[^>]*>(.*?)</kaffah_issearch\b[^>]*>#s';

		$foreachpattern = '#<\s*?kaffah_foreach_loop\b[^>]*>(.*?)</kaffah_foreach_loop\b[^>]*>#s';

		$arraywarn = array();

		/* check home looping */
		$home = preg_match_all($homepattern,$display,$homeresult);
		if(!empty($homeresult[0][0])){
			$foreach = preg_match_all($foreachpattern,$homeresult[0][0],$foreachresult);
			if(count($foreachresult[0]) > 5){
				$arraywarn['home_warning'] = 'Looping di home tidak boleh lebih dari 5';
			}
			else{
				$arraywarn['home_warning'] = TRUE;
			}
		}
		else{
			$arraywarn['home_warning'] = "Tidak ada '&lt;kaffah_ishome&gt;'. ";
		}

		/* check page looping */
		$page = preg_match_all($pagepattern,$display,$pageresult);
		if(!empty($pageresult[0][0])){
			$foreach = preg_match_all($foreachpattern,$pageresult[0][0],$foreachresult);
			if(count($foreachresult[0]) > 2){
				$arraywarn['page_warning'] = 'Looping di page tidak boleh lebih dari 2';
			}
			else{
				$arraywarn['page_warning'] = TRUE;
			}			
		}
		else{
			$arraywarn['page_warning'] = "Tidak ada '&lt;kaffah_ispage&gt;'. ";
		}

		/* check category looping */
		$category = preg_match_all($categorypattern,$display,$categoryresult);
		if(!empty($categoryresult[0][0])){
			$foreach = preg_match_all($foreachpattern,$categoryresult[0][0],$foreachresult);
			if(count($foreachresult[0]) > 2){
				$arraywarn['category_warning'] = 'Looping di category tidak boleh lebih dari 2';
			}
			else{
				$arraywarn['category_warning'] = TRUE;
			}			
		}
		else{
			$arraywarn['category_warning'] = "Tidak ada '&lt;kaffah_iscategory&gt;'. ";
		}	

		/* check category product looping */
		$categoryproduct = preg_match_all($categoryproductpattern,$display,$categoryproductresult);
		if(!empty($categoryproductresult[0][0])){
			$foreach = preg_match_all($foreachpattern,$categoryproductresult[0][0],$foreachresult);
			if(count($foreachresult[0]) > 2){
				$arraywarn['category_product_warning'] = 'Looping di category product tidak boleh lebih dari 2';
			}
			else{
				$arraywarn['category_product_warning'] = TRUE;
			}			
		}
		else{
			$arraywarn['category_product_warning'] = "Tidak ada '&lt;kaffah_iscategory_product&gt;'. ";
		}		

		/* check single looping */
		$single = preg_match_all($singlepattern,$display,$singleresult);
		if(!empty($singleresult[0][0])){
			$foreach = preg_match_all($foreachpattern,$singleresult[0][0],$foreachresult);
			if(count($foreachresult[0]) > 2){
				$arraywarn['single_warning'] = 'Looping di single tidak boleh lebih dari 2';
			}
			else{
				$arraywarn['single_warning'] = TRUE;
			}			
		}
		else{
			$arraywarn['single_warning'] = "Tidak ada '&lt;kaffah_issingle&gt;'. ";
		}	


		/* check single product looping */
		$single = preg_match_all($singleproductpattern,$display,$singleproductresult);
		if(!empty($singleproductresult[0][0])){
			$foreach = preg_match_all($foreachpattern,$singleproductresult[0][0],$foreachresult);
			if(count($foreachresult[0]) > 2){
				$arraywarn['single_product_warning'] = 'Looping di single product tidak boleh lebih dari 2';
			}
			else{
				$arraywarn['single_product_warning'] = TRUE;
			}			
		}
		else{
			$arraywarn['single_product_warning'] = "Tidak ada '&lt;kaffah_issingle_product&gt;'. ";
		}	


		/* check single product looping */
		$search = preg_match_all($searchpattern,$display,$searchresult);
		if(!empty($searchresult[0][0])){
			$foreach = preg_match_all($foreachpattern,$searchresult[0][0],$foreachresult);
			if(count($foreachresult[0]) > 2){
				$arraywarn['search_warning'] = 'Looping di search tidak boleh lebih dari 2';
			}
			else{
				$arraywarn['search_warning'] = TRUE;
			}			
		}
		else{
			$arraywarn['search_warning'] = "Tidak ada '&lt;kaffah_issearch&gt;'. ";
		}						
		

		return $arraywarn;

	}

	function posParsing($template=NULL){
		global $SConfig;
		$_this =& get_instance();
		$_this->load->model('Global_model');
		$_this->load->model('Site_model');		

		/* init */
		$domainatr = $_this->main->allAtr ;
		$this->templatesetting = $_this->Site_model->getTemplateSetting($domainatr->domain_id);
		$templatesett = $this->templatesetting;
				
		/* all pattern */
		$categorypattern = '#<\s*?kaffah_iscategory\b[^>]*>(.*?)</kaffah_iscategory\b[^>]*>#s';
		$searchpattern = '#<\s*?kaffah_issearch\b[^>]*>(.*?)</kaffah_issearch\b[^>]*>#s';
		$categoryproductpattern = '#<\s*?kaffah_iscategory_product\b[^>]*>(.*?)</kaffah_iscategory_product\b[^>]*>#s';
		$pagepattern = '#<\s*?kaffah_ispage\b[^>]*>(.*?)</kaffah_ispage\b[^>]*>#s';
		$singlepattern = '#<\s*?kaffah_issingle\b[^>]*>(.*?)</kaffah_issingle\b[^>]*>#s';
		$singleproductpattern = '#<\s*?kaffah_issingle_product\b[^>]*>(.*?)</kaffah_issingle_product\b[^>]*>#s';
		$homepattern = '#<\s*?kaffah_ishome\b[^>]*>(.*?)</kaffah_ishome\b[^>]*>#s';
		$foreachpattern = '#<\s*?kaffah_foreach_loop\b[^>]*>(.*?)</kaffah_foreach_loop\b[^>]*>#s';
		$loginpattern = '#<\s*?kaffah_login_status\b[^>]*>(.*?)</kaffah_login_status\b[^>]*>#s';
		$kaffahepattern = '#<\s*?kaffah_e\b[^>]*/>#s';
		$commentareapattern = '#<\s*?kaffah_comment_area\b[^>]*>(.*?)</kaffah_comment_area\b[^>]*>#s';
		$kaffahcommentlistpattern = '#<\s*?kaffah_commentlist\b[^>]*/>#s';
		$kaffahcommentfieldpattern = '#<\s*?kaffah_comment_field\b[^>]*/>#s';
		$kaffahcommentvalidationpattern = '#<\s*?kaffah_comment_validation\b[^>]*/>#s';
		$kaffahcommentcaptcha = '#<\s*?kaffah_comment_captcha\b[^>]*/>#s';
		$kaffahsliderpattern = '#<\s*?kaffah_slider\b[^>]*>(.*?)</kaffah_slider\b[^>]*>#s';	
		$breadcrumbpattern = '#<\s*?kaffah_breadcrumb\b[^>]*/>#s';								

		/* kaffah special area pattern */
		$kaffah___categorypattern = '#<\s*?kaffah___category\b[^>]*/>#s';
		$kaffah___searchpattern = '#<\s*?kaffah___search\b[^>]*/>#s';

		/* kaffah post paging */
		$kaffahhomepostpagingpattern = '#<\s*?kaffah_home_post_paging\b[^>]*/>#s';
		$kaffahcategorypostpagingpattern = '#<\s*?kaffah_category_post_paging\b[^>]*/>#s';
		$kaffahsearchpostpagingpattern = '#<\s*?kaffah_search_post_paging\b[^>]*/>#s';

		/* kaffah module pattern */
		$modulepattern = '#<\s*?kaffah_module\b[^>]*>(.*?)</kaffah_module\b[^>]*>#s';
		$forresult = array();
		
		/* ketika yang di akses adalah halaman depan maka */
		if($_this->main->isHome == TRUE){
			
			/*********************************************************************
			
			1. BANGUN TEMPLATE 
					
			********************************************************************/
			
			/* hapus bagian kategorinya */
			$removeIsCategory = preg_replace($categorypattern,'',$template);
			$removeIsCategoryProduct = preg_replace($categoryproductpattern,'',$removeIsCategory);

			/* remove kaffah_issearching */			
			$removeisSearching = preg_replace($searchpattern,'',$removeIsCategoryProduct);
			
			/* hapus bagian page detilnya */
			$removeIsPage = preg_replace($pagepattern,'',$removeisSearching);
			
			$removeIsSingleProduct = preg_replace($singleproductpattern,'',$removeIsPage);

			$removeIsSingle = preg_replace($singlepattern,'',$removeIsSingleProduct);
			
			/* tentukan dari mana tag home kaffah bermula */
			$isHomePosStart = strpos($removeIsSingle,'<kaffah_ishome>');
			
			/* menentukan tag home kaffah berakhir */
			$isHomePosEnd = strpos($removeIsSingle,'</kaffah_ishome>');
			
			/* ambil bagian headnya, sampai ketemu bagian home kaffah */
			$head = substr($removeIsSingle,0,$isHomePosStart);
			
			/* ambil dari home hingga akhir */
			$foot = substr($removeIsSingle,$isHomePosEnd);
			
			/* ambil bagian ishome dari template tersebut */
			$homeRegex = preg_match($homepattern,$template,$result);
			
			/* kemudian tampilkan kesemuanya */
			$display = $head.@$result[0].$foot;


			/*********************************************************************
			
			2. REPLACE BAGIAN LOOPING
						
			********************************************************************/						
			/* lakukan replace bagian looping */
			@$displayfix = $this->kaffahForEach($foreachpattern,$result[0],$display);				
		}
		
		else if($_this->main->isSearch == TRUE){

			/*********************************************************************
			
			1. BANGUN TEMPLATE 
					
			********************************************************************/
			if($_this->uri->total_segments() == 1){
				redirect(base_url());
			}
			
			/* hapus bagian page detilnya */
			$removeIsHome = preg_replace($homepattern,'',$template);
			$removeIsCategory = preg_replace($categorypattern,'',$removeIsHome);
			$removeIsCategoryProduct = preg_replace($categoryproductpattern,'',$removeIsCategory);

			$removeIsPage = preg_replace($pagepattern,'',$removeIsCategoryProduct);
			
			$removeIsSingleProduct = preg_replace($singleproductpattern,'',$removeIsPage);
			$removeIsSingle = preg_replace($singlepattern,'',$removeIsSingleProduct);

			$isSearchStart = strpos($removeIsSingle,'<kaffah_issearch>');			
			$isSearchEnd = strpos($removeIsSingle,'</kaffah_issearch>');

			$head = substr($removeIsSingle,0,$isSearchStart);
			$foot = substr($removeIsSingle,$isSearchEnd);

			$categoryRegex = preg_match('#<\s*?kaffah_issearch\b[^>]*>(.*?)</kaffah_issearch\b[^>]*>#s',$template,$result);

			@$display = $head.$result[0].$foot;

			/* isCategory atribut */
			$display = $this->kaffah___category($display,$kaffah___searchpattern);			

			@$displayfix = $this->kaffahForEach($foreachpattern,$result[0],$display);			
		}


		else if($_this->main->isCategoryProduct == TRUE){

			/*********************************************************************
			
			1. BANGUN TEMPLATE 
					
			********************************************************************/

			if($_this->uri->total_segments() == 1){
				redirect(base_url());
			}
			
			/* hapus bagian page detilnya */
			$removeIsHome = preg_replace($homepattern,'',$template);
			$removeIsCategory = preg_replace($categorypattern,'',$removeIsHome);

			/* remove kaffah_issearching */			
			$removeisSearching = preg_replace($searchpattern,'',$removeIsCategory);

			$removeIsPage = preg_replace($pagepattern,'',$removeisSearching);
			
			$removeIsSingleProduct = preg_replace($singleproductpattern,'',$removeIsPage);
			$removeIsSingle = preg_replace($singlepattern,'',$removeIsSingleProduct);

			$isCategoryPosStart = strpos($removeIsSingle,'<kaffah_iscategory_product>');			
			$isCategoryPosEnd = strpos($removeIsSingle,'</kaffah_iscategory_product>');

			$head = substr($removeIsSingle,0,$isCategoryPosStart);
			$foot = substr($removeIsSingle,$isCategoryPosEnd);

			$categoryRegex = preg_match('#<\s*?kaffah_iscategory_product\b[^>]*>(.*?)</kaffah_iscategory_product\b[^>]*>#s',$template,$result);

			/* kemudian tampilkan kesemuanya */
			@$display = $head.$result[0].$foot;

			/* isCategory atribut */
			$display = $this->kaffah___category($display,$kaffah___categorypattern);

			@$displayfix = $this->kaffahForEach($foreachpattern,$result[0],$display);	
		}

		else if($_this->main->isCategory == TRUE){			
			/*********************************************************************
			
			1. BANGUN TEMPLATE 
					
			********************************************************************/

			
			/* hapus bagian page detilnya */
			$removeIsHome = preg_replace($homepattern,'',$template);
			$removeIsCategoryProduct = preg_replace($categoryproductpattern,'',$removeIsHome);

			/* remove kaffah_issearching */			
			$removeisSearching = preg_replace($searchpattern,'',$removeIsCategoryProduct);			
			$removeIsPage = preg_replace($pagepattern,'',$removeisSearching);

			$removeIsSingleProduct = preg_replace($singleproductpattern,'',$removeIsPage);

			$removeIsSingle = preg_replace($singlepattern,'',$removeIsSingleProduct);


			$isCategoryPosStart = strpos($removeIsSingle,'<kaffah_iscategory>');			
			$isCategoryPosEnd = strpos($removeIsSingle,'</kaffah_iscategory>');

			$head = substr($removeIsSingle,0,$isCategoryPosStart);
			$foot = substr($removeIsSingle,$isCategoryPosEnd);

			$categoryRegex = preg_match('#<\s*?kaffah_iscategory\b[^>]*>(.*?)</kaffah_iscategory\b[^>]*>#s',$template,$result);

			/* kemudian tampilkan kesemuanya */
			$display = $head.$result[0].$foot;

			/* isCategory atribut */
			$display = $this->kaffah___category($display,$kaffah___categorypattern);

			$displayfix = $this->kaffahForEach($foreachpattern,$result[0],$display);	
		}

		else if($_this->main->isSingle == TRUE){			

			/* hapus bagian kategorinya */
			$removeIsCategory = preg_replace($categorypattern,'',$template);

			$removeIsCategoryProduct = preg_replace($categoryproductpattern,'',$removeIsCategory);

			/* remove kaffah_issearching */				
			$removeisSearching = preg_replace($searchpattern,'',$removeIsCategoryProduct);				
			
			$removeIsPage = preg_replace($pagepattern,'',$removeisSearching);

			$removeIsSingleProduct = preg_replace($singleproductpattern,'',$removeIsPage);		
			
			/* hapus bagian page detilnya */
			$removeIsHome = preg_replace($homepattern,'',$removeIsSingleProduct);
			
			/* tentukan dari mana tag home kaffah bermula */
			$isPagePosStart = strpos($removeIsHome,'<kaffah_issingle>');
			
			/* menentukan tag home kaffah berakhir */
			$isPagePosEnd = strpos($removeIsHome,'</kaffah_issingle>');
			
			/* ambil bagian headnya, sampai ketemu bagian home kaffah */
			$head = substr($removeIsHome,0,$isPagePosStart);
			
			/* ambil dari home hingga akhir */
			$foot = substr($removeIsHome,$isPagePosEnd);
			
			/* ambil bagian ishome dari template tersebut */
			$homeRegex = preg_match('#<\s*?kaffah_issingle\b[^>]*>(.*?)</kaffah_issingle\b[^>]*>#s',$template,$result);
			
			/* kemudian tampilkan kesemuanya */
			@$display = $head.$result[0].$foot;
			$display = $this->kaffahCommentArea($display,$commentareapattern);

			/* jika komentar tidak di aktifkan */
			if($this->commentarea == TRUE){
				$display = $this->kaffahLoginComment($display,$loginpattern);
				$display = $this->kaffahCommentField($display,$kaffahcommentfieldpattern);
				$display = $this->kaffahCommentValidation($display,$kaffahcommentvalidationpattern);
				$display = $this->kaffahCommentList($display,$kaffahcommentlistpattern);				
				$display = $this->kaffahcommentcaptcha($display,$kaffahcommentcaptcha);
			}				
						
			$displayfix = $this->kaffahSingleParse($display,'post',$foreachpattern);
		}

		else if($_this->main->isSingleProduct == TRUE){			

			/* hapus bagian kategorinya */
			$removeIsCategory = preg_replace($categorypattern,'',$template);

			$removeIsCategoryProduct = preg_replace($categoryproductpattern,'',$removeIsCategory);
			
			/* remove kaffah_issearching */				
			$removeisSearching = preg_replace($searchpattern,'',$removeIsCategoryProduct);			
			
			$removeIsPage = preg_replace($pagepattern,'',$removeisSearching);

			$removeIsSingle = preg_replace($singlepattern,'',$removeIsPage);		
			
			/* hapus bagian page detilnya */
			$removeIsHome = preg_replace($homepattern,'',$removeIsSingle);
			
			/* tentukan dari mana tag home kaffah bermula */
			$isPagePosStart = strpos($removeIsHome,'<kaffah_issingle_product>');
			
			/* menentukan tag home kaffah berakhir */
			$isPagePosEnd = strpos($removeIsHome,'</kaffah_issingle_product>');
			
			/* ambil bagian headnya, sampai ketemu bagian home kaffah */
			$head = substr($removeIsHome,0,$isPagePosStart);
			
			/* ambil dari home hingga akhir */
			$foot = substr($removeIsHome,$isPagePosEnd);
			
			/* ambil bagian ishome dari template tersebut */
			$homeRegex = preg_match('#<\s*?kaffah_issingle_product\b[^>]*>(.*?)</kaffah_issingle_product\b[^>]*>#s',$template,$result);
			
			/* kemudian tampilkan kesemuanya */
			@$display = $head.$result[0].$foot;
			$display = $this->kaffahCommentArea($display,$commentareapattern);

			/* jika komentar tidak di aktifkan */
			if($this->commentarea == TRUE){
				$display = $this->kaffahLoginComment($display,$loginpattern);
				$display = $this->kaffahCommentField($display,$kaffahcommentfieldpattern);
				$display = $this->kaffahCommentValidation($display,$kaffahcommentvalidationpattern);
				$display = $this->kaffahCommentList($display,$kaffahcommentlistpattern);	
				$display = $this->kaffahcommentcaptcha($display,$kaffahcommentcaptcha);			
			}						

			$displayfix = $this->kaffahSingleParse($display,'product',$foreachpattern);			
		}		

		else if($_this->main->isPage == TRUE){
			/* hapus bagian kategorinya */
			$removeIsCategory = preg_replace($categorypattern,'',$template);

			$removeIsCategoryProduct = preg_replace($categoryproductpattern,'',$removeIsCategory);

			/* remove kaffah_issearching */				
			$removeisSearching = preg_replace($searchpattern,'',$removeIsCategoryProduct);	

			$removeIsSingleProduct = preg_replace($singleproductpattern,'',$removeisSearching);
			
			$removeIsSingle = preg_replace($singlepattern,'',$removeIsSingleProduct);
			
			/* hapus bagian page detilnya */
			$removeIsHome = preg_replace($homepattern,'',$removeIsSingle);
			
			/* tentukan dari mana tag home kaffah bermula */
			$isPagePosStart = strpos($removeIsHome,'<kaffah_ispage>');
			
			/* menentukan tag home kaffah berakhir */
			$isPagePosEnd = strpos($removeIsHome,'</kaffah_ispage>');
			
			/* ambil bagian headnya, sampai ketemu bagian home kaffah */
			$head = substr($removeIsHome,0,$isPagePosStart);
			
			/* ambil dari home hingga akhir */
			$foot = substr($removeIsHome,$isPagePosEnd);
			
			/* ambil bagian ispage dari template tersebut */
			$pageRegex = preg_match($pagepattern,$template,$result);
			
			/* kemudian tampilkan kesemuanya */
			@$display = $head.$result[0].$foot;
			$displayfix = $this->kaffahSingleParse($display,'page',$foreachpattern);			
		}
		
		else{
			$displayfix = $template;
		}

		/*********************************************************************
		
		4. REPLACE ATRIBUT TEMPLATE YANG KECIL-KECIL
				
		********************************************************************/	
		


		/* array search for parsing */
		$array_search  = array(	'#<\s*?kaffah_head\b[^>]*/>#s',								
								'#<\s*?kaffah_home_post_paging\b[^>]*/>#s',
								'#<\s*?kaffah_category_post_paging\b[^>]*/>#s',
								'#<\s*?kaffah_search_post_paging\b[^>]*/>#s',
								'#<\s*?kaffah_iscategory_product\b[^>]*>#s',
								'#</kaffah_iscategory_product\b[^>]*>#s',								
								'#<\s*?kaffah_ishome\b[^>]*>#s',
								'#</kaffah_ishome\b[^>]*>#s',
								'#<\s*?kaffah_ispage\b[^>]*>#s',
								'#</kaffah_ispage\b[^>]*>#s',
								'#<\s*?kaffah_issingle\b[^>]*>#s',										
								'#</kaffah_issingle\b[^>]*>#s',
								'#<\s*?kaffah_issingle_product\b[^>]*>#s',										
								'#</kaffah_issingle_product\b[^>]*>#s',
								'#<\s*?kaffah_login_status\b[^>]*>#s',										
								'#</kaffah_login_status\b[^>]*>#s',
								'#<\s*?kaffah_comment_area\b[^>]*>#s',										
								'#</kaffah_comment_area\b[^>]*>#s',
								'#<\s*?kaffah_iscategory\b[^>]*>#s',										
								'#</kaffah_iscategory\b[^>]*>#s',									
								'#<\s*?kaffah_blog_title\b[^>]*/>#s',
								'#<\s*?kaffah_blog_description\b[^>]*/>#s',
								'#<\s*?kaffah_page_menulist\b[^>]*/>#s',
								'#<\s*?kaffah_user_menu\b[^>]*/>#s',
								'#<\s*?kaffah_post_categorymenulist\b[^>]*/>#s',
								'#<\s*?kaffah_product_categorymenulist\b[^>]*/>#s',
								'#<\s*?kaffah_blog_homepageurl\b[^>]*/>#s',
								'#<\s*?kaffah_foot\b[^>]*/>#s',
								'#<\s*?kaffah_title\b[^>]*/>#s',									
								'#<\s*?kaffah_front_reg_update\b[^>]*/>#s',
								'#<\s*?kaffah_front_reg_newdomain\b[^>]*/>#s',
								'#<\s*?kaffah_front_reg_loginmenu\b[^>]*/>#s',
								'#<\s*?kaffah_current_url\b[^>]*/>#s'
							   );
		
		//echo $this->kaffahBlogTitle();
		// echo strrpos($display,'<kaffah_blog_title/>');

		// $searchpostpaging = $this->kaffahSearchPostPaging();

		/* array for replace parsing */
		$array_replace = array( $this->kaffahHead(),								
								$this->kaffahHomePostPaging(),
								$this->kaffahCategoryPostPaging(),
								$this->kaffahCategoryPostPaging(),
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',	
								'',
								'',															
								$this->kaffahBlogTitle(),
								$this->kaffahBlogDesc(),
								$this->kaffahPageMenuList($displayfix),
								$this->kaffahUserMenu(),
								$this->kaffahPostCatMenuList($displayfix),
								$this->kaffahProductCatMenuList($displayfix),
								$this->kaffahBlogHomePageURL(),
								$this->kaffahFoot(),
								$this->kaffahTitle(),
								get_new_update(),
								get_new_domain(),
								k_reg_menu_login(),
								current_url()
							   );
		
		/* replacing template with array for templaet */

		// echo $displayfix;

		$displayfix = preg_replace($array_search,$array_replace,$displayfix);

		list($displayfix,$slider,$isexist) = $this->kaffahSlider($displayfix,$kaffahsliderpattern);	

		$displayfix = $this->kaffahE($displayfix,$kaffahepattern,$slider,$isexist);

		

		/*********************************************************************
		
		5. REPLACE MODULE TEMPLATE 
				
		********************************************************************/	

		$displayfix = $this->kaffahModule($modulepattern,$displayfix);

		$displayfix = $this->kaffahBreadCrumb($breadcrumbpattern,$displayfix);

		return $displayfix;		
	}

	function kaffahSlider($display,$sliderpattern){		
		$_this =& get_instance();
		$doc = new DOMDocument();
		$templateRegex = preg_match_all($sliderpattern,$display,$result);
		$isexist = array();
		$slider = array();		

		if(!empty($result[0])){
			$screenwithtag = $result[0];	
			$screenwithnotag = $result[1];
			$fixeddisplayslide = NULL;
			
			/*ambil tag untuk slider */
			for($x=0;$x<count($screenwithtag);$x++){
				/* ambil fungsi untuk tag name kaffah e */
				libxml_use_internal_errors(true);
				$doc->loadHTML($screenwithtag[$x]);
				libxml_use_internal_errors(false);
				
				/* pengulangan ini didgunakan untuk mendapatkan satu demi kaffah_e */
				$foreachkaffahslider = $doc->getElementsByTagName('kaffah_slider');
				
				/* disini digunakan untuk menampilkan hasil dari kaffahe */
				foreach($foreachkaffahslider as $tag){	
				
			       $slider[$x]['name'] = $tag->getAttribute('name');				       
			       $slider[$x]['atr'] = $tag->getAttribute('atr');				       
					
				}		
				
			}

			$domainatr = $_this->main->allAtr ;

			$isExistModuleSetting = $_this->Site_model->is_modulexist(
						array(
							'option_name' => 'module_setting',
							'blog_id' => $domainatr->domain_id
							), 
						'kp_options');
	 
			$listmodule = unserialize(base64_decode($isExistModuleSetting['option_value']));

			for($a=0;$a<count($slider);$a++){
				$slide = @$listmodule[$slider[$a]['name']];

				if(!empty($slide)){

					foreach($slide as $key => $val){
						$nameslide = $key;			
					}

					$atribut = @$slide[$nameslide]['atribut'];		
					$atr = explode(',',$slider[$a]['atr']);

					/* ambil isi dari atribut dari tag html */
					for($x=0;$x<count($atr);$x++){
						$temp = explode('=',$atr[$x]);
						if($temp[1] != 'iteration'){
							$singleatr[$temp[0]] = $temp[1];
							@$varsingle = count($atribut[$temp[0]]);					
						}				
					}	

					/* digunakan untuk memasukkan htmlnya */
					for($y=0;$y<$varsingle;$y++){
						$fixeddisplayslide[$y] = $screenwithnotag[$a];
					}	

					/* ini untuk menampilkan isinya */
					$z = 0;
					for($y=0;$y<$varsingle;$y++){
						$z = $y + 1;
						foreach($singleatr as $key => $val){
							@$fixeddisplayslide[$y] = str_replace('%%'.$key.'%%', $atribut[$key][$y],$fixeddisplayslide[$y]);
							$fixeddisplayslide[$y] = str_replace('%%i%%', $z, $fixeddisplayslide[$y]);				
						}			
					}					

					$fixslide = @implode($fixeddisplayslide,"\n");	

					$fixslide = @implode($fixeddisplayslide,"\n");		
					// $display = str_replace($screenwithtag,$fixslide,$display);		
					// <font [^>]*\bstyle="([^"]*)"[^>]*>
					$search = array( '#<kaffah_module [^>]*\id="'.$slider[$a]['name'].'"[^>]*>(.*?)</kaffah_module\b[^>]*>#s');
					$replace = array($fixslide);
					
					/* replacing pattern */
					$display = preg_replace($search,$replace,$display);						
													
					$slider[$a] = $slider[$a]['name'];
					$isexist[$a] = true;

				}

				else{
					$slider[$a] = $slider[$a]['name'];
					$isexist[$a] = false;
				}

			}
							
		}						




		return array($display,$slider,$isexist);
	}

	function kaffahCommentArea($display,$commentareapattern){
		$_this =& get_instance();
		$_this->load->model('Post_model');
		$_this->load->model('Site_model');
		$domainatr = $_this->main->allAtr;
		
		$doc = new DOMDocument();
		$screen = NULL;
		$show = '';
		$mustbee = NULL;
			
		/* get comment setting */
		$all_ts_array = $_this->template->templatesetting;
		$template_setting = unserialize(base64_decode(@$all_ts_array['option_value']));
		$comment_setting = $template_setting['comment_setting'];

		/* ketika semua orang bisa berkomentar  */
		if(@array_key_exists('all_comment', $comment_setting)) {
			$this->commentarea = TRUE;		}

		else{	
			/* ambil khusus dibagian looping saja */
			$this->commentarea = FALSE;
			$display = preg_replace($commentareapattern,'',$display);
		}	

		return $display;
	}

	function kaffahCommentValidation($display,$kaffahcommentvalidationpattern){
		$_this =& get_instance();

		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		@$doc->loadHTML($display);
		libxml_use_internal_errors(false);
		
		/* get all tag */
		$kaffaheRegex = preg_match_all($kaffahcommentvalidationpattern,$display,$forresult);
		$screenwithtag = $forresult[0];
		$flashdata = array();
		
		for($x=0;$x<count($screenwithtag);$x++){
			/* ambil fungsi untuk tag name kaffah e */
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);
			
			/* pengulangan ini didgunakan untuk mendapatkan satu demi kaffah_e */
			$foreachkaffahe = $doc->getElementsByTagName('kaffah_comment_validation');
			
			/* disini digunakan untuk menampilkan hasil dari kaffahe */
			foreach($foreachkaffahe as $atr){

				if($_this->session->flashdata($atr->getAttribute('name'))){				
					$flashdata[$atr->getAttribute('name')] = $_this->session->flashdata($atr->getAttribute('name'));
				}				

				if(array_key_exists($atr->getAttribute('name'), $flashdata)){
					if($atr->getAttribute('name') == 'captcha'){
						if($flashdata['captcha'] == 'salah'){
							$value = $atr->getAttribute('name') . ' salah, mohon isi yang benar';		
						}
						else{
							$value = NULL;
						}
						
					}
					else{
						$value = NULL;
					}
					
				} 	

				else if(!array_key_exists($atr->getAttribute('name'), $flashdata) && ($_this->session->userdata('comment') == TRUE) ){
					$value = $atr->getAttribute('name') . ' belum di isi';					
				} 	

				else{
					$value = NULL;
				}							
			}
			
			/* replace kode dengan text yang ada dalam database */
			
			$display = str_replace($screenwithtag[$x], $value, $display);
		}
		
		/* display ini digunakan untuk menampilkan isi */
		$_this->session->unset_userdata('comment');
		return $display;	
	}

	function kaffahCommentField($display,$kaffahcommentfieldpattern){
		/* fungsi ini digunakan untuk mengambil semua isi dari kaffah_e */
		$_this =& get_instance();

		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		@$doc->loadHTML($display);
		libxml_use_internal_errors(false);
		
		/* get all tag */
		$kaffaheRegex = preg_match_all($kaffahcommentfieldpattern,$display,$forresult);
		$screenwithtag = $forresult[0];
		
		for($x=0;$x<count($screenwithtag);$x++){
			/* ambil fungsi untuk tag name kaffah e */
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);
			
			/* pengulangan ini didgunakan untuk mendapatkan satu demi kaffah_e */
			$foreachkaffahe = $doc->getElementsByTagName('kaffah_comment_field');
			
			/* disini digunakan untuk menampilkan hasil dari kaffahe */
			foreach($foreachkaffahe as $atr){	
				$flashdata[$atr->getAttribute('name')] = $_this->session->flashdata($atr->getAttribute('name'));
				
				if(!empty($flashdata[$atr->getAttribute('name')])){
					$value = $_this->session->flashdata($atr->getAttribute('name'));
				}

				else if(!empty($templateval[$atr->getAttribute('name')])){
					$value = $templateval[$atr->getAttribute('name')];
				}
				
				else{
					$value = $atr->getAttribute('text');
				}

				
			}
			
			/* replace kode dengan text yang ada dalam database */
			$display = str_replace($screenwithtag[$x], $value, $display);
		}
		
		/* display ini digunakan untuk menampilkan isi */
		return $display;
	}

	function kaffahcommentcaptcha($display,$kaffahcommentcaptcha){
		global $SConfig;
		$_this =& get_instance();
		
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		@$doc->loadHTML($display);
		libxml_use_internal_errors(false);
		
		
		$kaffaheRegex = preg_match_all($kaffahcommentcaptcha,$display,$forresult);
		$screenwithtag = $forresult[0];
		
		for($x=0;$x<count($screenwithtag);$x++){
		
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);
						
			$kaffahcaptcha = $doc->getElementsByTagName('kaffah_comment_captcha');
			foreach($kaffahcaptcha as $atr){	
				$width = $atr->getAttribute('width');
				$height = $atr->getAttribute('height');				
			}					
		}


		if(empty($width) || empty($height)){
			$width = 150;
			$height = 40;
		}

		$_this->load->helper('captcha');
		$vals = array(
			'word' => getRandomWord(6),
			'img_path'	=> './captcha/',
			'img_url'	=> base_url().'captcha/',
			'img_width'	=> $width,
		    'img_height' => $height,
		    'expiration' => 7200
		);

		$cap = create_captcha($vals);

		$data = array(
		    'captcha_time'	=> $cap['time'],
		    'ip_address'	=> $_this->input->ip_address(),
		    'word'	=> $cap['word']
	    );

	    $query = $_this->db->insert_string('kp_captcha', $data);
		$_this->db->query($query);

		$kaffahcommentcaptchafix = preg_replace($kaffahcommentcaptcha,$cap['image'],$display);	

		return $kaffahcommentcaptchafix;
	}

	function kaffahCommentList($display,$kaffahcommentlistpattern){
		global $SConfig;
		$_this =& get_instance();
		$_this->load->model('Comment_model');
		$_this->load->model('Global_model');
		$_this->load->helper('date');
		$domainatr = $_this->main->allAtr ;		

		/* tampilkan daftar artikel sesuai dengan domain + post  yang sedang di akses */
		if($_this->uri->segment(1) == 'artikel'){
			$article_name = $_this->uri->segment(3);
		}

		else if($_this->uri->segment(1) == 'produk'){
			$article_name = $_this->uri->segment(3);
		}

		/* dapatkan id dari artikelnya */
		$post = $_this->Global_model->select_single(array('blog_id' => $domainatr->domain_id, 'post_name' => $article_name ), 'kp_posts');

		/* get comment list array */
		$commentlistarray = $_this->Comment_model->get_comment_list(array('kp_comments.comment_approved' => 'terpasang', 'kp_posts.ID' => $post['ID'], 'kp_comments.comment_blog_id' => $domainatr->domain_id) );
		
		if($commentlistarray > 0){
			$sortarray = sort_array_comment($commentlistarray);	
			$display_array = substr(display_commentlist($sortarray,1),21,-5);	
		}
		else{
			$sortarray = NULL;
			$display_array = '<li class="depth-1"><div class="comment-info"><p class="nocomment"><kaffah_e name="tidak_ada_komentar" type="text" value="Tidak ada komentar" /></p></div></li>';
		}
			
		/* ... */
		$commentlist = '<ol class="commentlist">'.$display_array.'</ol>';

		$kaffahcommentlistfix = preg_replace($kaffahcommentlistpattern,$commentlist,$display);	
		return $kaffahcommentlistfix;
		// return $display_array;
	}

	function kaffahLoginComment($display,$loginpattern){
		global $SConfig;
		$_this =& get_instance();

		/* jika statusnya sudah login sebagai seorang member */		
		if($_this->main->getUser('logged_in') == TRUE){
			$removeLoginCommentPatternElemen = preg_replace($loginpattern,'',$display);	
		}
		
		else {
			$removeLoginCommentPatternElemen = $display;
		}
		
		return $removeLoginCommentPatternElemen;
	}

	function kaffah___Category($display,$pattern){
		$_this =& get_instance();
		$_this->load->model('Global_model');
		$_this->load->helper('inflector');		
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		@$doc->loadHTML($display);
		libxml_use_internal_errors(false);

		$domainatr = $_this->main->allAtr;

		/* get all tag */
		$kaffaheRegex = preg_match_all($pattern,$display,$forresult);
		$screenwithtag = $forresult[0];		

		/* get category */
		if($_this->uri->segment(1) == 'pencarian'){
			$category = $_this->uri->segment(2);
			$isExistCategory = 1;
		}
		else{
			$category = $_this->uri->segment(2);
			$isExistCategory = $_this->Global_model->select_single(array('slug' => $category, 'term_blog_id' => $domainatr->domain_id),'kp_terms');
			$this->categoryAtr = $isExistCategory;

		}		

		if(count($isExistCategory) > 0){

			/* looping ini digunakan untuk mengambil pattern dari kaffah___category() */
			for($x=0;$x<count($screenwithtag);$x++){

				/* ambil fungsi untuk tag value nantinya */
				libxml_use_internal_errors(true);
				$doc->loadHTML($screenwithtag[$x]);
				libxml_use_internal_errors(false);
				
				/* pengulangan ini didgunakan untuk mendapatkan satu demi kaffah_e */
				if($_this->uri->segment(1) == 'pencarian'){
					$kaffahcat = $doc->getElementsByTagName('kaffah___search');	
				}
				else{
					$kaffahcat = $doc->getElementsByTagName('kaffah___category');	
				}
								
				/* disini digunakan untuk menampilkan hasil dari kaffahe */
				foreach($kaffahcat as $atr){	
					$val = $atr->getAttribute('value');										
					$print = $atr->getAttribute('print');
					$none = $atr->getAttribute('none');
					@$attr = $atr->getAttribute('attr');								
				}

				if(!empty($print)){
					if($print == 'attribute' && !empty($attr)){
						$printattr = unserialize(base64_decode($isExistCategory[$print]));

						if(!empty($printattr)){
							@$value[$x] = $printattr[$attr];
						}
						else{
							@$value[$x] = $none;
						}
						

					}
					else{
						$value[$x] = $isExistCategory[$print];	
					}
					
				}
				else{
					$value[$x] = $val .''.humanize($category).'';
				}

			}
			


			$display = str_replace($screenwithtag, @$value, $display);
		}

		return $display;
	}

	function kaffahHomePostPaging(){
		global $SConfig;
		$_this =& get_instance();
		$domainatr = $_this->main->allAtr;
		if($_this->main->isHome == TRUE){
			$_this->load->library('pagination');
			$_this->load->library('coredb');

			/* ambil total record dari table post row */				
			$totalrow = $_this->coredb->count_table_row(array('kp_posts.post_type' => 'post', 'kp_posts.post_status' => 'publish', 'kp_posts.blog_id' => $domainatr->domain_id ),'kp_posts');								
			$config = array(
						'base_url' => base_url().'/no/',
						'total_rows' => $totalrow,
						'per_page' => get_limit_post(),
						'uri_segment' => 2,
						'use_page_numbers' => false,
						'full_tag_open' => '<div class="pagination">',
						'full_tag_close' => '</div>'
					);			

			$_this->pagination->initialize($config); 
			return $_this->pagination->create_links();
		}
	}

	function kaffahCategoryPostPaging(){		
		global $SConfig;
		$_this =& get_instance();

		$_this->load->library('pagination');
		$_this->load->library('coredb');
		/* ambil total record dari table post row */				
		$caturl = $_this->uri->segment(2);


		$domainatr = $_this->main->allAtr;

		if($_this->main->isCategory == TRUE){			
			if(!empty($caturl) && $caturl != 'no'){
				$totalrow = $_this->coredb->count_table_row("kp_posts.blog_id = '".$domainatr->domain_id."' AND kp_posts.post_type = 'post' AND kp_posts.post_status = 'publish' AND kp_posts.post_category LIKE '%$caturl%'",'kp_posts');								
			}
			else{
				$totalrow = $_this->coredb->count_table_row("kp_posts.blog_id = '".$domainatr->domain_id."' AND kp_posts.post_type = 'post' AND kp_posts.post_status = 'publish'",'kp_posts');								
			}
			
			
			$limit_post = get_limit_post();
		}	

		else if($_this->main->isCategoryProduct == TRUE){
			$totalrow = $_this->coredb->count_table_row("kp_posts.blog_id = '".$domainatr->domain_id."' AND kp_posts.post_type = 'product' AND kp_posts.post_status = 'publish' AND kp_posts.post_category LIKE '%$caturl%'",'kp_posts');								
			$limit_post = get_limit_post();
		}

		else if($_this->main->isSearch == TRUE){
			$totalrow = $_this->coredb->count_table_row("kp_posts.blog_id = '".$domainatr->domain_id."' AND kp_posts.post_type NOT IN ('page', 'attachment') AND kp_posts.post_status = 'publish' AND kp_posts.post_title LIKE '%$caturl%'",'kp_posts');								
			$limit_post = get_limit_post();
		}	

		if($_this->uri->segment(2) == 'no'){
				$config = array(
					'base_url' => base_url().'/'.$_this->uri->segment(1).'/no/',
					'total_rows' => @$totalrow,
					'per_page' => @$limit_post,
					'uri_segment' => 3,
					'use_page_numbers' => false,
					'full_tag_open' => '<div class="pagination">',
					'full_tag_close' => '</div>'
				);
		}
		else{
			$config = array(
						'base_url' => base_url().'/'.$_this->uri->segment(1).'/'.$_this->uri->segment(2).'/no/',
						'total_rows' => @$totalrow,
						'per_page' => @$limit_post,
						'uri_segment' => 4,
						'use_page_numbers' => false,
						'full_tag_open' => '<div class="pagination">',
						'full_tag_close' => '</div>'
					);			
		}

						

		$_this->pagination->initialize($config); 
		return $_this->pagination->create_links();				
	}

	function kaffahForEachInside($display){
		$_this =& get_instance();
		$_this->load->model('Post_model');
		$_this->load->model('Site_model');
		$domainatr = $_this->main->allAtr;

		$pattern = '#<\s*?kaffah_foreach_loop\b[^>]*>(.*?)</kaffah_foreach_loop\b[^>]*>#s';

		$doc = new DOMDocument();
		$screen = NULL;
		$disp = '';
		
		$mustbee = NULL;
		$_this->main->tempforeach+=1;
		
		/* ambil khusus dibagian looping saja */
		$foreachRegex = preg_match_all($pattern,$display,$forresult);
		$screenwithtag = $forresult[0];
		$screen = $forresult[1];	

		if(!empty($screenwithtag)){

			for($x=0;$x<count($screenwithtag);$x++){
					libxml_use_internal_errors(true);
					$doc->loadHTML($screenwithtag[$x]);
					libxml_use_internal_errors(false);								
					$dispx[$x] = NULL;

					/* mulai parsing untuk setiap foreach looping */
					$foreachloop = $doc->getElementsByTagName('kaffah_foreach_loop');			
					
					/* pengulangan untuk mendapatkan atribut-atribut dari looping terkait */
					foreach ($foreachloop as $tag) {			
				       $atributtype = $tag->getAttribute('type');		       		       
				       $atributcat = $tag->getAttribute('category');
				       $atributlimit = $tag->getAttribute('limit');
				       $atributoffset = $tag->getAttribute('offset');
					}						
					

					$kaffahcat = $doc->getElementsByTagName('kaffah_post_image');


					foreach($kaffahcat as $atr){	
						$height = $atr->getAttribute('height');
						$width = $atr->getAttribute('width');	

					}	

					/* ambil atribut untuk post_content */
					$postcontent = $doc->getElementsByTagName('kaffah_post_content');
					foreach($postcontent as $atr){
						$atributintrotext = $atr->getAttribute('introtext');
					}

					/* ambil atribut untuk post_content */
					$postcontent = $doc->getElementsByTagName('kaffah_post_metatime');
					foreach($postcontent as $atr){
						$atributTimeString[] = $atr->getAttribute('string');
					}	

					if($atributtype == 'post'){
						
						if(!empty($atributcat)){
							/* media and content filter */
							$mediafilter = 'f';
							$contentfilter = $atributcat;
											
						}
						
						/* kecuali daripada itu */
						else{
							
							/* media and content filter */
							if($_this->uri->segment(1) == 'artikel'){
								$mediafilter = 'f';
								$contentfilter = $_this->uri->segment(2);
							}

							else{						
								$mediafilter = NULL;
								$contentfilter = NULL;
							}

							$replacepost = $_this->Post_model->get_post($atributtype,'publish',$domainatr->domain_id, get_limit_post($atributtype,$atributlimit),get_offset($atributoffset),$mediafilter,$contentfilter);


							if(!empty($replacepost)){
								$_this->load->helper('inflector');
								$this->title = humanize($contentfilter);							

								/* replace foreachloop inside */
								foreach($replacepost as $record){
									/* for category post */						
									$this->datetemp = $record['post_date'];
									//					<kaffah_post_metatime string="d-M-Y"/>
									$getTimePattern = '#<kaffah_post_metatime\sstring="([^>]*)"/>#s';
									
									/* array to replace */
									$search = array(
													'#<\s*?kaffah_post_title\b[^>]*/>#s',
													'#<\s*?kaffah_post_content\b[^>]*/>#s',
													'#<\s*?kaffah_post_permalink\b[^>]*/>#s',
													'#<\s*?kaffah_post_image\b[^>]*/>#s',
													'#<\s*?kaffah_post_metaauthor\b[^>]*/>#s',
													'#<\s*?kaffah_post_commentcount\b[^>]*/>#s',
													'#<\s*?kaffah_post_price\b[^>]*/>#s',
													'#<\s*?kaffah_post_stock\b[^>]*/>#s',
													'#<\s*?kaffah_post_code\b[^>]*/>#s',
													'#<\s*?kaffah_product_price\b[^>]*/>#s',
													'#<\s*?kaffah_product_stock\b[^>]*/>#s',
													'#<\s*?kaffah_product_code\b[^>]*/>#s',										
													'#<\s*?kaffah_post_category\b[^>]*/>#s',
													'#<\s*?kaffah_post_category_permalink\b[^>]*/>#s',
													);


									
									/* replace array */
									$replace = array(
													$record['post_title'],
													character_limiter(strip_tags($record['post_content']), @$atributintrotext),
													$this->kaffahPostPermalink($record,$domainatr,$atributtype),
													$this->kaffahPostImage($record,$domainatr,$atributtype,$display,'#<\s*?kaffah_post_image\b[^>]*/>#s',FALSE,$width,$height),
													$record['name'],
													$record['comment_count'],
													'Rp '.@number_format($record['post_price'],0,",","."),	
													$record['post_stock'],
													$record['post_code'],
													'Rp '.@number_format($record['post_price'],0,",","."),	
													$record['post_stock'],
													$record['post_code'],										
													$this->kaffahPostCategory($record,$domainatr),
													$this->kaffahPostCategoryPermalink($record,$domainatr,$atributtype),
													);


									/* replacing pattern */
									@$disp[$x] .= preg_replace($search,$replace,@$screen[$x]);
									$disp[$x] = preg_replace_callback($getTimePattern,'kaffahPostMetaTime',$disp[$x]);
								}	

								$dispx[$x] = $disp[$x];		
								
							}


							// echo $showx[1];
							
							/* lakukan replace antara pattern looping dengan yang ada dalam database */
							

							//echo $screenwithtag[$x];
						}				
					}

					/* ketika atributnya adalah post */
					else if($atributtype == 'product'){
						
						/* jika atribut kategori tidak kosong */
						if(!empty($atributcat)){
							/* media and content filter */
							$mediafilter = 'f';
							$contentfilter = $atributcat;					
						}
						
						/* kecuali daripada itu */
						else{
							
							/* media and content filter */
							if($_this->uri->segment(1) == 'produk'){
								$mediafilter = 'f';
								$contentfilter = $_this->uri->segment(2);
							}

							else{						
								$mediafilter = NULL;
								$contentfilter = NULL;
							}
						}					

						$replacepost = $_this->Post_model->get_post($atributtype,'publish',$domainatr->domain_id, get_limit_post($atributtype,$atributlimit),get_offset($atributoffset),$mediafilter,$contentfilter);
									

						if(!empty($replacepost)){
							$_this->load->helper('inflector');
							$this->title = humanize($contentfilter);

							/* replace foreachloop inside */
							foreach($replacepost as $record){
								/* for category post */						
								$this->datetemp = $record['post_date'];						
								//					<kaffah_post_metatime string="d-M-Y"/>
								$getTimePattern = '#<kaffah_post_metatime\sstring="([^>]*)"/>#s';
								
								/* array to replace */
								$search = array(
												'#<\s*?kaffah_post_title\b[^>]*/>#s',
												'#<\s*?kaffah_post_content\b[^>]*/>#s',
												'#<\s*?kaffah_post_permalink\b[^>]*/>#s',
												'#<\s*?kaffah_post_image\b[^>]*/>#s',
												'#<\s*?kaffah_post_metaauthor\b[^>]*/>#s',
												'#<\s*?kaffah_post_commentcount\b[^>]*/>#s',
												'#<\s*?kaffah_post_price\b[^>]*/>#s',
												'#<\s*?kaffah_post_stock\b[^>]*/>#s',
												'#<\s*?kaffah_post_category\b[^>]*/>#s',
												'#<\s*?kaffah_post_category_permalink\b[^>]*/>#s',
												);
								
								/* replace array */
								$replace = array(
												$record['post_title'],
												character_limiter(strip_tags($record['post_content']), @$atributintrotext),
												$this->kaffahPostPermalink($record,$domainatr,$atributtype),
												$this->kaffahPostImage($record,$domainatr,$atributtype,$screenwithtag[$x],'#<\s*?kaffah_post_image\b[^>]*/>#s',TRUE,$width,$height),
												$record['name'],
												$record['comment_count'],
												'Rp '.number_format($record['post_price'],0,",","."),
												$record['post_stock'].' stok',
												$this->kaffahPostCategory($record,$domainatr),
												$this->kaffahPostCategoryPermalink($record,$domainatr,$atributtype),
												);
								
								/* replacing pattern */
									@$disp[$x] .= preg_replace($search,$replace,@$screen[$x]);
									$disp[$x] = preg_replace_callback($getTimePattern,'kaffahPostMetaTime',$disp[$x]);
								}	

								$dispx[$x] = $disp[$x];						
						}						
					}				

				}	


			for($x=0;$x<count($screenwithtag);$x++){
				 $display = str_replace($screenwithtag[$x],$dispx[$x],$display);
				
			}

			
			//echo $screenwithtag[0];
			// $mustbee = str_replace($screenwithtag[1],$showx[1],$display);
			// echo $showx[$x];
			/* kembalikan nilai untuk foreach */
			//$mustbee = preg_replace($pattern,'',$mustbee);

			return @$display;

		}
		else{

			return $display;
		}
	}

	function kaffahSingleParse($display,$type,$foreachpattern){
		$_this =& get_instance();
		$_this->load->model('Post_model');		

		$show = '';
		$domainatr = $_this->main->allAtr ;
		if($_this->uri->segment(1) == 'halaman'){
			$article_name = $_this->uri->segment(2);
		}

		else if($_this->uri->segment(1) == 'artikel'){
			$article_name = $_this->uri->segment(3);
		}

		else if($_this->uri->segment(1) == 'produk'){

			$article_name = $_this->uri->segment(3);
		}

		else{
		}
		
		$getTimePattern = '#<kaffah_post_metatime\sstring="([^>]*)"/>#s';
		
		if(!empty($article_name)){
			if($_this->uri->segment(2) =='konfirmasi_selesai'){
				$_this->load->library('custom');

				$record = $_this->custom->custom_page($_this->uri->segment(2));
				
				$_this->custom->temp_page = $_this->uri->segment(2);
			}
			else{
				$record = $_this->Post_model->get_post_detail($type, 'publish', $article_name, $domainatr->domain_id);
			}
			
		}
		else{			
			$_this->load->library('custom');					

			$record = $_this->custom->custom_page($_this->uri->segment(2));
			
			$_this->custom->temp_page = $_this->uri->segment(2);
		}



		$display = $this->kaffahForEachInside($display);		
		
		if(!empty($record)){

			@$atribut = unserialize(base64_decode($record['post_attribute'])) ;
			$this->datetemp = $record['post_date'];


			/* FOR TITLE */
			if(!empty($atribut['title'])){
				$this->title = $atribut['title'];
			}
			else{
				$this->title = $record['post_title'];	
			}
			
			/* FOR META KEYWORD */
			if(!empty($atribut['metakeyword'])){
				$this->metakeyword = $atribut['metakeyword'];
			}

			else{
				$this->metakeyword = str_replace($record['post_title'],' ', ',');
			}

			/* FOR META DESC  */
			if(!empty($atribut['metadescription'])){
				$this->metadescription = $atribut['metadescription'];
			}

			else{
				$this->metadescription = character_limiter(strip_tags($record['post_content']), 200);
			}			
			
			/* array to replace */
			$search = array(
							'#<\s*?kaffah_issingle_product\b[^>]*/>#s',
							'#<\s*?kaffah_post_title\b[^>]*/>#s',
							'#<\s*?kaffah_post_content\b[^>]*/>#s',
							'#<\s*?kaffah_post_type\b[^>]*/>#s',
							'#<\s*?kaffah_post_id\b[^>]*/>#s',
							'#<\s*?kaffah_comment_parent\b[^>]*/>#s',
							'#<\s*?kaffah_post_permalink\b[^>]*/>#s',							
							'#<\s*?kaffah_post_price\b[^>]*/>#s',
							'#<\s*?kaffah_post_stock\b[^>]*/>#s',
							'#<\s*?kaffah_post_metaauthor\b[^>]*/>#s',
							'#<\s*?kaffah_post_commentcount\b[^>]*/>#s',
							'#<\s*?kaffah_post_category\b[^>]*/>#s',
							'#<\s*?kaffah_post_category_permalink\b[^>]*/>#s',
							'#<\s*?kaffah_form_shopping_cart\b[^>]*>#s',
							'#<\s*?kaffah_product_price\b[^>]*/>#s',
							'#<\s*?kaffah_product_stock\b[^>]*/>#s',
							'#<\s*?kaffah_product_code\b[^>]*/>#s',
							'#<\s*?kaffah_product_size\b[^>]*/>#s',
							'#<\s*?kaffah_product_color\b[^>]*/>#s',
							'#<\s*?kaffah_product_qty\b[^>]*/>#s',
							'#<\s*?kaffah_buy_button\b[^>]*/>#s',
							'#</kaffah_form_shopping_cart\b[^>]*>#s'
							);

			// #<\s*?kaffah_foreach_loop\b[^>]*>(.*?)</kaffah_foreach_loop\b[^>]*>#s
			
			/* replace array */
			$replace = array(
							'',
							$record['post_title'],
							$record['post_content'],
							$record['post_type'],
							$record['ID'],
							'',
							$this->kaffahPostPermalink($record,$domainatr,$type),
							$this->kaffahFormShoppingCart('product_price',$record),	
							$this->kaffahFormShoppingCart('product_stock',$record),
							@$record['name'],
							$record['comment_count'],
							$this->kaffahPostCategory($record,$domainatr,$type),
							$this->kaffahPostCategoryPermalink($record,$domainatr,$type),
							$this->kaffahFormShoppingCart('open'),
							$this->kaffahFormShoppingCart('product_price',$record),							
							$this->kaffahFormShoppingCart('product_stock',$record),
							$this->kaffahFormShoppingCart('product_code',$record),
							$this->kaffahFormShoppingCart('product_size',$record),
							$this->kaffahFormShoppingCart('product_color',$record),
							$this->kaffahFormShoppingCart('product_qty',$record),
							$this->kaffahFormShoppingCart('buy_button',$record),
							$this->kaffahFormShoppingCart('close')
							);
	
		

			/* replacing pattern */
			$display = preg_replace($search,$replace,$display);

			$display = $this->kaffahProductImageList($display,$record);				

			$display = preg_replace_callback($getTimePattern,'kaffahPostMetaTime',$display);

			$display = $this->kaffahPostImage($record,$domainatr,$type,$display,'#<\s*?kaffah_post_image\b[^>]*/>#s',FALSE,NULL,NULL);
			
			/* replace confirmation */
			$_this->load->library('custompage');

			/*************  POST COUNTER   ****************/
			$_this->load->model('Global_model');
			if(empty($record['post_counter'])){
				$counter = 1;
			}
			else{
				$counter = $record['post_counter'] + 1;
			}
			$_this->Global_model->update(array('ID' => $record['ID']),array('post_counter' => $counter),'kp_posts');


			$display = $_this->custompage->kaffahConfirmationPage($display);
		}
		
		else{
			redirect(base_url());
		}


		return $display;
	}

	function kaffahProductImageList($display,$record=NULL){
		$_this =& get_instance();
		$productimagelist = '#<\s*?kaffah_product_imagelist\b[^>]*>(.*?)</kaffah_product_imagelist\b[^>]*>#s';

		$imglist = array();

		$atribut = unserialize(base64_decode(@$record['post_attribute']));

		if(!empty($atribut)){
			$doc = new DOMDocument();
			libxml_use_internal_errors(true);
			@$doc->loadHTML($display);
			libxml_use_internal_errors(false);

			$kaffaheRegex = preg_match_all($productimagelist,$display,$forresult);
			$screenwithtag = $forresult[0];
			$screenwithnotag = $forresult[1];


			for($x=0;$x<count($screenwithtag);$x++){
				libxml_use_internal_errors(true);
				$doc->loadHTML($screenwithtag[$x]);
				libxml_use_internal_errors(false);

				$kaffah_product_imagelist = $doc->getElementsByTagName('kaffah_product_imagelist');
				
				/* disini digunakan untuk menampilkan hasil dari kaffahe */
				foreach($kaffah_product_imagelist as $atr){	
					if($atr->getAttribute('type') == 'all'){
						@$imglist[$x] .=  str_replace('%%imgurl%%',$record['post_image'],$screenwithnotag[$x]);
						for($y=0;$y<count(@$atribut['img']);$y++){						
							@$imglist[$x] .=  str_replace('%%imgurl%%',$atribut['img'][$y],$screenwithnotag[$x]);
						}
					}
					else if($atr->getAttribute('type') == 'prime'){
						@$imglist[$x] .=  str_replace('%%imgurl%%',$record['post_image'],$screenwithnotag[$x]);
					}
					else{
						for($y=0;$y<count($atribut['img']);$y++){
							@$imglist[$x] .=  str_replace('%%imgurl%%',$atribut['img'][$y],$screenwithnotag[$x]);
						}					
					}
				}			
			}
			
			$display = str_replace($screenwithtag, $imglist, $display);			

		}

		return $display;
	}

	function kaffahFormShoppingCart($atr=NULL,$record=NULL){
		$_this =& get_instance();
		
		$_this->load->library('custom');		

		if(($_this->custom->temp_page == NULL) && !array_key_exists($_this->custom->temp_page, $_this->custom->list_page)){
		
			if($_this->uri->segment(1) == 'produk'){
				if($atr == 'open'){
					return '<form  id="shop_cart" method="POST" action="'.base_url().'produk/beli">';
				}

				else if($atr == 'product_price'){
					if(!empty($record)){
						return 'Rp '.@number_format($record['post_price'],0,",",".");	
					}					
				}

				else if($atr == 'product_code'){
					if(!empty($record)){
						return $record['post_code'];	
					}
					
				}

				else if($atr == 'product_size'){
					if(!empty($record)){
						$post_atribut = unserialize(base64_decode(@$record['post_attribute']));
						// echo '<pre>'.print_r($post_atribut).'</pre>';
						$_this->load->helper('form');

						$size = explode(',', replace_( @$post_atribut['ukuran'], ' '));
						
						foreach($size as $row){
							$val[trim($row)] = trim($row);
						}							

						return form_dropdown('product_size', $val, '', 'class="atribut_select"');
					}
					
				}

				else if($atr == 'product_color'){
					if(!empty($record)){
						$post_atribut = unserialize(base64_decode(@$record['post_attribute']));
						// echo '<pre>'.print_r($post_atribut).'</pre>';
						$_this->load->helper('form');
						$color = explode(',', replace_( @$post_atribut['warna'], ' '));
						foreach($color as $row){
							$val[trim($row)] = trim($row);
						}							

						return form_dropdown('product_color', $val, '', 'class="atribut_select"');
					}
					
				}

				else if($atr == 'product_qty'){
					if(!empty($record)){
						// echo '<pre>'.print_r($post_atribut).'</pre>';
						$_this->load->helper('form');
						return  form_input(array('name'=>'qty', 'id'=>'qty', 'value'=>1));
					}
					
				}

				else if($atr == 'product_stock'){
					if(!empty($record) && ($record['post_stock'] > 0)  ){
						return $record['post_stock'].' <kaffah_e name="stock_tersedia" type="text" value="Stok tersedia" />';
					}

					else if(!empty($record) && ( $record['post_stock'] == '-') ){
						return '<kaffah_e name="stock_tersedia" type="text" value="Stok tersedia" />';
					}

					else{
						return '<kaffah_e name="stock_tidak_tersedia" type="text" value="Stok tidak tersedia" />';
					}
					
				}

				else if($atr == 'buy_button'){
					$displaybutton = '<input type="hidden" name="product_id" value="'.$record['ID'].'" />';
					$displaybutton .= '<input type="hidden" name="product_name" value="'.$record['post_name'].'" />';
					$displaybutton .= '<input type="hidden" name="product_price" value="'.$record['post_price'].'" />';
					$displaybutton .= '<input type="submit" class="cart le-button" value="Beli Produk!" />';
					return $displaybutton;
				}

				else{
					return '</form>';
				}			
			}

		}
	}

	function kaffahE($display,$kaffahepattern,$slider=NULL,$isexist=false){
		/* fungsi ini digunakan untuk mengambil semua isi dari kaffah_e */
		$_this =& get_instance();

		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		@$doc->loadHTML($display);
		libxml_use_internal_errors(false);
		
		/* get all tag */
		$kaffaheRegex = preg_match_all($kaffahepattern,$display,$forresult);
		$screenwithtag = $forresult[0];
		
		/* get value tag */
		$templatesetting = $this->templatesetting;

		if($templatesetting){
			$getoldtemplatesetting = unserialize(base64_decode($templatesetting['option_value']));
		}
		else{
			$getoldtemplatesetting['display_setting'] = array();
		}

		// print_r($getoldtemplatesetting);

		/* template value */
		$templateval = $getoldtemplatesetting['display_setting'];

		/* agar bisa di akses di satu halaman */
		$this->templatesetting = $getoldtemplatesetting;
		
		

		for($x=0;$x<count($screenwithtag);$x++){
			/* ambil fungsi untuk tag name kaffah e */
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);
			
			/* pengulangan ini didgunakan untuk mendapatkan satu demi kaffah_e */
			$foreachkaffahe = $doc->getElementsByTagName('kaffah_e');
			
			/* disini digunakan untuk menampilkan hasil dari kaffahe */
			foreach($foreachkaffahe as $atr){	
				

				if(!empty($templateval[$atr->getAttribute('name')])){

					$value[$atr->getAttribute('name')] = $templateval[$atr->getAttribute('name')];	
					$atrname[$atr->getAttribute('name')] = $atr->getAttribute('name');
					

				}
				else{					
					$value[$atr->getAttribute('name')] = $atr->getAttribute('value');	
					$atrname[$atr->getAttribute('name')] = $atr->getAttribute('name');

				}
				
			}
			
			/* replace kode dengan text yang ada dalam database */
			
			$value[$atr->getAttribute('name')] = stripslashes(str_replace('&quot;','"',$value[$atr->getAttribute('name')]));

			//
			
		}			
		
		/* yang sebelah sini untuk menghapus slider jika ada isinya di module */
		for($x=0;$x<count($slider);$x++){
			if($isexist[$x] == true){
				$value[$slider[$x]] = '';
			}
		}		

		$screenwithtag = array_unique($screenwithtag);

		$display = str_replace($screenwithtag, @$value, $display);	

		$display = str_replace(array('[[',']]'),array('<','>'), $display);
	
		/* display ini digunakan untuk menampilkan isi */
		return $display;
	}
	
	function kaffahModule($pattern,$display){
		$_this =& get_instance();
		$doc = new DOMDocument();
		$screen = NULL;
		$show = '';
		$moduledisplay = array();
		$whattodisplay = NULL;
		$loop = 0;

		/* atribut domain yang sedang di akses */
		$domainatr = $_this->main->allAtr ;

		/* ini digunakan untuk memparsing module */
		$moduleRegex = preg_match_all($pattern,$display,$result);
		$screenwithtag = $result[0];
		$screenwithnotag = $result[1];


		/* is exist module */
		$isExistModuleSetting = $_this->Site_model->is_modulexist(
					array(
						'option_name' => 'module_setting',
						'blog_id' => $domainatr->domain_id
						), 
					'kp_options');
 
		$listmodule = unserialize(base64_decode($isExistModuleSetting['option_value']));

		// print_r($listmodule);	

		/* looping screen with tag */
		for($x=0;$x<count($screenwithtag);$x++){

			// bypassing error of domdocument 
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);

			$moduleloop = $doc->getElementsByTagName('kaffah_module');

			/* bagian ini digunakan untuk mengambil nilai dari setiap meta tag untuk kaffah module */
			foreach ($moduleloop as $tag) {
				// ambil template tag lalu replace saja ...
		       	$atributid = $tag->getAttribute('id');
		       	// proses replace tag dengan atribut
			}				


			/* cek yang hanya dalam database itu module bagian apa saja */
			if(!empty($listmodule[$atributid])){
				

				/* lakukan pengulangan untuk mendapatkan nama module */
				foreach($listmodule[$atributid] as $row){
					
					/* jika didalamnya terdapat module */
					if($row['module']){



						/* dibagian sini nanti disediakan untuk sortir module */
						$modulename = $row['module'];

						/* yang ini digunakan untuk mengeksekusi fungsi sesuai dengan nama module */																

						$moduledisplay[$row['sort']] = call_user_func_array($modulename, 
								array( 
									@array('judul' => $row['judul'], 'deskripsi' => $row['deskripsi']
									, 'atribut' => $row['atribut']
									)
								)
						);

						
							
					}

					else{

					}
					
					
				}			
				
				ksort($moduledisplay);

				foreach ($moduledisplay as $row) {
					$whattodisplay .= $row;
				}

				/* timpa antara template dengan sesuatu yang mantap */
				$display = str_replace($screenwithtag[$x],$whattodisplay,$display);

				/* bagian ini digunakan agar module 
				dari yang sebelumnya tidak ikut-ikutan 
				untuk masuk ke modul yang lainnnya */				
				
				$whattodisplay = NULL;
				$moduledisplay = NULL;

	       	}

	       	else{
	       		if(strpos($screenwithnotag[$x],'slider')){
	       			$display = str_replace($screenwithtag[$x],'',$display);	       			
	       		}
	       		else{	       			
	       			$display = str_replace($screenwithtag[$x],$screenwithnotag[$x],$display);	       		
	       		}	       		
	       	}

	       	$z=0;
		}


		$search = array( '#<\s*?kaffah_module\b[^>]*>(.*?)</kaffah_module\b[^>]*>#s');
		$replace = array('');
		
		/* replacing pattern */
		$display = preg_replace($search,$replace,$display);		

		return $display;
	}

	function kaffahForEach($pattern,$wrap,$display){
		/* $unfix = str_replace($array_search,$array_replace,$display);
		   paging dan lain-lain akan di letakkan di sini */
		$_this =& get_instance();
		$_this->load->model('Post_model');
		$_this->load->model('Site_model');
		$domainatr = $_this->main->allAtr;

		$doc = new DOMDocument();
		$screen = NULL;
		$show = '';
		
		$mustbee = NULL;
		$_this->main->tempforeach+=1;
		
		/* ambil khusus dibagian looping saja */
		$foreachRegex = preg_match_all($pattern,$display,$forresult);
		$screenwithtag = $forresult[0];
		$screen = $forresult[1];




		/* pengulangan untuk mengambil berapa banyak foreach loop yang ada di home */
		for($x=0;$x<count($screenwithtag);$x++){
			/* bypassing error of domdocument */
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);								
			$showx[$x] = NULL;

			

			$kaffahcat = $doc->getElementsByTagName('kaffah_post_image');


			foreach($kaffahcat as $atr){	
				$height = $atr->getAttribute('height');
				$width = $atr->getAttribute('width');	

			}	


			/* mulai parsing untuk setiap foreach looping */
			$foreachloop = $doc->getElementsByTagName('kaffah_foreach_loop');			
			

			/* pengulangan untuk mendapatkan atribut-atribut dari looping terkait */
			foreach ($foreachloop as $tag) {			
		       $atributtype = $tag->getAttribute('type');				              		       
		       $atributcat = $tag->getAttribute('category');
		       $atributlimit = $tag->getAttribute('limit');
		       $atributoffset = $tag->getAttribute('offset');
			}						
			

			/* ambil atribut untuk post_content */
			$postcontent = $doc->getElementsByTagName('kaffah_post_content');
			foreach($postcontent as $atr){
				$atributintrotext = $atr->getAttribute('introtext');
			}
			
			/* ambil atribut untuk post_content */
			$postcontent = $doc->getElementsByTagName('kaffah_post_metatime');
			foreach($postcontent as $atr){
				$atributTimeString[] = $atr->getAttribute('string');
			}			
			
			/* ketika yang di akses adalah atribut post */
			if($atributtype == 'post'){
				
				// echo $width.$height.'<br />';

				// echo $atributtype;

				if(!empty($atributcat)){
					/* media and content filter */
					$mediafilter = 'f';
					$contentfilter = $atributcat;									
				}
				
				/* kecuali daripada itu */
				else{
					
					/* media and content filter */
					if($_this->uri->segment(1) == 'artikel'){
						$mediafilter = 'f';
						if($_this->uri->segment(2) != 'no'){
							$contentfilter = $_this->uri->segment(2);	
						}
						else{
							$contentfilter = NULL;
						}
						
					}

					else{						
						$mediafilter = NULL;
						$contentfilter = NULL;
					}				

				}	

				$replacepost = $_this->Post_model->get_post('post','publish',$domainatr->domain_id, get_limit_post($atributtype,$atributlimit),get_offset($atributoffset),$mediafilter,$contentfilter);									
								

				if(!empty($replacepost)){
					$_this->load->helper('inflector');
					$this->title = humanize($contentfilter);							

					/* replace foreachloop inside */
					foreach($replacepost as $record){
						/* for category post */						
						$this->datetemp = $record['post_date'];
						//					<kaffah_post_metatime string="d-M-Y"/>
						$getTimePattern = '#<kaffah_post_metatime\sstring="([^>]*)"/>#s';						
						/* array to replace */
						$search = array(
										'#<\s*?kaffah_post_title\b[^>]*/>#s',
										'#<\s*?kaffah_post_content\b[^>]*/>#s',
										'#<\s*?kaffah_post_permalink\b[^>]*/>#s',
										'#<\s*?kaffah_post_image\b[^>]*/>#s',
										'#<\s*?kaffah_post_metaauthor\b[^>]*/>#s',
										'#<\s*?kaffah_post_commentcount\b[^>]*/>#s',
										'#<\s*?kaffah_post_viewcount\b[^>]*/>#s',
										'#<\s*?kaffah_post_category\b[^>]*/>#s',
										'#<\s*?kaffah_post_category_permalink\b[^>]*/>#s',
										);
						
						/* replace array */
						$replace = array(
										$record['post_title'],
										character_limiter(strip_tags($record['post_content']), @$atributintrotext),
										$this->kaffahPostPermalink($record,$domainatr),
										$this->kaffahPostImage($record,$domainatr,$atributtype,$display,'#<\s*?kaffah_post_image\b[^>]*/>#s',FALSE,@$width,@$height),
										$record['name'],
										$record['comment_count'],
										$record['post_counter'],
										$this->kaffahPostCategory($record,$domainatr),
										$this->kaffahPostCategoryPermalink($record,$domainatr),
										);


						/* replacing pattern */
						@$show[$x] .= preg_replace($search,$replace,@$screen[$x]);
						$show[$x] = preg_replace_callback($getTimePattern,'kaffahPostMetaTime',$show[$x]);

						// echo $show[$x];
					}	

					$showx[$x] = $show[$x];
					
				}				
			}

			/* ketika atributnya adalah post */
			else if($atributtype == 'product'){
				
				/* jika atribut kategori tidak kosong */
				if(!empty($atributcat)){
					/* media and content filter */
					$mediafilter = 'f';
					$contentfilter = $atributcat;

					
				}
				
				/* kecuali daripada itu */
				else{
					
					/* media and content filter */
					if($_this->uri->segment(1) == 'produk'){
						$mediafilter = 'f';
						$contentfilter = $_this->uri->segment(2);
					}

					else{						
						$mediafilter = NULL;
						$contentfilter = NULL;
					}


				}					

				$replacepost = $_this->Post_model->get_post($atributtype,'publish',$domainatr->domain_id, get_limit_post($atributtype,$atributlimit),get_offset($atributoffset),$mediafilter,$contentfilter);
				// echo $mediafilter.$contentfilter;


				if(!empty($replacepost)){
					$_this->load->helper('inflector');
					$this->title = humanize($contentfilter);



					/* replace foreachloop inside */
					foreach($replacepost as $record){
						/* for category post */						
						$this->datetemp = $record['post_date'];
						//					<kaffah_post_metatime string="d-M-Y"/>
						$getTimePattern = '#<kaffah_post_metatime\sstring="([^>]*)"/>#s';
						
						/* array to replace */
						$search = array(
										'#<\s*?kaffah_post_title\b[^>]*/>#s',
										'#<\s*?kaffah_post_content\b[^>]*/>#s',
										'#<\s*?kaffah_post_permalink\b[^>]*/>#s',
										'#<\s*?kaffah_post_image\b[^>]*/>#s',
										'#<\s*?kaffah_post_metaauthor\b[^>]*/>#s',
										'#<\s*?kaffah_post_commentcount\b[^>]*/>#s',
										'#<\s*?kaffah_post_price\b[^>]*/>#s',
										'#<\s*?kaffah_post_stock\b[^>]*/>#s',
										'#<\s*?kaffah_post_code\b[^>]*/>#s',
										'#<\s*?kaffah_product_price\b[^>]*/>#s',
										'#<\s*?kaffah_product_stock\b[^>]*/>#s',
										'#<\s*?kaffah_product_code\b[^>]*/>#s',										
										'#<\s*?kaffah_post_category\b[^>]*/>#s',
										'#<\s*?kaffah_post_category_permalink\b[^>]*/>#s',
										);


						
						/* replace array */
						$replace = array(
										$record['post_title'],
										character_limiter(strip_tags($record['post_content']), @$atributintrotext),
										$this->kaffahPostPermalink($record,$domainatr,$atributtype),
										$this->kaffahPostImage($record,$domainatr,$atributtype,$display,'#<\s*?kaffah_post_image\b[^>]*/>#s',FALSE,$width,$height),
										$record['name'],
										$record['comment_count'],
										'Rp '.@number_format($record['post_price'],0,",","."),	
										$record['post_stock'],
										$record['post_code'],
										'Rp '.@number_format($record['post_price'],0,",","."),	
										$record['post_stock'],
										$record['post_code'],										
										$this->kaffahPostCategory($record,$domainatr),
										$this->kaffahPostCategoryPermalink($record,$domainatr,$atributtype),
										);
					
						

					/* replacing pattern */
						@$show[$x] .= preg_replace($search,$replace,@$screen[$x]);
						$show[$x] = preg_replace_callback($getTimePattern,'kaffahPostMetaTime',$show[$x]);
					}	

					$showx[$x] = $show[$x];
				}
								
				/* lakukan replace antara pattern looping dengan yang ada dalam database */
				// $mustbee = str_replace($screenwithtag[$x],$show,$display);						
			}

			else if($atributtype == 'search'){				

				/* jika atribut kategori tidak kosong */
				if(!empty($atributcat)){
										
				}
				
				/* kecuali daripada itu */
				else{
					
					/* media and content filter */
					if($_this->uri->segment(1) == 'pencarian'){
						$_this->load->helper('inflector');
						$mediafilter = 's';
						$contentfilter = humanize($_this->uri->segment(2));
					}

					else{						
						$mediafilter = NULL;
						$contentfilter = NULL;
					}					


					$replacepost = $_this->Post_model->get_post($atributtype,'publish',$domainatr->domain_id, get_limit_post(),get_offset(),$mediafilter,$contentfilter);

					if(!empty($replacepost)){	
										
						$_this->load->helper('inflector');											
						$this->title = humanize($contentfilter);													

						/* replace foreachloop inside */
						foreach($replacepost as $record){
							/* for category post */						
							$this->datetemp = $record['post_date'];
							/* <kaffah_post_metatime string="d-M-Y"/> */
							$getTimePattern = '#<kaffah_post_metatime\sstring="([^>]*)"/>#s';
							
							/* array to replace */
							$search = array(
											'#<\s*?kaffah_post_title\b[^>]*/>#s',
											'#<\s*?kaffah_post_content\b[^>]*/>#s',
											'#<\s*?kaffah_post_permalink\b[^>]*/>#s',
											'#<\s*?kaffah_post_image\b[^>]*/>#s',
											'#<\s*?kaffah_post_metaauthor\b[^>]*/>#s',
											'#<\s*?kaffah_post_commentcount\b[^>]*/>#s',
											'#<\s*?kaffah_post_category\b[^>]*/>#s',
											'#<\s*?kaffah_post_category_permalink\b[^>]*/>#s',
											);
							
							/* replace array */
							$replace = array(
											$record['post_title'],
											character_limiter(strip_tags($record['post_content']), $atributintrotext),
											$this->kaffahPostPermalink($record,$domainatr),
											$this->kaffahPostImage($record,$domainatr,$atributtype,$display,'#<\s*?kaffah_post_image\b[^>]*/>#s',FALSE,$width,$height),
											$record['name'],
											$record['comment_count'],
											$this->kaffahPostCategory($record,$domainatr),
											$this->kaffahPostCategoryPermalink($record,$domainatr),
											);
							
								/* replacing pattern */							

							@$show[$x] .= preg_replace($search,$replace,$screen[$x]);
							$show[$x] = preg_replace_callback($getTimePattern,'kaffahPostMetaTime',$show[$x]);
						}	

						$showx[$x] = $show[$x];
					}
					
					/* lakukan replace antara pattern looping dengan yang ada dalam database */
					@$mustbee = str_replace($screenwithtag[$x],$show,$display);


				}	
			}					

		}

		for($x=0;$x<count($screenwithtag);$x++){
			 $display = str_replace($screenwithtag[$x],$showx[$x],$display);
			
		}
	
		return @$display;
	}	
	
	function kaffahParsing($arraySearch,$arrayReplace,$template){
		return str_replace($arraySearch, $arrayReplace, $template);
	}

	/* <kaffah:head/> */
	function kaffahHead(){
		global $SConfig;
		$_this =& get_instance();
		$domainatr = $_this->main->allAtr ;

		$display = '<link rel="stylesheet" href="http://www.kaffah.biz/assets/css/global.css" />';

		/* get comment setting */
		$all_ts_array = $_this->template->templatesetting;
		$template_setting = unserialize(base64_decode(@$all_ts_array['option_value']));


		$this->metakeyword = $template_setting['seo_setting']['home_keyword'];
		$this->metadescription = $template_setting['seo_setting']['home_description'];

		$display .= meta_keyword($this->metakeyword);
		$display .= meta_description($this->metadescription);

		/* url canonical */
		if($template_setting['seo_setting']['url_canonical'] == 'yes'){
			$display .= '<link rel="canonical" href="'.current_url().'" />';
		}

		/* google meta */
		if(!empty($template_setting['seo_setting']['webmaster_meta'])){
			$display .= google_webmaster($template_setting['seo_setting']['webmaster_meta']);
		}

		if(!empty($template_setting['seo_setting']['google_analytic'])){
			$display .= g_analytics($template_setting['seo_setting']['google_analytic'], $domainatr->domain_name);
		}		
		
		if(!empty($template_setting['seo_setting']['alexa_meta'])){
			$display .= alexa_meta($template_setting['seo_setting']['alexa_meta']);
		}


		$display .= "<div id=\"fb-root\"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = \"//connect.facebook.net/en_US/all.js#xfbml=1\";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>";

		return $display;
	}

	/* <kaffah:title/> */
	function kaffahTitle(){
		/* lihat ilmuwebsite struktur title */

		global $SConfig;
		$_this =& get_instance();
		
		/* 	ketika yang di akses adalah halaman depan atau home maka title 
			yang harusnya keluar adalah sebagai berikut : */
		if($_this->main->isHome == TRUE){
			if($_this->main->allAtr->domain_name == $SConfig->domain){
				return $_this->main->forTitle.' | '.$_this->main->allAtr->domain_title;
			}

			else{
				/* get comment setting */
				$all_ts_array = $_this->template->templatesetting;
				$template_setting = unserialize(base64_decode(@$all_ts_array['option_value']));				

				if(!empty($template_setting['seo_setting']['home_title'])){
					return $template_setting['seo_setting']['home_title'];
				}
				else{
					return $_this->main->allAtr->domain_title;
				}											
			}
		}
		
		else if($_this->main->isSingle == TRUE){
			return $this->title.' | '.$_this->main->allAtr->domain_title;
		}

		else if($_this->main->isPage == TRUE){
			
			return $this->title.' | '.$_this->main->allAtr->domain_title;	
			
			
		}

		else if($_this->main->isCategory == TRUE){
			if($this->title){
				return $this->title.' | '.$_this->main->allAtr->domain_title;	
			}
			else{
				return 'Artikel | '.$_this->main->allAtr->domain_title;
			}
			
		}

		else if($_this->main->isCategoryProduct == TRUE){
			return $this->title.' | '.$_this->main->allAtr->domain_title;
		}

		else if($_this->main->isSingleProduct == TRUE){
			return $this->title.' | '.$_this->main->allAtr->domain_title;
		}

		else if($_this->main->isSearch == TRUE){
			return 'Hasil pencarian '. $this->title .' | '.$_this->main->allAtr->domain_title;
		}		
	}
	
	function kaffahBreadCrumb($breadcrumbpattern,$display){
		/* lihat ilmuwebsite struktur title */

		global $SConfig;
		$_this =& get_instance();
		
		/* 	ketika yang di akses adalah halaman depan atau home maka title 
			yang harusnya keluar adalah sebagai berikut : */
		if($_this->main->isHome == TRUE){
			$breadcrumb =  $this->title;
		}
		
		else if($_this->main->isSingle == TRUE){
			$breadcrumb =  ' / '.$this->title;
		}

		else if($_this->main->isPage == TRUE){
			$breadcrumb = ' / '.$this->title;
		}

		else if($_this->main->isCategory == TRUE){
			$breadcrumb = ' / '.$this->title;
		}

		else if($_this->main->isCategoryProduct == TRUE){
			$breadcrumb = ' / '.$this->title;
		}

		else if($_this->main->isSingleProduct == TRUE){
			$breadcrumb =  ' / '.$this->title;
		}

		else if($_this->main->isSearch == TRUE){
			$breadcrumb =  ' / Pencarian / '.$this->title;
		}		


		$displayfix = preg_replace($breadcrumbpattern,$breadcrumb,$display);
		return $displayfix;
	}

	/* <kaffah:blog.title/> */
	function kaffahBlogTitle(){
		global $SConfig;
		$_this =& get_instance();
		
		/* 	ketika yang di akses adalah halaman depan atau home maka title 
			yang harusnya keluar adalah sebagai berikut : */			

		if($_this->main->isHome == TRUE){
			return $_this->main->allAtr->domain_title;

		}
		
		else if($_this->main->isSingle == TRUE){
			return $_this->main->allAtr->domain_title;
		}

		else if($_this->main->isPage == TRUE){
			return $_this->main->allAtr->domain_title;
		}

		else if($_this->main->isCategory == TRUE){
			return $_this->main->allAtr->domain_title;
		}

		else if($_this->main->isCategoryProduct == TRUE){
			return $_this->main->allAtr->domain_title;
		}	

		else if($_this->main->isSingleProduct == TRUE){
			return $_this->main->allAtr->domain_title;
		}	

		else if($_this->main->isSearch == TRUE){
			return $_this->main->allAtr->domain_title;
		}			
	}
	
	/* kaffah_blog_description */
	function kaffahBlogDesc(){
		global $SConfig;
		$_this =& get_instance();
		
		/* 	ketika yang di akses adalah halaman depan atau home maka title 
			yang harusnya keluar adalah sebagai berikut : */
		if($_this->main->isHome == TRUE){
			return $_this->main->allAtr->domain_desc;
		}
		
		else if($_this->main->isSingle == TRUE){
			return $_this->main->allAtr->domain_desc;
		}

		else if($_this->main->isPage == TRUE){
			return $_this->main->allAtr->domain_desc;
		}

		else if($_this->main->isCategory == TRUE){
			return $_this->main->allAtr->domain_desc;
		}	

		else if($_this->main->isCategoryProduct == TRUE){
			return $_this->main->allAtr->domain_desc;
		}

		else if($_this->main->isSingleProduct == TRUE){
			return $_this->main->allAtr->domain_desc;
		}

		else if($_this->main->isSearch == TRUE){
			return $_this->main->allAtr->domain_desc;
		}		
	}

	/* <kaffah:blog.homepageUrl/> */
	function kaffahBlogHomePageURL(){
		global $SConfig;
		$_this =& get_instance();
		$domainatr = $_this->main->allAtr ;
		return base_url();
	}
	
	function kaffahPostPermalink($record,$domainatr,$type=NULL){
		global $SConfig;
		$_this =& get_instance();
		$_this->load->library('custom');		

		if(($_this->custom->temp_page == NULL) && !array_key_exists($_this->custom->temp_page, $_this->custom->list_page)){
			/* kategori post */		

			$category = explode(',',$record['post_category']);
			
			if(count($category) > 1){
				$category = $category[0];
			}
			else{
				$category = $record['post_category'];	
			}

			/* jika yang di akses adalah kategori produk */
			if ($record['post_type'] == 'product'){
				return base_url().'produk/'.$category.'/'.$record['post_name'];	
			}
			else if($record['post_type'] == 'page'){
				return base_url().'halaman/'.$record['post_name'];
			}
			else{
				return base_url().'artikel/'.$category.'/'.$record['post_name'];
			}			
		}

		else{
			return 'http://'.$domainatr->domain_name;
		}		
	}
	
	function kaffahPostCategory($record,$domainatr){
		global $SConfig;
		$_this =& get_instance();
		
		$_this->load->library('custom');		

		if(($_this->custom->temp_page == NULL) && !array_key_exists($_this->custom->temp_page, $_this->custom->list_page)){

			$_this->load->helper('inflector');
			
			/* kategori post */
			$category = explode(',',$record['post_category']);
			
			if(count($category) > 1){
				return humanize($category[0]);
			}
			else{
				return humanize($record['post_category']);	
			}				
		}
	}

	function kaffahPostCategoryPermalink($record,$domainatr,$atributtype=NULL){
		global $SConfig;
		$_this =& get_instance();

		$_this->load->library('custom');		

		if(($_this->custom->temp_page == NULL) && !array_key_exists($_this->custom->temp_page, $_this->custom->list_page)){	
			/* kategori post */
			$category = explode(',',$record['post_category']);
			
			if(count($category) > 1){
				$category = $category[0];
			}
			else{
				$category = $record['post_category'];	
			}	

			if($atributtype == 'product'){
				return base_url().'produk/'.$category;
			}	
			else{
				return base_url().'artikel/'.$category;	
			}				
		}
	}
		
	function kaffahPostImage($record,$domainatr,$atributtype=NULL,$display=NULL,$pattern=NULL,$inside=FALSE,$w=NULL,$h=NULL){
		$_this =& get_instance();

		$_this->load->library('custom');						
			

		if($w==NULL && $h==NULL){
			$foreachRegex = preg_match_all($pattern,$display,$forresult);
			@$screenwithtag = $forresult[0][0];
			$screen = @$forresult[1];

			$doc = new DOMDocument();
			libxml_use_internal_errors(true);
			@$doc->loadHTML($display);
			libxml_use_internal_errors(false);


			/* pengulangan untuk mengambil berapa banyak foreach loop yang ada di home */
			for($x=0;$x<count($screenwithtag);$x++){
				/* bypassing error of domdocument */
				libxml_use_internal_errors(true);
				$doc->loadHTML($screenwithtag[$x]);
				libxml_use_internal_errors(false);								
				$showx[$x] = NULL;
				

				$kaffahcat = $doc->getElementsByTagName('kaffah_post_image');


				foreach($kaffahcat as $atr){	
					$w = $atr->getAttribute('height');
					$h = $atr->getAttribute('width');	
					
				}	
			}		
		}

		if(($_this->custom->temp_page == NULL) && !array_key_exists($_this->custom->temp_page, $_this->custom->list_page)){
			
			$_this->load->model('Global_model');
			$_this->load->helper('inflector');			

			$width = $w;
			$height = $h;
			


			if(($inside == FALSE) && ($_this->main->isSingle == TRUE || $_this->main->isPage == TRUE || $_this->main->isSingleProduct == TRUE )){
				

				if($atributtype=='product'){		

					$val = '<img src="'. get_thumb($record['post_image'],@$width,@$height,1).'" alt="'.$record['post_title'].'" />';

				}
				else{

					$val = '<img src="'. get_thumb(img_capture($record['post_content']),@$width,@$height,1).'" alt="'.$record['post_title'].'" />';	

				
				}	
			}
			else{
				if($atributtype=='product'){		

					$display = '<img src="'. get_thumb($record['post_image'],@$width,@$height,1).'" alt="'.$record['post_title'].'" />';
				}
				else{

					if($record['post_type'] == 'product'){
						$display = '<img src="'. get_thumb($record['post_image'],@$width,@$height,1).'" alt="'.$record['post_title'].'" />';
					}
					else{
						$display =  '<img src="'. get_thumb(img_capture($record['post_content']),@$width,@$height,1).'" alt="'.$record['post_title'].'" />';
					}

						
					
				}	
			}				

			@$display = str_replace($screenwithtag,$val,$display);	



			/*$doc = new DOMDocument();
			libxml_use_internal_errors(true);
			@$doc->loadHTML($display);
			libxml_use_internal_errors(false);

			$domainatr = $_this->main->allAtr ;

			
			$kaffaheRegex = preg_match_all($pattern,$display,$forresult);
			$screenwithtag = $forresult[0];			

			
			for($x=0;$x<count($screenwithtag);$x++){				

				
				libxml_use_internal_errors(true);
				$doc->loadHTML($screenwithtag[$x]);
				libxml_use_internal_errors(false);
				
				
				$kaffahcat = $doc->getElementsByTagName('kaffah_post_image');
				
				foreach($kaffahcat as $atr){	
					$height = $atr->getAttribute('height');
					$width = $atr->getAttribute('width');					
				}	

				if(($inside == FALSE) && ($_this->main->isSingle == TRUE || $_this->main->isPage == TRUE || $_this->main->isSingleProduct == TRUE)){
					if($atributtype=='product'){		

						$val[$x] = '<img src="'. get_thumb($record['post_image'],@$width,@$height,1).'" alt="image post" height="'.@$height.'" width="'.@$width.'"/>';
					}
					else{

						$val[$x] = '<img src="'. get_thumb(img_capture($record['post_content']),@$width,@$height,1).'" alt="image post" height="'.@$height.'" width="'.@$width.'"/>';	
					
					}	
				}
				else{
					if($atributtype=='product'){		

						$display = '<img src="'. get_thumb($record['post_image'],@$width,@$height,1).'" alt="image post" height="'.@$height.'" width="'.@$width.'"/>';
					}
					else{

						$display =  '<img src="'. get_thumb(img_capture($record['post_content']),@$width,@$height,1).'" alt="image post" height="'.@$height.'" width="'.@$width.'"/>';	
					
					}	
				}				

				@$display = str_replace($screenwithtag,$val,$display);	
			}
			*/

		}	



		else{
			
		}
		
		return $display;			
	}
	


	function kaffahUserMenu(){
		global $SConfig;
		$_this =& get_instance();

		if($_this->session->userdata('logged_in') == TRUE){
			$display = '<li><a href="">User Menu</a>';
			$display .= '<ul class="user_menu">
						<li><a href="'.base_url().'user/dashboard">Dashboard</a></li>
						<li><a href="'.base_url().'user/transaksi">Daftar Transaksi</a></li>
						<li><a href="'.base_url().'user/setting">Setting Akun</a></li>';

			if($_this->session->userdata('reseller')){				
				$display .= '<li>
				<a id="loginlink" href="http://store.kaffahbiz.co.id/req/login">Masuk Web Reseller</a>
				<form id="formloginlink" method="POST" action="http://www.kaffah.biz/req/login"><input type="hidden" name="email" value="'.$_this->session->userdata('email').'" />
				<input type="hidden" name="password" value="'.$_this->session->userdata('password').'" />				
				</form>
				</li>';
			}						
						
			$display .=	'<li><a href="'.base_url().'user/logout">Log Out!</a></li>
						</ul>';
			$display .= '</li>';
		}
		else{
			$display = '<li><a href="'.base_url().'user/register"><kaffah_e name="register" type="text" value="Register" /></a></li>';
			$display .= '<li><a href="'.base_url().'user/login"><kaffah_e name="login_menu" type="text" value="Login Member" /></a></li>';			
		}
		return $display;
	}
	
	/* <kaffah:post.CategoryMenuList/> */
	function kaffahPostCatMenuList($display=NULL){
		global $SConfig;
		$_this =& get_instance();
		$domainatr = $_this->main->allAtr;
		// kaffah_post_categorymenulist
		$typemenu = NULL;


		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		@$doc->loadHTML($display);
		libxml_use_internal_errors(false);

		$domainatr = $_this->main->allAtr ;

		
		$kaffaheRegex = preg_match_all('#<\s*?kaffah_post_categorymenulist\b[^>]*/>#s',$display,$forresult);
		$screenwithtag = $forresult[0];		

		for($x=0;$x<count($screenwithtag);$x++){							
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);
			
			
			$kaffahcat = $doc->getElementsByTagName('kaffah_post_categorymenulist');
			
			if(!empty($kaffahcat)){
				foreach($kaffahcat as $atr){	
					$typemenu = $atr->getAttribute('type');									
				}	

			}	
			else{
				$typemenu = NULL;				
			}	
		}




		/* kaffah_post_category_permalink */
		$_this->load->model('Post_model');
		$postcategory = reListCat($_this->Post_model->get_all_cat('category_article',NULL,$domainatr->domain_id));

		// echo '<pre>'.print_r($postcategory).'</pre>';


		/* jadikan hirarki di setiap kategori */

		/* jadikan hirarki di setiap kategori */
		if($typemenu == 'li'){
			return menuCategoryLi($postcategory, 'post',NULL,$typemenu);
		}
		else{
			return menuCategory($postcategory,'post',NULL,$typemenu);		
		}

				
	}

	/* <kaffah:page.MenuList/> */
	function kaffahPageMenuList($display=NULL){
		global $SConfig;
		$_this =& get_instance();
		$_this->load->model('Post_model');
		$typemenu = NULL;


		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		@$doc->loadHTML($display);
		libxml_use_internal_errors(false);

		$domainatr = $_this->main->allAtr ;

		
		$kaffaheRegex = preg_match_all('#<\s*?kaffah_page_menulist\b[^>]*/>#s',$display,$forresult);
		$screenwithtag = $forresult[0];		

		for($x=0;$x<count($screenwithtag);$x++){							
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);
			
			
			$kaffahcat = $doc->getElementsByTagName('kaffah_page_menulist');
			
			if(!empty($kaffahcat)){
				foreach($kaffahcat as $atr){	
					$typemenu = $atr->getAttribute('type');								
				}	

			}	
			else{
				$typemenu = NULL;
			}	
		}

		
		/* get all pages from current domain */
		$pagelist = $_this->Post_model->get_post('page', 'publish', $domainatr->domain_id);
		$pagelist = reListCatPage($pagelist);
		return recursiveFrontPage($pagelist,$typemenu);
	}	

	/* <kaffah:post.CategoryMenuList/> */
	function kaffahProductCatMenuList($display=NULL){
		global $SConfig;
		$_this =& get_instance();
		$domainatr = $_this->main->allAtr;
		$typemenu = NULL;


		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		@$doc->loadHTML($display);
		libxml_use_internal_errors(false);

		$domainatr = $_this->main->allAtr ;

		
		$kaffaheRegex = preg_match_all('#<\s*?kaffah_product_categorymenulist\b[^>]*/>#s',$display,$forresult);
		$screenwithtag = $forresult[0];		

		for($x=0;$x<count($screenwithtag);$x++){							
			libxml_use_internal_errors(true);
			$doc->loadHTML($screenwithtag[$x]);
			libxml_use_internal_errors(false);
			
			
			$kaffahcat = $doc->getElementsByTagName('kaffah_product_categorymenulist');
			
			if(!empty($kaffahcat)){
				foreach($kaffahcat as $atr){	
					$typemenu = $atr->getAttribute('type');				
				}	

			}	
			else{
				$typemenu = NULL;
			}	
		}


		/* kaffah_post_category_permalink */
		$_this->load->model('Post_model');
		$postcategory = reListCat($_this->Post_model->get_all_cat('category_product',NULL,$domainatr->domain_id));

		// echo '<pre>'.print_r($postcategory).'</pre>';

		/* jadikan hirarki di setiap kategori */

		// print_r($postcategory);

		if($typemenu == 'li'){
			return substr(menuCategoryLi($postcategory, 'product',NULL,$typemenu),40,-5);
		}
		else{
			return menuCategory($postcategory, 'product');			
		}
		
	}	
	
	/* <kaffah:foot/> */
	function kaffahFoot(){
		global $SConfig;
		$_this =& get_instance();
		/* JS Library */

		$display = '<script src="http://www.kaffah.biz/assets/js/global.js"></script>';
		return $display;		
	}	

	function templateParsing(){
		/* 	
		CUSTOM TEMPLATE ADALAH BAGIAN TERPENTING DARI TEMPLATE CUSTOM 
		CARA CUSTOM TEMPLATE ADALAH : 
		1. mengambil text template custom dari database
		2. LAKUKAN PARSING!
		
		
		// KEBUTUHAN PARSING 

		$array_find_ 	= array(
									$l=get_k_loop($custom_template),
									'<kaffah:blog.title/>',
									'<kaffah:loop>',
									'</kaffah:loop>'
								);
							
		$array_replace_ = array(
									k_loop($l),
									k_title(),
									'',
									''
								);
		
		// START PARSING 
		$parsing_template = str_replace($array_find_, $array_replace_, $custom_template);
		*/
	}	
	
	
}