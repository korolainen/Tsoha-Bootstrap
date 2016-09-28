<?php
class UserController extends BaseController{

	public function create_new(){
		if(isset($_POST['account']) && isset($_POST['password']) && isset($_POST['phone'])){
			$hash = Security::hash_with_salt($_POST['password']);
			$user = new User(array('account' => $_POST['account'],'hash' => $hash,'phone' => $_POST['phone']));
			$user->save();
			if(LoggedUser::login($_POST['account'], $_POST['password'])){
				self::return_back('/search');
			}
		}
		Redirect::to('/signup');
	}
	
	public static function get($id){
		$user = User::get_user_i_know($id);
		View::make('profile/user.html', array('user' => $user));
	}
	
	public static function check_account(){
		if(!isset($_GET['account'])) exit();
		$account = User::check_account($_GET['account']);
		if(!empty($account)) echo 'ok';
		exit();
	}
	
}