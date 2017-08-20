<?php

class User extends \HXPHP\System\Model
{
	static $validates_uniqueness_of = array(
      	array(
      	'username',
      	 'message' => 'Já existe um Usuário com este nick'
      	 ),
      	array(
			'email',
			'message' => 'Já existe um usuário com este e-mail cadastrado.'
		)
    );
	static $validates_presence_of = array(
		array(	'name',
				'message' => 'O Nome é um campo Obrigatório!'
			),
		array(	'email',
				'message' => 'O Email é um campo Obrigatório!'
			),
		array(	'username',
				'message' => 'O Usuário é um campo Obrigatório!'
			),
		array(	'password',
				'message' => 'A senha é um campo Obrigatório!'
			)
	);
	public static function cadastrar(array $post)
	{
		$callbackObj = new \stdClass;
		$callbackObj->user = null;
		$callbackObj->status = false;
		$callbackObj->errors = array();

		$role = Role::find_by_role('user');

		if(is_null($role)){
			array_push($callbackObj->errors, 'A role User não existe. Contate o ADM.');
			return $callbackObj;
		}else{
		$user_data = array(
			'role_id' => $role->id,
			'status' => 1
		));
		$password = \HXPHP\System\Tools::hashHX($post['password']);

		$post = array_merge($post, $user_data, $password);

		$cadastrar = self::create($post);

		if ($cadastrar->is_valid()) {
			$callbackObj->user = $cadastrar;
			$callbackObj->status = true;
			return $callbackObj;
		}

		$errors = $cadastrar->errors->get_raw_errors();

		foreach ($errors as $field => $message) {
			array_push($callbackObj->errors, $message[0]);
		}

		return $callbackObj;

	}
}