<?php 
defined('_JEXEC') or die('Restricted access'); 

$result = JRequest::getVar('result', '');
if ($result != '') {
	$messages = array('Ваша заявка принята, мы свяжемся с вами в ближайшее время.');
}
?>

<a name="<?php echo("mid_" . $module->id); ?>"></a>

<div class="foxcontainer<?php echo($params->get("moduleclass_sfx")); ?>" style="width:<?php
echo($params->get("form_width", "100") . $params->get("form_unit", "%"));
?> !important;">

	<?php
// Page Subheading if needed
	if (!empty($page_subheading))
		echo("<h2>" . $page_subheading . "</h2>" . PHP_EOL);
	?>

	<?php
	/* Don't remove the following code, or you will loose system messages too, like
	  "Invalid field: email" or "Your messages has been received" and so on.
	  If you have problems related to language files, fix your language file instead. */
	if (count($messages)) {
		echo('<ul class="fox_messages">');
		foreach ($messages as $message) {
			echo("<li>" . $message . "</li>");
		}
		echo("</ul>");
	}
	
	?>

	<?php if ($result == '') { ?>
	
	<?php if (!empty($form_text)) { ?>
		<form id="request-individual"
					enctype="multipart/form-data" class="foxform" 
					action="/index.php?option=com_foxcontact&task=requestIndividual" method="post">
						
			<div class="errormsg"></div>
			
			<div id="first" style="display: block;" class="view-page">
				<big class="form-ignored steps"><a name="#step1"></a>Шаг 1</big>
				<table width="100%" cellspacing="1" cellpadding="4" border="0" bgcolor="#cdcbc9">
					<tbody>
						<tr>
							<td bgcolor="#ffffff">&nbsp;</td>
							<td bgcolor="#ffffff"><b>Физ. лицо</b></td>
							<td bgcolor="#ffffff"><b>Доверенное лицо (поручитель)</b></td>
						</tr>
						<tr>
							<td bgcolor="#F7F6F3">1.&nbsp;ФИО&nbsp;<font color="red">*</font></td>
							<td bgcolor="#F7F6F3"><input class="fiz" type="text" name="fio1" value=""></td>
							<td bgcolor="#F7F6F3"><input class="dov" type="text" name="fio2" value=""></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;№ доверенности (для поручителя)&nbsp;<font color="red">*</font></td>
							<td bgcolor="#ffffff"><input class="dov" type="text" name="number_dov" value=""></td>
						</tr>
						<tr>
							<td bgcolor="#F7F6F3">2.&nbsp;Дата рождения&nbsp;<font color="red">*</font></td>
							<td bgcolor="#F7F6F3"><input class="fiz" type="text" name="birthday1" value=""></td>
							<td bgcolor="#F7F6F3"><input class="dov" type="text" name="birthday2" value=""></td>
						</tr>
						<tr><td bgcolor="#ffffff" colspan="3" style="padding-right: 3px;">3.&nbsp;Паспортные данные&nbsp;<font color="red">*</font>&nbsp;</td></tr>
						<tr class="passport">
							<td bgcolor="#ffffff">

                            <div>серия <font color="red">*</font></div>
                            <div>номер <font color="red">*</font></div>
                            <div>кем и когда выдан <font color="red">*</font></div>


							</td>
							<td bgcolor="#ffffff">
                            <input class="fiz" type="text" name="passport_serial1" value="">
                            <input class="fiz" type="text" name="passport_number1" value="">
                            <input class="fiz" type="text" name="passport_issued1" value="">
							</td>
							<td bgcolor="#ffffff">
                            <input class="dov" type="text" name="passport_serial2" value="">
                            <input class="dov" type="text" name="passport_number2" value="">
                            <input class="dov" type="text" name="passport_issued2" value="">
							</td>
						</tr>
						<tr>
							<td bgcolor="#F7F6F3">4.&nbsp;Заказчик&nbsp;<font color="red">*</font></td>
							<td bgcolor="#F7F6F3" colspan="2"><select style="width: 98%;" name="customer" id="customer">
									<option value="Собственник">Собственник</option>
									<option value="Владелец">Владелец</option>
									<option value="Арендатор">Арендатор</option>
									<option value="Наследник">Наследник</option>
									<option value="Член ГСК">Член ГСК</option>
									<option value="Член СТ">Член СТ</option>
									<option value="Застройщик">Застройщик</option>
									<option value="Иное лицо">Иное лицо</option>
								</select></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff">5.&nbsp;Объект заказа&nbsp;<font color="red">*</font></td>
							<td bgcolor="#ffffff" colspan="2"><select style="width: 98%;" name="object_type" id="object_type">
									<option value="Земельный участок">Земельный участок</option>
									<option value="Домовладение">Домовладение</option>
									<option value="Жилой дом">Жилой дом</option>
									<option value="Индивидуальное жилое строение">Индивидуальное жилое строение</option>
									<option value="Квартира">Квартира</option>
									<option value="Комната">Комната</option>
									<option value="Нежилое здание">Нежилое здание</option>
									<option value="Нежилое помещение">Нежилое помещение</option>
									<option value="Объект незавершенного строительства">Объект незавершенного строительства</option>
									<option value="Гараж (строение)">Гараж (строение)</option>
									<option value="Гараж (помещение)">Гараж (помещение)</option>
									<option value="Линейный объект">Линейный объект</option>
									<option value="Иной объект">Иной объект</option>
								</select></td>
						</tr>
						<tr>
							<td bgcolor="#F7F6F3">6.&nbsp;Площадь объекта (по внутренним размерам)</td>
							<td bgcolor="#F7F6F3" colspan="2"><input type="text" name="square" value="" id="square"></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff">7.&nbsp;Адрес объекта&nbsp;<font color="red">*</font></td>
							<td bgcolor="#ffffff" colspan="2">
								<table cellspacing="0" cellpadding="0" border="0" class="address">
									<tbody>
										<tr>
											<td>город <font color="red">*</font></td>
											<td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="city" value="" id="city"></td>
										</tr>
										<tr>
											<td>район <font color="red">*</font></td>
											<td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="region" value="" id="region"></td>
										</tr>
										<tr>
											<td>улица <font color="red">*</font></td>
											<td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="street" value="" id="street"></td>
										</tr>
										<tr>
											<td>дом <font color="red">*</font></td>
											<td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="house" value="" id="house"></td>
										</tr>
										<tr>
											<td>квартира <font color="red">*</font></td>
											<td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="flat" value="" id="flat"></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td bgcolor="#F7F6F3">8.&nbsp;Имеющиеся у вас документы&nbsp;<font color="red">*</font></td>
							<td bgcolor="#F7F6F3" colspan="2" class="docs">

								<label>
									<input type="checkbox" name="docs[0]" value="Договор купли-продажи">
									Договор купли-продажи
								</label>

								<label>
									<input type="checkbox" name="docs[1]" value="Договор мены">
									Договор мены
								</label>

								<label>
									<input type="checkbox" name="docs[2]" value="Договор дарения">
									Договор дарения
								</label>

								<label>
									<input type="checkbox" name="docs[3]" value="Договор приватизации">
									Договор приватизации
								</label>

								<label>
									<input type="checkbox" name="docs[4]" value="Свидетельство о праве на наследство">
									Свидетельство о праве на наследство
								</label>

								<label>
									<input type="checkbox" name="docs[5]" value="Свидетельство о государственной регистрации права">           Свидетельство о государственной регистрации права
								</label>

								<label>
									<input type="checkbox" name="docs[6]" value="Выписка из ЕГРП">
									Выписка из ЕГРП
								</label>

								<label>
									<input type="checkbox" name="docs[7]" value="Решение суда о признании права собственности">
									Решение суда о признании права собственности
								</label>

								<label>
									<input type="checkbox" name="docs[8]" value="Акт приема-передачи жилого помещения по договору долевого участия в строительстве">
									Акт приема-передачи жилого помещения по договору долевого участия в строительстве
								</label>

								<label>
									<input type="checkbox" name="docs[9]" value="Договор социального найма">
									Договор социального найма
								</label>

								<label>
									<input type="checkbox" name="docs[10]" value="Ордер о предоставлении жилого помещения">
									Ордер о предоставлении жилого помещения
								</label>

								<label>
									<input type="checkbox" name="docs[11]" value="Договор оперативного управления с приложением">
									Договор оперативного управления с приложением
								</label>

								<label>
									<input type="checkbox" name="docs[12]" value="Договор хозяйственного ведения с приложением">
									Договор хозяйственного ведения с приложением
								</label>

								<label>
									<input type="checkbox" name="docs[13]" value="Свидетельство о смерти наследодателя">
									Свидетельство о смерти наследодателя
								</label>

								<label>
									<input type="checkbox" name="docs[14]" value="Завещание">
									Завещание
								</label>

								<div class="form-ignored">
									Иное <input type="text" size="50" name="other" value=""><br>
									
                <div class="bUpload">
									Копии документов <input type="file" name="copy_document">
								</div>
									</div>
							</td>
							</div>
						</tr>
						<tr>
							<td bgcolor="#ffffff">9.&nbsp;Необходимый вам документ&nbsp;<font color="red">*</font></td>
							<td bgcolor="#ffffff" colspan="2"><input type="text" data-required="1" name="need_doc" value=""></td>
						</tr>
						<tr>
							<td bgcolor="#F7F6F3">10.&nbsp;Услуга&nbsp;<font color="red">*</font></td>
							<td bgcolor="#F7F6F3" colspan="2"><select style="width: 98%;" name="id_service">
									<option value="Техническая инвентаризация и учет объектов капитального ремонта">Техническая инвентаризация и учет объектов капитального строительства</option>
									<option value="Подготовка технической документации">Подготовка технической документации</option>
									<option value="Геодезические и землеустроительные работы">Геодезические и землеустроительные работы</option>
									<option value="Подготовка технической документации (межевой план)">Подготовка технической документации (межевой план)</option>
									<option value="Риэлторские услуги">Риэлторские услуги</option>
									<option value="Перепланировка">Перепланировка</option>
									<option value="Юридические услуги">Юридические услуги</option>
									<option value="Оценка рыночной стоимости объектов недвижимости">Оценка рыночной стоимости объектов недвижимости</option>
								</select></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff">11.&nbsp;Характер работ</td>
							<td bgcolor="#ffffff" colspan="2"><select style="width: 98%;" name="id_work_type">
									<option value="по имеющимся данным без проведения технической инвентаризации">по имеющимся данным без проведения технической инвентаризации</option>
									<option value="по результатам обследования (есть изменения объекта)">по результатам обследования (есть изменения объекта)</option>
									<option value="по результатам обследования (нет изменений объекта)">по результатам обследования (нет изменений объекта)</option>
								</select></td>
						</tr>
						<tr>
							<td bgcolor="#F7F6F3">12.&nbsp;Цель заказа работ (услуг)&nbsp;<font color="red">*</font></td>
							<td bgcolor="#F7F6F3" colspan="2"><input type="text" data-required="1" name="target" value=""></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff">13.&nbsp;Количество экземпляров</td>
							<td bgcolor="#ffffff" colspan="2"><input type="text" name="doc_count" value=""></td>
						</tr>
						<tr>
							<td bgcolor="#F7F6F3">14.&nbsp;Сроки исполнения<font color="red">*</font><br> (желаемая дата получения заказа). </td>
							<td bgcolor="#F7F6F3" colspan="2"><input type="text" data-required="1" name="need_date" value="" id="need_date"></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff">15.&nbsp;Контактный телефон, e-mail&nbsp;<font color="red">*</font></td>
							<td bgcolor="#ffffff" colspan="2"><input data-required="1" type="text" name="contact" value=""></td>
						</tr>
						
						<tr><td bgcolor="#ffffff" colspan="3">Внимание! На субботу и воскресенье запись не производится</td></tr>
					</tbody>
				</table>
				<div class="form-ignored">
					<input type="button" id="prev1" value="Назад" disabled="disabled"> <input type="button" id="next1" value="Далее">
				</div>
				<p class="form-ignored">Настоящим даю согласие на обработку персональных данных.</p>
			</div>

			<div id="second" style="display: none;" class="view-page">
				<big class="form-ignored steps"><a name="#step2"></a>Шаг 2</big>
				<table width="100%">
					<tbody>
						<tr>
							<td>Выберите филиал</td>
							<td>
								<select name="id_filial" id="id_filial">
									<option value="10">г.Орел, Ленина, 25</option>
									<option value="7">Орловский </option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<div  class="form-ignored">
					<input type="button" id="prev2" value="Назад"> <input type="button" id="next2" value="Далее">
				</div>
			</div>

			<div id="third" style="display: none;" class="view-page">
				<big class="form-ignored steps"><a name="#step3"></a>Шаг 3</big>
				<table style="width:400px;">
					<tbody>
						<tr>
							<td>Выберите дату</td>
							<td><input type="text" name="date" id="date"></td>
						</tr>
						<tr>
							<td>Выберите время</td>
							<td><select name="time" id="time">
									<option value="9:00">9:00</option>
									<option value="10:00">10:00</option>
									<option value="11:00">11:00</option>
									<option value="12:00">12:00</option>
									<option value="14:00">14.00</option>
									<option value="15:00">15.00</option>
									<option value="16:00">16.00</option>
								</select></td>
						</tr>
						<tr>
							<td>Сотрудник</td>
							<td>
								<select name="id_sotrudnik" id="id_sotrudnik">
									<option class="id_filial10" value="10">Фомина Н.В.</option>

									<option class="id_filial7" value="2">каб.№1, стол №1, тел.40-99-80</option>
									<option class="id_filial7" value="3">каб.№1, Стол №2, тел. 40-99-80</option>
									<option class="id_filial7" value="4">Каб.№1, стол №3, тел.40-99-80</option>
									<option class="id_filial7" value="5">каб.№1, Стол №4, тел. 40-99-80</option>
									<option class="id_filial7" value="6">каб.№14 (только для работ по геодезии и межеванию) тел.76-06-29</option>
								</select>
							</td>
						</tr>

						<tr class="form-ignored">
							<td align="right">Контрольное значение</td>
							<td colspan="3" style="text-align:center;">
								<!--img id="captcha" src="/index.php?option=com_foxcontact&task=captcha" alt="Контрольное значение" /-->
								<img id="captcha" src="/index.php?option=com_foxcontact&view=loader&owner=module&id=102&type=captcha" 
										 alt="Контрольное значение" width="150" height="75"/>
										 <br>
								<a id="captchaReload" href="#" 
									 onclick="document.getElementById('captcha').src='/index.php?option=com_foxcontact&view=loader&owner=module&id=102&type=captcha&'+Math.random();return false"> Обновить</a>
							</td>
						</tr>

						<tr class="form-ignored">
							<td align="right">Поле ввода</td>
							<td colspan="3"><input type="text" name="captcha">
							</td>    </tr>

					</tbody>
				</table>

				<div class="form-ignored">
					<input type="button" id="prev3" value="Назад">
					<input type="submit" id="next3" name="mid_102" value="Отправить">
				</div>
			</div>

			<!--input type="hidden" id="form_document" name="form_document"-->
			<?php //= $form_text ?>

			<div class="errormsg"></div>

		</form>
	<?php } ?>
	
	<?php } ?>
	
</div>

