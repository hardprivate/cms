<h1>Страница авторизации</h1>
<p>
<form action="" method="post">
<table class="login">
	<tr>
		<th>Авторизация</th>
	</tr>
	<tr>
		<td>Для авторизации/регистрации введите номер телефона</td>
	</tr>
	<tr>
		<td><input type="input" name="phone_numb"></td>
	</tr>
	<th colspan="2" style="text-align: right">
	<input type="submit" value="Войти" name="auth0"
	style="width: 150px; height: 30px;"></th>
</table>
</form>
</p>

<?php extract($data); ?>
<?php if($login_status=="access_granted") { ?>
<p style="color:green">Авторизация прошла успешно.</p>
<?php } elseif($login_status=="access_denied") { ?>
<p style="color:red">Логин и/или пароль введены неверно.</p>
<?php } ?>
