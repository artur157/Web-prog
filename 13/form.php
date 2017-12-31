<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Форма</title>
	</head>

	<body>
        <?php
        if (!empty($messages)) {
                print('<div id="messages">');
                // Выводим все сообщения.
                foreach ($messages as $message) {
                    print($message);
                }
                print('</div>');
            }

            // Далее выводим форму отмечая элементы с ошибками классом error
            // и задавая начальные значения элементов ранее сохраненными.
        ?>
		<form action="index.php" method="POST">
			<label> Имя
				<br /> <input type="text" name="name" size="50" <?php if ($errors['name']) {print 'class="error"';} ?> value="<?php print $values['name']; ?>"/>
			</label><br /><br />

			<label> E-mail
				<br /> <input type="text" name="email" size="50" <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>"/>
			</label><br /><br />

			<label> Год рождения
				<select name="age" <?php if ($errors['age']) {print 'class="error"';} ?> value="<?php print $values['age']; ?>">
					  <option value="1990" selected>1990</option>
					  <option value="1991" <?php if ($values['age'] == 1991) print 'selected'; ?>>1991</option>
					  <option value="1992" <?php if ($values['age'] == 1992) print 'selected'; ?>>1992</option>
					  <option value="1993" <?php if ($values['age'] == 1993) print 'selected'; ?>>1993</option>
					  <option value="1994" <?php if ($values['age'] == 1994) print 'selected'; ?>>1994</option>
					  <option value="1995" <?php if ($values['age'] == 1995) print 'selected'; ?>>1995</option>
					  <option value="1996" <?php if ($values['age'] == 1996) print 'selected'; ?>>1996</option>
					  <option value="1997" <?php if ($values['age'] == 1997) print 'selected'; ?>>1997</option>
					  <option value="1998" <?php if ($values['age'] == 1998) print 'selected'; ?>>1998</option>
					  <option value="1999" <?php if ($values['age'] == 1999) print 'selected'; ?>>1999</option>
					  <option value="2000" <?php if ($values['age'] == 2000) print 'selected'; ?>>2000</option>
				</select>
			</label><br />

			<p>Пол
				<input type="radio" name="sex" value="male" checked/> Мужской
				<input type="radio" name="sex" value="female" <?php if ($values['sex'] == 'female') print 'checked'; ?>/> Женский
			</p>

			Количество конечностей<br />
			<label><input type="radio" name="quantity" value="1" checked /> 1 </label>
			<label><input type="radio" name="quantity" value="2" <?php if ($values['quantity'] == 2) print 'checked'; ?>/> 2 </label>
			<label><input type="radio" name="quantity" value="3" <?php if ($values['quantity'] == 3) print 'checked'; ?>/> 3 </label>
			<label><input type="radio" name="quantity" value="4" <?php if ($values['quantity'] == 4) print 'checked'; ?>/> 4 </label>
			<br /><br />

			Сверхспособности<br />
			<select name="super[]" multiple="multiple">
                <option value="immortality" <?php if (strpos($values['super'], "immortality") !== false) print 'selected'; ?>>Беcсмертие</option>
                <option value="wallhack" <?php if (strpos($values['super'], "wallhack") !== false) print 'selected'; ?>>Прохождение сквозь стены</option>
                <option value="levitation" <?php if (strpos($values['super'], "levitation") !== false) print 'selected'; ?>>Левитация</option>
			</select><br /><br />
			
			Биография <br />
			<textarea name="bio" <?php if ($errors['bio']) {print 'class="error"';}?>><?php print $values['bio']; ?></textarea>
			<br />

            <p <?php if ($errors['contract']) {print 'class="error"';} ?>>
			<input type="checkbox" name="contract" <?php if (isset($values['contract']) && $values['contract'] == '1') print 'checked'; ?>>С контрактом ознакомлен
            </p>
			<br>

			<input type="submit" class="mybutton" value="Отправить" /><br><br>
            <a href='exit.php' style="{text-align: center;}">Выход</a>
		</form>
	</body>
</html>