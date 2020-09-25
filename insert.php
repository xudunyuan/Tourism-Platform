<?php

//insert.php

include('database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));

$error = '';
$message = '';
$validation_error = '';

$username = '';
$email = '';
$tel = '';
$Permission = '';
$birthday = '';

if($form_data->action == 'fetch_single_data')
{
	$query = "SELECT username, email, tel, Permission, birthday FROM users WHERE id='".$form_data->id."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['username'] = $row['username'];
		$output['email'] = $row['email'];
        $output['tel'] = $row['tel'];
        $output['Permission'] = $row['Permission'];
        $output['birthday'] = $row['birthday'];
	}
}
elseif($form_data->action == "Delete")
{
	$query = "
	DELETE FROM users WHERE id='".$form_data->id."'
	";
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$output['message'] = 'Data Deleted';
	}
}
else
{
	if(empty($form_data->username))
	{
		$error = 'User Name is Required';
	}
	else
	{
		$username = $form_data->username;
	}

	if(empty($form_data->email))
	{
		$error = 'Email is Required';
	}
	else
	{
		$email = $form_data->email;
	}

    if(empty($form_data->tel) && $form_data->tel!=='0')
    {
        $error = 'Tel is Required';
    }
    else
    {
        $tel = $form_data->tel;
    }

    if(empty($form_data->Permission) && $form_data->Permission!=='0')
    {
        $error = 'Permission is Required';
    }
    else
    {
        $Permission = $form_data->Permission;
    }

    if(empty($form_data->birthday))
    {
        $error = 'Birthday is Required';
    }
    else
    {
        $birthday = $form_data->birthday;
    }

	if(empty($error))
	{
		if($form_data->action == 'Edit')
		{
			$data = array(
				':username'	=>	$username,
				':email'	=>	$email,
                ':tel'		    =>	$tel,
                ':Permission'	=>	$Permission,
                ':birthday'		=>	$birthday,
				':id'			=>	$form_data->id
			);
			$query = "
			UPDATE users 
			SET username = :username, email = :email, tel = :tel, Permission = :Permission, birthday = :birthday 
			WHERE id = :id
			";

			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Data Edited';
			}
		}
	}
	else
	{
		$validation_error = $error;
	}

	$output = array(
		'error'		=>	$validation_error,
		'message'	=>	$message
	);

}



echo json_encode($output);

?>