<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$config = array(
				/* ini konfigurasi form untuk login */
                'login' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'Username',
                                            'rules' => 'required|trim|valid_email'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'required|trim'
                                         )
                                    )
									
									,
									
                'add_domain' => array(
                                    array(
                                            'field' => 'domain',
                                            'label' => 'domain',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'domaintld',
                                            'label' => 'domaintld',
                                            'rules' => 'required'
                                         ),
                                    
                                    array(
                                            'field' => 'title',
                                            'label' => 'title',
                                            'rules' => 'required'
                                         ),
                                    
                                    array(
                                            'field' => 'desc',
                                            'label' => 'desc',
                                            'rules' => 'required'
                                         )
                                    
									)
									
                                    ,

                    'register' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required|valid_email|callback_check_email'
                                         )                                    
                                    )
                           
                                    ,  

                    'register_reseller' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required|valid_email|callback_check_email'
                                         )                                    
                                    )
                           
                                    ,  



                    'lupa_password' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required'
                                         )                                    
                                    )
                           
                                    ,  



                    'forgot_password' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required'
                                         )                                    
                                    )
                           
                                    ,                                      

                    'forgot_pass' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required|valid_email|callback_check_email'
                                         )                                    
                                    )
                           
                                    ,  

                    'konfirmasi' => array(
                                        array(
                                                'field' => 'nama_lengkap',
                                                'label' => 'Nama Lengkap',
                                                'rules' => 'required'
                                             ), 
                                        array(
                                                'field' => 'email',
                                                'label' => 'Email',
                                                'rules' => 'required'
                                             ),  
                                        array(
                                                'field' => 'no_telepon',
                                                'label' => 'No. Telp',
                                                'rules' => 'required'
                                             ),  
                                        array(
                                                'field' => 'no_order',
                                                'label' => 'No. Order',
                                                'rules' => 'required'
                                             ),  
                                        array(
                                                'field' => 'jumlah_bayar',
                                                'label' => 'Jumlah Bayar',
                                                'rules' => 'required'
                                             ),  
                                        array(
                                                'field' => 'rekening',
                                                'label' => 'Rekening',
                                                'rules' => 'required'
                                             ),  
                                        array(
                                                'field' => 'bank_pengirim',
                                                'label' => 'Bank Pengirim',
                                                'rules' => 'required'
                                             ),  
                                        array(
                                                'field' => 'pemilik_rekening',
                                                'label' => 'Pemilik Rekening',
                                                'rules' => 'required'
                                             )                                   
                                    )
                           
                                    ,                                     


                   'setting' => array(
                                    array(
                                            'field' => 'nama_depan',
                                            'label' => 'Nama Depan',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'nama_belakang',
                                            'label' => 'Nama Belakang',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'alamat',
                                            'label' => 'Alamat',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'provinsi',
                                            'label' => 'Provinsi',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'kota',
                                            'label' => 'kota',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'handphone',
                                            'label' => 'Handphone',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'nama_depan',
                                            'label' => 'Nama Depan',
                                            'rules' => 'required'
                                         )                                                                                                                                                                                                                                              
                                    
                                    )
                                    
                                    ,                                                                      
									
                'transaction' =>  array(
                                    array(
                                        'field'   => 'nama_lengkap', 
                                        'label'   => 'Nama Lengkap', 
                                        'rules'   => 'required'
                                        ),                  
                                    

                                    array(
                                        'field'   => 'email', 
                                        'label'   => 'Email', 
                                        'rules'   => 'required|valid_email'
                                        ),                  

                                    array(
                                        'field'   => 'alamat', 
                                        'label'   => 'Alamat', 
                                        'rules'   => 'required'
                                        ),                  

                                    array(
                                        'field'   => 'provinsi', 
                                        'label'   => 'Provinsi', 
                                        'rules'   => 'required'
                                        ),                  

                                    array(
                                        'field'   => 'provinsi_kota', 
                                        'label'   => 'Kota', 
                                        'rules'   => 'required'
                                        ),                  

                                    array(
                                        'field'   => 'no_handphone', 
                                        'label'   => 'No. HP', 
                                        'rules'   => 'required'
                                        )                              

            
                )																	
									
									 
									
									                        
        );
			   
/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */