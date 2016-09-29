<?php
class UserController extends BaseController{

	public function create_new(){
		CheckPost::required_redirect(array('first_name','last_name','account','password','password_check','phone'), '/signup');
		$hash = Security::hash_with_salt($_POST['password']); 
		$user = new User(array('account' => $_POST['account'],
								'hash' => $hash,
								'first_name' => $_POST['first_name'], 
								'last_name' => $_POST['last_name'], 
								'phone' => $_POST['phone'], 
								'password' => $_POST['password'], 
								'password_check' => $_POST['password_check']));
		$user->check_errors_and_redirect();
		$user->save();
		if(LoggedUser::login($_POST['account'], $_POST['password'])){
			Redirect::back('/search');
		}
		Redirect::back('/signup');
	}
	
	public static function get($id){
		$user = User::get_user_i_know($id);
		if(!$user->exists) Redirect::to('/search');
		View::make('profile/user.html', array('user' => $user));
	}
	
	public static function check_account(){
		if(!isset($_GET['account'])) exit();
		if(User::account_exists($_GET['account'])) echo 'ok';
		exit();
	}
	
}