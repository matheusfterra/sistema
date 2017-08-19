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
		$userObj = new \stdClass;
		$userObj->user = null;
		$userObj->status = false;
		$userObj->errors = array();

		$role = Role::find_by_role('user');

		if(is_null($role))
			array_push($userObj->errors, 'A role User não existe. Contate o ADM.');
			return $userObj;

		$post = array_merge($post, array(
			'role_id' => $role->id,
			'status' => 1
		));
		$password = \HXPHP\System\Tools::hashHX($post['password']);

		$post = array_merge($post, $password);

		$cadastrar = self::create($post);

		if ($cadastrar->is_valid()) {
			$userObj->user = $cadastrar;
			$userObj->status = true;
			return $userObj;
		}

		$errors = $cadastrar->errors->get_raw_errors();

		foreach ($errors as $field => $message) {
			array_push($userObj->errors, $message[0]);
		}

		return $userObj;

	}
}