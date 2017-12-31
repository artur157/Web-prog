<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Форма</title>
	</head>

	<body>
		<form action="index.php" method="POST">
			<label> Имя
				<br /> <input type="text" name="name" />
			</label><br /><br />

			<label> Email
				<br /> <input type="text" name="email" />
			</label><br /><br />

			<label> Год рождения
				<select name="age">
					  <option value="1990">1990</option>
					  <option value="1991">1991</option>
					  <option value="1992">1992</option>
					  <option value="1993">1993</option>
					  <option value="1994">1994</option>
					  <option value="1995">1995</option>
					  <option value="1996">1996</option>
					  <option value="1997">1997</option>
					  <option value="1998">1998</option>
					  <option value="1999">1999</option>
					  <option value="2000">2000</option>
				</select>
			</label><br /><br />

			<p>Пол
				<input type="radio" name="sex" value="male" checked/> Мужской 
				<input type="radio" name="sex" value="female" /> Женский 
			</p><br />

			Количество конечностей<br />
			<label><input type="radio" name="quantity" value="1" checked/> 1 </label>
			<label><input type="radio" name="quantity" value="2" /> 2 </label>
			<label><input type="radio" name="quantity" value="3" /> 3 </label>
			<label><input type="radio" name="quantity" value="4" /> 4 </label>
			<br /><br />

			Сверхспособности<br />
			<select name="super[]" multiple="multiple">
				<option value="immortality" selected="selected">Беcсмертие</option>
				<option value="wallhack">Прохождение сквозь стены</option>
				<option value="levitation">Левитация</option>
			</select><br /><br />
			
			Биография <br />
			<textarea name="bio"></textarea>
			<br /><br />

			<input type="checkbox" name="contract" value='1'>С контрактом ознакомлен
			<br>
			<br><br />

			<input type="submit" value="Отправить" />
		</form>
	</body>
</html>