<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
         function __construct()
	{
		parent::__construct();
                $this->load->model('mdl_dashboard');                
                $this->load->library('Tutor_auth'); 
	}
	public function index()
	{
            date_default_timezone_set('Asia/Singapore'); 
            $uri_='';
            $content_view='';
            $uri_ = $this->uri->segment(1, '');
//            if ($this->tutor_auth->isTutorHasLogin()){
//                $data['top_menu_ul'] = $this->get_menus(15);//get top horizontal tutor
//            }
//            else{
                $data['top_menu_ul'] = $this->get_menus(1);//get top horizontal menus
            //}
            $data['web_title'] = 'Tuition Guru';//get top horizontal menus
            $data['uri'] = $uri_;
//            echo $uri_;
            if($uri_=='main.tutor'){
                
                redirect('tutor_register','refresh');
                
            }
            elseif($uri_=='register.tutor'){
                $simpan = $this->uri->segment(2, '');
                if ($simpan=='save'){
                    $this->load->library('encrypt');
                    $hash = $this->encrypt->sha1($this->input->post('email').'~'.$this->input->post('name').'~'.$this->input->post('password'));
                    $act_email = base_url('activate.tutor/'.$hash);
                    $pass_enc=$this->encrypt->sha1(trim($this->input->post('password')));
                    $ins_data=array(
                        'email' =>$this->input->post('email'),
                        'passwd' =>$this->input->post('password'),
                        'login_passwd' =>$pass_enc,
                        'name' =>$this->input->post('name'),
                        'nric' =>$this->input->post('nric'),
                        'gender' =>$this->input->post('gender'),
                        'birthday' =>$this->input->post('birthday'),
                        'mobile_number' =>$this->input->post('mobile'),
                        'address' =>$this->input->post('address'),
                        'postal_code' =>$this->input->post('postcode'),
                        'occupation' =>$this->input->post('occupation'),
                        'job_category' =>$this->input->post('job_category'),
                        'email_activation_link' =>$act_email,
                        'activation_code' => $hash,
                        'membership_id' => 1,
                        'status' =>0, //status=0 artinya belum terverifikasi saat awal registrasi                       
                    );
                    if ($this->db->insert('tutor_registers',$ins_data)){
                        $username=$this->input->post('name');
                        $ebody="Dear $username,
            This email has been sent from ". base_url() .".
            You have received this email because this email address
            was used during registration for our tutor account.
            If you did not register to be a tutor, please disregard this
            email. You do not need to unsubscribe or take any further action.

            ------------------------------------------------
            Activation Instructions
            ------------------------------------------------
            Thank you for registering.
            We require that you validate your registration to ensure that
            the email address you entered was correct. This protects against
            unwanted spam and malicious abuse.

            To activate your account, simply click on, or copy and paste the 
            following link:

            $act_email

            Once activated, remember to upload a recent photo for identification.
            Your default status is set to available which means you will receive
            assignments. If you are currently busy, please login and update your
            status to busy.

            If you cannot validate your account, it's possible that the 
            account has been removed.
            If this is the case, please contact support@tuitionguru.sg to rectify
            the problem.

            Thank you for registering!

            Regards,

            Tuition Guru";
                        $eSubject = 'TuitionGuru Tutor Registration';
                        $this->sent_email($username, $this->input->post('email'), $act_email,$eSubject, $ebody);
                        $data['message']='Your registration has been accepted. Please check your email or junk mail if junk filter is active to activate your tutor account.';
                    } 
                    //redirect(base_url('register.tutor'),true);
                    $data['confirmation_head']='Email Activation Sent!';
                    $data['verification_status']='0';
                    $content_view = 'v_verification_form';
                }
                else{
                    $data['job_category']=$this->get_comboBox(15);
                    $data['verification_status']='-1';
                    $content_view = 'v_register_tutor';
                }
                //echo $content_view;
            }            
            elseif($uri_=='activate.tutor'){
                $link_email = $this->uri->segment(2, '');
                $this->db->where('activation_code',$link_email);
                $this->db->where('status','0');
                $query  = $this->db->get('tutor_registers');
                
		if ($query->num_rows() > 0){ //apakah sudah ada
                    //echo 'Your account has been activated! Lets Login using your email account';
                    //redirect(base_url(),true);
                    //$this->db->where('activation_code',$link_email);
                    //$this->db->update('tutor_registers',array('status'=>1));//rubah status menjadi sudah terverifikasi
                    $data['confirmation_head']='Almost complete!';
                    $data['verification_status']='1';
                    $data['message']='Please Login in order to complete verification process';
                    $data['activation_code']=$link_email;
                    $content_view = 'v_verification_form'; 
                    
		}
                else{
                    redirect(base_url(),true);
                }
            }
            elseif($uri_=='tutor.dashboard'){
                    $data['confirmation_head']='Registration tutor is complete!';
                    $data['verification_status']='1';
                    $data['message']='Please Login in order to complete verification process';                    
                    $content_view = 'tutor/v_main_dashboard_tutor';  
                    
           }
           elseif($uri_=='login.tutor'){
                    $data['confirmation_head']='Registration tutor is complete!';
                    $data['verification_status']='1';
                    $data['message']='Please Login in order to complete verification process';                    
                    $content_view = 'tutor/v_main_dashboard_tutor';  
                    
           }
           elseif($uri_=='search.tutor'){                     
               $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
               $lokasi = isset($_GET['lokasi'])?trim($_GET['lokasi']):'';
               
                $data['select_district']  = $this->get_comboBox(12,'id');
                $data['select_level']  = $this->get_level0(10);
                $data['select_preference']  = $this->get_comboBox(11);
                $data['select_preference_dtl']  = $this->get_preference();

               $arLoc=array();
               if ($lokasi==190){
                   $lokasi='%';
               }
               elseif ($lokasi==285||$lokasi==286||$lokasi==235||$lokasi==246||$lokasi==254||$lokasi==266||$lokasi==279){ 
//all east district,central district, northeasth, north,northwest,west,south
                    $this->db->where('id',$lokasi);
                    $t=$this->db->get('menus');
                    if ($t->num_rows >0){
                        $r=$t->row_array();
                        $this->db->where('parent_id',$r['parent_id']);
                        $arLoc0=$this->db->get('menus')->result_array();
                        foreach($arLoc0 as $ar_loc)
                        {                     
                             array_push($arLoc, $ar_loc['id']);
                        }
                    }
               }
               $subject =  $_GET['level'];
               $groupTui =  $_GET['preferensi'];
               $gender =  isset($_GET['gender'])?$_GET['gender']:'';
               $foto_tutor =  isset($_GET['photo'])?$_GET['photo']:'';
               $tutor_age =  isset($_GET['age'])?$_GET['age']:'';
               $category = isset($_GET['category'])?$_GET['category']:'';
               $ar_sub=array();
               if (isset($_GET['subject_det']))
                    $ar_sub = $_GET['subject_det'];
               //echo 'lokasi '. $lokasi.' subject '.$subject.' GRoup '.$groupTui.'<br/>';
               //$subject_id_in ="";
               $subject_id_in =array();
               $exp=array();
               //$x=1;
               //subject 
               
               foreach($ar_sub as $subDtl)
               {                     
//                   if ($x>1){
//                       $subject_id_in .= ",";
//                   }
                   $exp = explode('_', $subDtl);
                //if ($exp[1]=='subject'){
                    //$subject_id_in .= $exp[0].',';
                    array_push($subject_id_in, $exp[0]);
                    //$x++;
                   
               //}               
               }
               //echo 'array '.count($subject_id_in);
               
               //$subject_id_in .= $lokasi;
               
//               array_push($subject_id_in, $lokasi); //tambahkan lokasi
//               if ($groupTui =='G'){
//                   array_push($subject_id_in, 189); //tambahkan preference
//               }
//               else {
//                   array_push($subject_id_in, 188); //tambahkan preference
//               }
                //$this->db->where('status',1);
                //$this->db->where('payment_status','S');
                
                $this->db->distinct();
                $this->db->select('t.*');
                $this->db->from('v_tutors AS t');
                if ($lokasi!='' | $lokasi!='%'){
                    if(strlen($lokasi)>1 ){                       
                        $this->db->join('tui_tutor_details AS td2','t.id=td2.tutor_id','left');                                        
                    }
                }
                if (count($subject_id_in)>0){                    
                $this->db->join('tui_tutor_details AS td','t.id=td.tutor_id','left');
                }
                $this->db->where('t.status',1);
                $this->db->where('t.payment_status','S');
                if ($gender!='' && $gender!='%'){
                        $this->db->where('t.gender',$gender);
                }
                if ($tutor_age!='' && $tutor_age!='0'){
                    if($tutor_age==30){
                        $this->db->where('t.tutor_age >',$tutor_age);
                    }
                    else{
                        $this->db->where('t.tutor_age >=',$tutor_age);
                        $this->db->where('t.tutor_age <=',$tutor_age+4);
                    }
                }
                if ($groupTui=='189'){
                    $this->db->where('t.is_group_tui','A');
                }
                
                if ($foto_tutor =='1'){
                    $this->db->where('t.tutor_image IS NOT NULL',null);
                }
                if ($category !=''){
                    $this->db->where('t.job_category',$category);
                }
                
                if ($lokasi!='' | $lokasi!='%'){
                    if(strlen($lokasi)>1 ){
                        if ($lokasi==285||$lokasi==286||$lokasi==235||$lokasi==246||$lokasi==254||$lokasi==266||$lokasi==279){ 
//all east district,central district, northeasth, north,northwest,west,south
                            $this->db->where_in('td2.cat_id',$arLoc);                 
                        }                        
                        else{
                            $this->db->where('td2.cat_id',$lokasi);
                        }
                        $this->db->where('td2.nilai',1); 
                    }
                }
                if (count($subject_id_in)>0){
                    $this->db->where_in('td.cat_id',$subject_id_in);                 
                    $this->db->where('td.nilai',1);
               }
                $rst=$this->db->get();
                
               $data['rst']=$rst;
               //echo $subject_id_in;
                $this->load->library('pagination');
//                $config['base_url'] = base_url('search.tutor');
//                $config['total_rows'] = $this->model_m->recordsCount();
//                $config['per_page'] = 20; 
//                $config['num_links'] = 3;
//                $config['uri_segment'] = 3;
//                $this->pagination->initialize($config); 
//
//                $data['pagination'] =$this->pagination->create_links();
//                $data['pagination_data']=$this->model_m->getRecords($page, $config['per_page']);
                
                $content_view = 'v_search_tutor'; 
                
           }           
           elseif($uri_=='advance.search.form'){
                $data['select_district']  = $this->get_comboBox(12,'id');
                $data['select_level']  = $this->get_level0(10);
                $data['select_preference']  = $this->get_comboBox(11);
                $data['select_preference_dtl']  = $this->get_preference();
                $content_view = 'v_search_tutor_advance';  
            }        
           elseif($uri_=='check.email.availability'){
                $this->checkavailability();
            }            
            elseif($uri_=='login.verification.tutor'){
                $this->login_verification_tutor();
            }
            elseif($uri_=='tutor.login'){ //login dari menu dashboard (1)
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                if ($this->tutor_login($email,$password)){
                    redirect(base_url().'main.tutor','refresh');
                    exit(0);
                    //$content_view = 'tutor/v_main_dashboard_tutor';                      
                }
                else{ //berhasil login                    
                    $data['module']='login_failed';
                    $data['verification_status']=-1;
                    $data['confirmation_head']='Login Failed. Invalid Email or Password';
                    $data['message_content']='You have entered an invalid email or password. 
                        If you have forgotten your password, you may <a href="reset">reset it here</a>. 
                        Read this <a href="help.116">help section</a> if you have forgotten your registered email. 
                        You may also <a href="help.117">read this help</a> for reasons why you are not able to login<br /><br />
                        <a href="login">Click here</a> to login again.';
                    $content_view = 'v_message';   
                }
            }
            elseif($uri_=='tutor.login.forget'){
                
                $data['module']='tutor_login_forget';
                $data['verification_status']=-1;
                $data['confirmation_head']='Forget Password';
                $data['message_content']='';
                $content_view = 'v_message';   
            }
            elseif($uri_=='top.tutor'){
                
                $data['module']='top-tutor';
                $data['header_title']='Top Tutor';
                $data['tutor_data']=$this->mdl_dashboard->get_top_tutors();
                $content_view = 'v_detail_info';   
            }
            elseif($uri_=='more.blog.news'){                
                $data['module']='blog-news';                
                $data['news_data']=$this->mdl_dashboard->get_blog_news_all();
                $content_view = 'v_detail_berita';   
            }            
            elseif($uri_=='more.events'){                
                $data['module']='more-events';                
                $data['events_data']=$this->mdl_dashboard->get_events_all();
                $content_view = 'v_detail_event';   
            }                      
            elseif($uri_=='tutor.login.forget.sendingmail'){
                $data['module']='general';
                $data['verification_status']=-1;
                $data['confirmation_head']='Forget Tutor Password';                
                $data['module']='general';
                $this->db->where('email',$this->input->post('email'));
                $this->db->where('status',1);//status =1 adalah user sudah ter-registrasi
                $query=$this->db->get('tutor_registers');
                if($query->num_rows()>0){
                    $row=$query->row();
                    $datenow = date("Y-m-d h:i:s");
                    $pid = $row->id;
                    $username = $row->name;
                    $password = $row->passwd;
                    $email = $this->input->post('email');
                    $this->load->library('encrypt');
                    
                    $lBody="Dear $username,
This email has been sent from ".base_url()."

You have received this email because you forget your
tutor account password.
If you did not make this request, please disregard this email.
You do not need to unsubscribe or take any further action.

------------------------------------------------
Your Tutor Account and Password 
------------------------------------------------

User Name : $email
Password  : $password

At the main page, type in your Email and password. Once set,
you will be redirect to the login page. Please login with your
account to confirm that you have successfully log on it.

Regards,
TuitionGuru Support
";
                    $lemail='';
                    $this->sent_email($username,$email ,$lemail,"Forget Tuitionguru.sg Tutor Password",$lBody);
                    //$username,$email,$link_email,$subject='',$emailBody=''
                    $data['message_content']='Forget confirmation was sent to your email.<a href="'. base_url().'">Click here to continue to main page.</a>';
                }
                else{ 
                    $data['message_content']='Your Email not found in our database. Please re-check your email. <a href="'. base_url().'">Click here to continue to the main page.</a>';
                }
                $content_view = 'v_message';      
            }
            elseif($uri_=='reset.tutor.password.request'){
                $data['module']='reset_tutor_password_request';
                $data['verification_status']=-1;
                $data['confirmation_head']='Reset Tutor Password';
                $data['message_content']='';
                $content_view = 'v_message';   
            }
            elseif($uri_=='tutor.image.file'){
                   $filename = $this->uri->segment(2, '');
                   get_tutor_image_file($filename);
            }
            elseif($uri_=='reset.tutor.password.sendrequest'){
                $data['module']='general';
                $data['verification_status']=-1;
                $data['confirmation_head']='Reset Tutor Password';                
                $data['module']='general';
                $this->db->where('email',$this->input->post('email'));
                $this->db->where('status',1);//status =1 adalah user sudah ter-registrasi
                $query=$this->db->get('tutor_registers');
                if($query->num_rows()>0){
                    $row=$query->row();
                    $datenow = date("Y-m-d h:i:s");
                    $pid = $row->id;
                    $username = $row->name;
                    $email = $this->input->post('email');
                    $this->load->library('encrypt');
                    $hash = $this->encrypt->sha1($pid.'~'.$email.'~'.$datenow.'~'.'reset.tutor.password.sendrequest');
                    
                    
                    $upd_data=array(
                        'tutor_reset_link' => $hash,
                        'tutor_reset_date' =>$datenow,
                        'tutor_reset_status'=>1
                    );
                    $this->db->where('id',$pid); 
                    
                    $this->db->update('tutor_registers',$upd_data);
                    //kirim email
                    $lemail = base_url()."reset.tutor.password/$hash";
                    $lBody="Dear $username,
This email has been sent from ".base_url()."

You have received this email because you have requested for your
tutor account password to be resetted.
If you did not make this request, please disregard this email.
You do not need to unsubscribe or take any further action.

------------------------------------------------
Password Reset Instructions
------------------------------------------------
To reset your password, simply click on, or copy and paste the
following link:

$lemail

At the page, type in your new password twice to set it. Once set,
you will be redirect to the login page. Please login with your
new password to confirm that you have successfully reset it.
You need to reset your password within one hour from the time the
request is made.

If you do not see any form, your request has expired and require
you to request again. If you still experience error, you may reply
to this email and we will see to your problem as soon as possible.

Regards,
TuitionGuru Support
";
                    $this->sent_email($username,$email,$lemail,"Reset Tuitionguru.sg Tutor Password",$lBody);
                    //$username,$email,$link_email,$subject='',$emailBody=''
                    $data['message_content']='Reset confirmation was sent to your email.<a href="'. base_url().'reset.tutor.password.request">Click here to continue to reset page.</a>';
                }
                else{ 
                    $data['message_content']='Your Email not found in our database. Please re-check your email. <a href="'. base_url().'reset.tutor.password.request">Click here to continue to the main page.</a>';
                }
                $content_view = 'v_message';   
            }
            elseif($uri_=='reset.tutor.password'){
                    
                    $data['verification_status']=-1;
                    $data['confirmation_head']='Reset Tutor Password';
                    //$data['module']='general';
                    $resetCode = $this->uri->segment(2, '');
                    $this->db->where('tutor_reset_link',$resetCode);
                    $this->db->where('tutor_reset_status',1);
                    $this->db->where('status',1);//status =1 adalah user sudah ter-registrasi
                    $query = $this->db->get('tutor_registers');
                    if($query->num_rows()>0){
                        $row = $query->row();
                        $data['hidden_value1']=$resetCode;                                                
                        $data['hidden_value2'] = $row->id;                        
                        //$data['message_content']='Successfully reset password. Please go to the <a href="'. base_url().'">main page</a> and re-login once again.';
                        $data['module']='reset_tutor_password';
                    }
                    else{
                        $data['module']='general';
                        $data['message_content']='You cannot reset your password. Please contact tuitionguru.com support for this issue. Please go to the <a href="'. base_url().'">main page</a>.';
                    }
                    $content_view = 'v_message';   
            }
            elseif($uri_=='reset.tutor.password.submit'){
                $pid = $this->input->post('value2');
                $validCode = $this->input->post('value1');
                $passwd = $this->input->post('password');
                $this->load->library('encrypt');
                $hashPasswd = $this->encrypt->sha1($passwd);
                    
                $this->db->where('id',$pid);
                $this->db->where('tutor_reset_link',$validCode);
                $this->db->where('tutor_reset_status',1);
                $this->db->where('status',1);//status =1 adalah user sudah ter-registrasi
                if ($this->db->update('tutor_registers',array('passwd'=>$passwd,'login_passwd'=>$hashPasswd,'tutor_reset_status'=>2))){
                    $data['verification_status']=-1;
                    $data['confirmation_head']='Successfully Reset Password';
                    $data['module']='general';
                    $data['message_content']='You have successfully reset your password. Please go to the <a href="'. base_url().'">main page</a> and re-login once again.';
                }
                else{
                        $data['module']='general';
                        $data['message_content']='You cannot reset your password. Please contact tuitionguru.com support for this issue. Please go to the <a href="'. base_url().'">main page</a>.';
                    }
                $content_view = 'v_message';   
            }          
            elseif($uri_=='news'){
                $fakeid = $this->uri->segment(2, '');
                if($fakeid!=''){
                    $data['view_news'] = $this->mdl_dashboard->get_news_index_row($fakeid);
                }
                else{
                    $data['view_news'] = $this->mdl_dashboard->get_news_index_all();
                }
                $content_view = 'v_news';   
            }
            elseif($uri_=='event'){
                $fakeid = $this->uri->segment(2, '')/2345;
                if($fakeid!=''){
                    $data['view_event'] = $this->mdl_dashboard->get_event_index_row($fakeid);
                }
                else{
                    $data['view_event'] = $this->mdl_dashboard->get_event_index_all();
                }
                $content_view = 'v_event';   
            }
            elseif($uri_=='show.tutor.detail'){
                $fakeid = $this->uri->segment(2, '');
                    $data['view_tutor_dtl'] = $this->mdl_dashboard->get_tutor_row($fakeid);
                
                $content_view = 'v_show_tutor_dtl';   
            }
            elseif($uri_=='help.faq'){
                $fakeid = $this->uri->segment(2, '');
                    $data['view_help'] = $this->mdl_dashboard->get_help();
                
                $content_view = 'v_show_help';   
            }            
            elseif($uri_=='request.tutor'){
                $simpan = $this->uri->segment(2, '');
                if ($simpan=='save'){
                    $this->load->library('encrypt');
                    $email = $this->input->post('email');
                    $name  = $this->input->post('name');
                    $phone  = $this->input->post('phone');
                    $address  = $this->input->post('address');
                    $postal  = $this->input->post('postal');
                    $ins_data=array(
                        'requestor_email' =>$email,
                        'requestor_name' => $name,
                        'request_date' =>date('Y-m-d'),
                        'requestor_phone' => $name,
                        'requestor_postal' => $name,
                        'requestor_address' => $name,
                        'add_info' => $name,
                    );
                    if ($this->db->insert('tui_tutor_requests',$ins_data)){                        
                        $insertId = $this->db->insert_id();
                        $username=$name;
                        $ebody="Dear $name,
            This email has been sent from ". base_url() .".
            You have received this email because this email address
            was used during request a tutor.
            
            Your request for a tutor has been accepted. A coordinator on shift will be contacting you shortly. 

            Meanwhile you can track the status of your request which includes information such as the tutors contacted and their responses. You can do this by accessing the Track My Request tab on the front page.


            Thank you!

            Regards,

            Tuition Guru";
                        $eSubject = 'TuitionGuru Tutor Request';
                        $this->sent_email($username, $email, '',$eSubject, $ebody);
                        $data['message']='Request Accepted
Your request for a tutor has been accepted. A coordinator on shift will be contacting you shortly. Your request id is '. $insertId*2345 .'.

Meanwhile you can track the status of your request which includes information such as the tutors contacted and their responses. You can do this by accessing the Track My Request tab on the front page.
<br/><br/>
Return to <a href="'.  base_url().'">Tuitionguru.sg</a>.  
';
                    } 
                    //redirect(base_url('register.tutor'),true);
                    $data['confirmation_head']='Request a tutor';
                    $data['verification_status']='0';
                    $content_view = 'v_verification_form';
                }
                else{
                    $data['job_category']=$this->get_comboBox(15);
                    $data['verification_status']='-1';                    
                    $content_view = 'v_request_tutor';
                }
            }
            elseif($uri_=='contact.us'){                
                $data['module']='contact.us';
                $simpan = $this->uri->segment(2, '');
                if ($simpan=='send'){
                    //$this->load->library('encrypt');
                    $tname = $this->input->post('tname');
                    $email = $this->input->post('email');
                    $phone = $this->input->post('phone');
                    $subject= $this->input->post('subject');
                    $issue= $this->input->post('issue');
                    $trx_dt = date('Y-m-d H:i:s');
                    $idata = array('sent_date'=> $trx_dt,
                                   'sender_name' => $tname,
                                   'sender_email' => $email,
                                   'sender_phone' => $phone,
                                   'sender_subject' => $subject,
                                   'sender_message' => $issue
                                    );
                    $this->db->where('key','webmaster_email');
                    $conf=$this->db->get('config');
                    if($conf->num_rows()>0){
                        $r=$conf->row_array();
                        $data['webmaster_email']=$r['value'];
                        $admin_email = $r['value'];
                        
                        if ($this->db->insert('contact_us',$idata)){                            
                            $eSubject='Tuitionguru.sg-Contact us new message';
                            $ebody = "Dear Admin,
            This email has been sent from ". base_url() .".
            You have received this email because someone has sent you an email from Contact Us form page.
            Please review contact/message below :
            
            Name            : $tname
            Email           : $email
            Phone number    : $phone
            Email Subject   : $email 
            Message         :
            $issue
                

            
            Thank you!

            Regards,

            Tuition Guru
";
 
                        $this->sent_email('Admin', $admin_email , '',$eSubject, $ebody);
                        $this->session->set_flashdata('message',"Your message has been sent to us. Thank you");
                        redirect(base_url('contact.us'), 'location');                        
                        }
                    }
                    
                    
                }
                $content_view = 'v_contact_us';   
            }
            else{
                $data['select_district']  = $this->get_comboBox(12,'id');
                $data['select_level']  = $this->get_level0(10);
                $data['select_preference']  = $this->get_comboBox(11);
                $data['view_news'] = $this->mdl_dashboard->get_news_index();
                $data['view_event'] = $this->mdl_dashboard->get_event_index();
                $data['select_preference_dtl']  = $this->get_preference();
                $content_view = 'v_main_dashboard';
            }
            
            $data['v'] = $this->load->view($content_view, $data, true);
            $this->load->view('main/templates',$data);               
                
	}
        private function get_menus($group_id,$ul_id='',$class=''){
               $menu = $this->mdl_dashboard->get_menu($group_id);
               $menu_ul = '';
                if (is_array($menu))
                {
                    if(isset($menu)){
                            $this->load->library('menu_tree');                            
                            foreach ($menu as $row) { 
                               //echo  $row[MENU_ID].' '.$row[MENU_PARENT].' '.$row[MENU_ID];
//                               echo $this->menu_tree->tes_panggil();
                                $this->menu_tree->add_row(
                                        $row["id"],
                                        $row["parent_id"],
                                        //' id="menu-'.$row[MENU_ID].'" class="sortable"',
                                        //' class="dd-item" data-id="'. $row[MENU_ID]. '"' ,
                                        '',
                                        (isset($row["url"]))?'<a href="'.$row["url"].'">'.$row["title"].'</a>':$row["title"]
                                );
                            }
                            $menu_ul = $this->menu_tree->generate_list($ul_id);
                        }                    
                }
                return $menu_ul;
        }
        
        private function get_comboBox($group_id,$p=''){
               $menu = $this->mdl_dashboard->get_menu($group_id);
               $comboBox = '';
                if (is_array($menu))
                {
                    if(isset($menu)){
                            $this->load->library('menu_tree');                            
                            $this->menu_tree->clear();
                            foreach ($menu as $row) {
                                if ($p='id'){
                                    $this->menu_tree->add_row_id(
                                            $row["id"],
                                            $row["parent_id"],
                                            //' id="menu-'.$row[MENU_ID].'" class="sortable"',
                                            //' class="dd-item" data-id="'. $row[MENU_ID]. '"' ,
                                            $row["id"],
                                            $row["title"],
                                            $row["class"]
                                    );
                                }
                                else{
                                    $this->menu_tree->add_row(
                                            $row["id"],
                                            $row["parent_id"],
                                            //' id="menu-'.$row[MENU_ID].'" class="sortable"',
                                            //' class="dd-item" data-id="'. $row[MENU_ID]. '"' ,
                                            $row["class"],
                                            $row["title"]
                                    );
                                }
                            }
                            $comboBox = $this->menu_tree->generate_comboBox('');                             
                        }                    
                }
                return $comboBox;
        }
        private function get_preference(){
            $pref = $this->mdl_dashboard->get_preference(10);
            $checkbox ='';
            $hdr_class='';
            if (is_array($pref))
            {
                if(isset($pref)){                        
                        foreach ($pref as $row) {                             
                            $cls = explode("_",$row['class']);                            
                            if ($hdr_class != $cls[0]){                                
                                if ($hdr_class!=''){
                                    $checkbox .= '
                                        </div>';
                                }
                                $checkbox .= '
                                <div id="'. $cls[0] .'" class="level" style="display:none">'; 
                                $hdr_class = $cls[0];
                            }
                            //'.$cls[0] .'#'. $cls[1].'#'. $cls[2] .'
                                $checkbox .= '
                                    <div class="checkbox">
                                        <label for="'. $row['class'] .'">                                           
                                            <input type="checkbox" name="subject_det[]" value="'. $row['id'] .'_subject" id="'. $row['class'] .'" /> &nbsp;'.$row['title'].'</label> 
                                    </div>';  
                        }
                        $checkbox .= '
                             </div>';
                }
            }
            return $checkbox;
        }
        private function get_level0($group_id){
               $menu = $this->mdl_dashboard->get_level0($group_id);
               $comboBox = '';
                if (is_array($menu))
                {
                    if(isset($menu)){
                            $this->load->library('menu_tree');                            
                            $this->menu_tree->clear();
                            foreach ($menu as $row) { 
                                $this->menu_tree->add_row(
                                        $row["id"],
                                        $row["parent_id"],
                                        //' id="menu-'.$row[MENU_ID].'" class="sortable"',
                                        //' class="dd-item" data-id="'. $row[MENU_ID]. '"' ,
                                        $row["class"],
                                        $row["title"]
                                );
                            }
                            $comboBox = $this->menu_tree->generate_comboBox('');                             
                        }                    
                }
                return $comboBox;
        }
        private function register_tutor(){
            $data['top_menu_ul'] = $this->get_menus(1);//get top horizontal menus
            $data['select_district']  = $this->get_comboBox(12);
            $data['select_level']  = $this->get_level0(10);
            $data['select_preference']  = $this->get_comboBox(11);
            $data['select_preference_dtl']  = $this->get_preference();
            $this->load->view('v_main_dashboard',$data);               
        }
        private function sent_email($username,$email,$link_email,$subject='',$emailBody=''){
            $str_email = $emailBody;
            $this->load->library('email');
            //$this->email->mailtype('html');
            $this->email->from('support@tuitionguru.sg', 'Tuition Guru Support');               
            $this->email->to($email); 
            $this->email->bcc('me@redyarman.net');
            $this->email->subject($subject);
            $this->email->message($str_email);	

            $this->email->send();
            
            return true;
        }
        private function checkavailability(){
            $email_=$this->input->post('email');
            $this->db->like('email', $email_);
            $query  = $this->db->get('tutor_registers');            
            if ($query->num_rows() > 0){ //apakah sudah ada
                echo 0;
            } 
            else{
                echo 1;
            }
            exit(0);
        }
        private function login_verification_tutor(){
            
            $username_=$this->input->post('uname');
            $passwd_=$this->input->post('passwd');
            $v_code_=$this->input->post('v_code');
            $this->db->where('activation_code', $v_code_);
            $this->db->where('status', '0');
            $this->db->where('email', trim($username_));
            $this->db->where('passwd', trim($passwd_)); 
            $query  = $this->db->get('tutor_registers');            
            if ($query->num_rows() > 0){ //apakah sudah ada
                $row = $query->row(); 
                $datenow = date("Y-m-d h:i:s");
                $data=array('id' => $row->id,
                            'username' =>$row->name,
                            'email'    =>$row->email,
                            'membership_id'=>$row->membership_id,
                            'activated'=>1);         
                
                if ($this->tutor_auth->tutor_login($data,$row->passwd)){
                    $this->db->where('id',$row->id);
                    $this->db->update('tutor_registers',array('status'=>1,'date_joined' =>$datenow )); //rubah status menjadi sudah terverifikasi
                    //redirect('main.tutor',true);
                }
                
                echo 'true'; 
            } 
            else{
                echo 'false';  
            }
            exit(0);
        }
        private function tutor_login($email,$password){       //login func untuk halaman depan            
            $this->db->where('email',$email);
            $this->db->where('passwd',$password);
            $this->db->where('status',1);
            $query = $this->db->get('tutor_registers');
            if ($query->num_rows() > 0){ //apakah sudah ada
                $row = $query->row(); 
                $data=array('id' => $row->id,
                            'username' =>$row->name,
                            'email' =>$row->email,
                            'membership_id'=>$row->membership_id,
                            'activated'=>1);     
                if ($this->tutor_auth->tutor_login($data,$row->passwd)){
                    return true;
                }                
            }
            return false;
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */