<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "front";
$route['404_override'] = '';

/* yang ini digunakan untuk halaman admin */
$route['login'] = "req/login";
$route['dir'] = "dir";
$route['dir/([a-zA-Z0-9-_.]+)'] = "dir/directory/$1";
$route['dir/([a-zA-Z0-9-_.]+)/id/([a-zA-Z0-9-_.]+)/([a-zA-Z0-9-_.]+)'] = "dir/directory/$1/id/$2";
$route['dir/([a-zA-Z0-9-_.]+)/h'] = "dir/directory/$1/h";
$route['dir/([a-zA-Z0-9-_.]+)/h/([a-zA-Z0-9-_.]+)'] = "dir/directory/$1/h/$2";


$route['forgot_pass'] = "req/forgot_pass";
$route['forgot_pass/([a-zA-Z0-9-_.]+)'] = "req/forgot_pass/$1";
$route['logout'] = "req/logout";
$route['site/member/([a-zA-Z0-9-_.]+)'] = "site/member/$1";



/* yang ini digunakan untuk halaman admin */
$route['req/savetemplate/([a-zA-Z0-9-_.]+)'] = "req/savetemplate/$1";
$route['req/getstatistic/([a-zA-Z0-9-_.]+)'] = "req/getstatistic/$1";
$route['req/getdomain/([a-zA-Z0-9-_.]+)'] = "req/getdomain/$1";
$route['req/getmodule/([a-zA-Z0-9-_.]+)'] = "req/getmodule/$1";
$route['req/modulelist/([a-zA-Z0-9-_.]+)'] = "req/modulelist/$1";
$route['req/moduledetail/([a-zA-Z0-9-_.]+)'] = "req/moduledetail/$1";
$route['req/addmodule/([a-zA-Z0-9-_.]+)'] = "req/addmodule/$1";
$route['req/getactivemodule/([a-zA-Z0-9-_.]+)'] = "req/getactivemodule/$1";
$route['req/delmodule/([a-zA-Z0-9-_.]+)'] = "req/delmodule/$1";
$route['req/editmodule/([a-zA-Z0-9-_.]+)'] = "req/editmodule/$1";
$route['req/sortirmodule/([a-zA-Z0-9-_.]+)'] = "req/sortirmodule/$1";
$route['req/updatemodule/([a-zA-Z0-9-_.]+)'] = "req/updatemodule/$1";
$route['req/gettemplatetag/([a-zA-Z0-9-_.]+)'] = "req/gettemplatetag/$1";
$route['req/savesetting/([a-zA-Z0-9-_.]+)'] = "req/savesetting/$1";
$route['req/getvaluetag/([a-zA-Z0-9-_.]+)'] = "req/getvaluetag/$1";
$route['req/savesettingconfigumum/([a-zA-Z0-9-_.]+)'] = "req/savesettingconfigumum/$1";
$route['req/getsettingconfigumum/([a-zA-Z0-9-_.]+)'] = "req/getsettingconfigumum/$1";
$route['req/savesettingconfigkonten/([a-zA-Z0-9-_.]+)'] = "req/savesettingconfigkonten/$1";
$route['req/getsettingconfigkonten/([a-zA-Z0-9-_.]+)'] = "req/getsettingconfigkonten/$1";
$route['req/savesettingconfigkomentar/([a-zA-Z0-9-_.]+)'] = "req/savesettingconfigkomentar/$1";
$route['req/getsettingconfigkomentar/([a-zA-Z0-9-_.]+)'] = "req/getsettingconfigkomentar/$1";
$route['req/savesettingconfigseo/([a-zA-Z0-9-_.]+)'] = "req/savesettingconfigseo/$1";
$route['req/getsettingconfigseo/([a-zA-Z0-9-_.]+)'] = "req/getsettingconfigseo/$1";
$route['req/getcity/([a-zA-Z0-9-_.]+)'] = "req/getcity/$1";
$route['req/savesettingconfigstore/([a-zA-Z0-9-_.]+)'] = "req/savesettingconfigstore/$1";
$route['req/getsettingconfigstore/([a-zA-Z0-9-_.]+)'] = "req/getsettingconfigstore/$1";
$route['reqpost/editcat/([a-zA-Z0-9-_.]+)'] = "reqpost/editcat/$1";
$route['reqpost/editarticle/([a-zA-Z0-9-_.]+)'] = "reqpost/editarticle/$1";
$route['reqpost/pagingarticle/([a-zA-Z0-9-_.]+)'] = "reqpost/pagingarticle/$1";


/* sebelah sini digunakan untuk setiap post yang di akses lewat public */
$route['artikel'] = "post/kategori";
$route['artikel/no/'] = "post/kategori";
$route['artikel/no/([a-zA-Z0-9-_.]+)'] = "post/kategori/no/$1";
$route['artikel/([a-zA-Z0-9-_.]+)/no'] = "post/kategori/$1";
$route['artikel/([a-zA-Z0-9-_.]+)/([a-zA-Z0-9-_.]+)'] = "post/detail/$1/$2/$3";
$route['artikel/([a-zA-Z0-9-_.]+)'] = "post/kategori/$1";
$route['artikel/([a-zA-Z0-9-_.]+)/no/([a-zA-Z0-9-_.]+)'] = "post/kategori/$1/no/$2";



$route['user/login'] = "page/login";
$route['user/register'] = "page/register";
$route['user/logout'] = "page/logout";
$route['user/dashboard'] = "page/dashboard";
$route['user/transaksi'] = "page/transaksi";
$route['user/setting'] = "page/setting";
$route['user/setting/save'] = "page/setting/save";
$route['user/lupa_password'] = "page/lupa_password";
$route['user/aktivasi/([a-zA-Z0-9-_.]+)'] = "page/aktivasi/$1";
$route['reseller/register'] = "page/register";


$route['produk/beli'] = "cart/beli";
$route['produk/keranjang_belanja'] = "cart/keranjang_belanja";
$route['produk/pemeriksaan_pembelian'] = "cart/pemeriksaan_pembelian";
$route['produk/pembelian_selesai'] = "cart/pembelian_selesai";
$route['produk/pemeriksaan_pembelian/cek'] = "cart/pemeriksaan_pembelian/cek";
$route['produk/([a-zA-Z0-9-_.]+)/no'] = "cart/kategori_produk/$1";
$route['produk/([a-zA-Z0-9-_.]+)/([a-zA-Z0-9-_.]+)'] = "cart/detail_produk/$1/$2/$3";
$route['produk/([a-zA-Z0-9-_.]+)'] = "cart/kategori_produk/$1";
$route['produk/([a-zA-Z0-9-_.]+)/no/([a-zA-Z0-9-_.]+)'] = "cart/kategori_produk/$1/no/$2";


$route['halaman/([a-zA-Z0-9-_.]+)'] = "page/detail/$1";
$route['halaman/([a-zA-Z0-9-_.]+)/([a-zA-Z0-9-_.]+)'] = "page/detail/$1/$2";
$route['komentar/kirim'] = "front/komentar/kirim";
$route['no'] = "front/index";

$route['feed'] = "front/feed";
$route['sitemapxml'] = "front/sitemap";

$route['pencarian/([a-zA-Z0-9-_.]+)'] = "front/search/$1";
$route['pencarian/([a-zA-Z0-9-_.]+)/no'] = "front/search/$1";
$route['pencarian/([a-zA-Z0-9-_.]+)/no/([a-zA-Z0-9-_.]+)'] = "front/search/$1/no/$2";


$route['no/([a-zA-Z0-9-_.]+)'] = "front/index/$1";


/* kaffah reg */
$route['reg'] = "signup/reg";
$route['fitur'] = "signup/fitur";
$route['harga'] = "signup/harga";
$route['demo'] = "signup/demo";


/* End of file routes.php */
/* Location: ./application/config/routes.php */
