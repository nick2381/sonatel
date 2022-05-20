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
		<form id="request-legalentity"
					enctype="multipart/form-data" class="foxform" 
					action="/index.php?option=com_foxcontact&task=requestLegalEntity" method="post">
						
			<div class="errormsg"></div>
			
			    <table cellspacing="1" cellpadding="4" border="0" bgcolor="#cdcbc9">
                    <colgroup>
                    <col style="width:180px;"/>
                    </colgroup>
                    
        <tbody>
            <tr>
                <td bgcolor="#ffffff"><label for="name">1.&nbsp;Наименование организации&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#ffffff"><input type="text" data-required="1" name="name" value="" id="name"></td>
            </tr>
            <tr>
                <td bgcolor="#F7F6F3"><label for="ur_address">2.&nbsp;Юридический адрес&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#F7F6F3"><input type="text" data-required="1" name="ur_address" value="" id="ur_address"></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff"><label for="post_address">3.&nbsp;Почтовый адрес&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#ffffff"><input type="text" data-required="1" name="post_address" value="" id="post_address"></td>
            </tr>
            <tr>
                <td bgcolor="#F7F6F3"><label for="phone">4.&nbsp;Телефон&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#F7F6F3"><input type="text" data-required="1" name="phone" value="" id="phone"></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff"><label for="email">5.&nbsp;E-mail&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#ffffff"><input type="text" data-required="1" name="email" value="" id="email"></td>
            </tr>
            <tr>
                <td bgcolor="#F7F6F3"><label for="customer">4.&nbsp;Заказчик&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#F7F6F3"><select data-required="1" style="width: 98%;" name="customer" id="customer">
                <option selected="" disabled="" value="">Выберите заказчика</option>
                <option value="Собственник">Собственник</option>
                <option value="Владелец (балансодержатель)">Владелец (балансодержатель) </option>
                <option value="Арендатор">Арендатор</option>
                <option value="Застройщик">Застройщик</option>
                <option value="Управляющая компания">Управляющая компания</option>
                <option value="Арбитражный управляющий">Арбитражный управляющий</option>
                <option value="Орган исполнительной власти">Орган исполнительной власти</option>
                <option value="Правоохранительные органы">Правоохранительные органы</option>
                <option value="Иное лицо">Иное лицо</option>
                </select></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff"><label for="object_type">5.&nbsp;Объект заказа&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#ffffff"><select data-required="1"  style="width: 98%;" name="object_type" id="object_type">
                <option selected="" disabled="" value="">Выберите объект заказа</option>
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
                <td bgcolor="#F7F6F3"><input type="text" name="square" value="" id="square"></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff">10.&nbsp;Адрес объекта&nbsp;<font color="red">*</font></td>
                <td bgcolor="#ffffff">
                <table cellspacing="0" cellpadding="0" border="0"  class="address">
                    <tbody>
                        <tr>
                            <td><label for="city">город <font color="red">*<br>
                            </font></label></td>
                            <td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="city" value="" id="city"></td>
                        </tr>
                        <tr>
                            <td><label for="region">район <font color="red">*<br>
                            </font></label></td>
                            <td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="region" value="" id="region"></td>
                        </tr>
                        <tr>
                            <td><label for="street">улица <font color="red">*<br>
                            </font></label></td>
                            <td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="street" value="" id="street"></td>
                        </tr>
                        <tr>
                            <td><label for="house">дом <font color="red">*<br>
                            </font></label></td>
                            <td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="house" value="" id="house"></td>
                        </tr>
                        <tr>
                            <td><label for="flat">квартира <font color="red">*<br>
                            </font></label></td>
                            <td style="padding: 0pt 0pt 3px 3px;"><input type="text" data-required="1" name="flat" value="" id="flat"></td>
                        </tr>
                    </tbody>
                </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#F7F6F3">8.&nbsp;Имеющиеся у вас документы&nbsp;<font color="red">*</font></td>
                <td bgcolor="#F7F6F3"  class="docs">
									
									<label>
										<input type="checkbox" name="docs[0]" value="Договор купли-продажи">           Договор купли-продажи
									</label>
									
									<label>
										<input type="checkbox" name="docs[1]" value="Договор мены">           Договор мены
									</label>
									
									<label><input type="checkbox" name="docs[2]" value="Договор дарения">           Договор дарения</label>
									
									<label><input type="checkbox" name="docs[3]" value="Договор приватизации">           Договор приватизации</label>
									
                <label><input type="checkbox" name="docs[4]" value="Свидетельство о праве на наследство">           Свидетельство о праве на наследство</label>
								
                <label><input type="checkbox" name="docs[5]" value="Свидетельство о государственной регистрации права">           Свидетельство о государственной регистрации права</label>
                
								<label><input type="checkbox" name="docs[6]" value="Выписка из ЕГРП">           Выписка из ЕГРП</label>
                
								<label><input type="checkbox" name="docs[7]" value="Решение суда о признании права собственности">           Решение суда о признании права собственности</label>
								
                <label><input type="checkbox" name="docs[8]" value="Акт приема-передачи жилого помещения по договору долевого участия в строительстве">           Акт приема-передачи жилого помещения по договору долевого участия в строительстве</label>
									
                <label><input type="checkbox" name="docs[9]" value="Договор социального найма">           Договор социального найма</label>
								
                <label><input type="checkbox" name="docs[10]" value="Ордер о предоставлении жилого помещения">           Ордер о предоставлении жилого помещения</label>
								
                <label><input type="checkbox" name="docs[11]" value="Договор оперативного управления с приложением">           Договор оперативного управления с приложением</label>
								
                <label><input type="checkbox" name="docs[12]" value="Договор хозяйственного ведения с приложением">           Договор хозяйственного ведения с приложением</label>
								
                <label><input type="checkbox" name="docs[13]" value="Свидетельство о смерти наследодателя">           Свидетельство о смерти наследодателя</label>
								
                <label><input type="checkbox" name="docs[14]" value="Завещание">           Завещание</label>
								
								<div class="form-ignored">
                Иное <input type="text" size="50" name="other" value=""><br>
                <div class="bUpload">Копии документов <input type="file" name="copy_document">
									</div>
								</div>
								</td>
            </tr>
            <tr>
                <td bgcolor="#ffffff"><label for="need_doc">9.&nbsp;Необходимый вам документ&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#ffffff"><input type="text" data-required="1" name="need_doc" value="" id="need_doc"></td>
            </tr>
            <tr>
                <td bgcolor="#F7F6F3"><label for="id_usl">10.&nbsp;Услуга&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#F7F6F3"><select data-required="1" style="width: 98%;" name="id_usl" id="id_usl">
                <option selected="" disabled="" value="">Выберите услугу</option>
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
                <td bgcolor="#ffffff"><select style="width: 98%;" name="id_work_type" id="id_work_type">
                <option selected="" disabled="" value="">Выберите характер работ</option>
                <option value="по имеющимся данным без проведения технической инвентаризации">по имеющимся данным без проведения технической инвентаризации</option>
                <option value="по результатам обследования (есть изменения объекта)">по результатам обследования (есть изменения объекта)</option>
                <option value="по результатам обследования (нет изменений объекта)">по результатам обследования (нет изменений объекта)</option>
                </select></td>
            </tr>
            <tr>
                <td bgcolor="#F7F6F3"><label for="target">12.&nbsp;Цель заказа работ (услуг)&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#F7F6F3"><input data-required="1" type="text" name="target" value="" id="target"></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff">13.&nbsp;Количество экземпляров</td>
                <td bgcolor="#ffffff"><input type="text" name="doc_count" value="" id="doc_count"></td>
            </tr>
            <tr>
                <td bgcolor="#F7F6F3"><label for="need_date">14.&nbsp;Сроки исполнения (желаемая дата получения заказа)&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#F7F6F3"><input type="text" data-required="1" name="need_date" value="" id="need_date"></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff"><label for="contact">15.&nbsp;Контактный телефон, e-mail&nbsp;<font color="red">*<br>
                </font></label></td>
                <td bgcolor="#ffffff"><input type="text" data-required="1" name="contact" value="" id="contact"></td>
            </tr>
            <tr>
                <td bgcolor="#F7F6F3">16.&nbsp;Дополнительная информация&nbsp;</td>
                <td bgcolor="#F7F6F3"><textarea cols="50" rows="6" name="note" id="note"></textarea></td>
            </tr>
            <tr  class="form-ignored">
	<td bgcolor="#F7F6F3"><label for="dec_num">Введите текст с картинки &nbsp;<font color="red">*<br><em></em></font></label></td>
    <td bgcolor="#F7F6F3">
	  <img id="captcha" src="/index.php?option=com_foxcontact&view=loader&owner=module&id=138&type=captcha" 
										 alt="Контрольное значение" width="150" height="75"/>
								<a id="captchaReload" href="#" 
									 onclick="document.getElementById('captcha').src='/index.php?option=com_foxcontact&view=loader&owner=module&id=138&type=captcha&'+Math.random();return false">[ Обновить ]</a>
	</td>
</tr>        
				<tr class="form-ignored">
							<td bgcolor="#ffffff" align="right">Поле ввода</td>
							<td bgcolor="#ffffff" colspan="3"><input type="text" name="captcha">
							</td>    </tr>

				</tbody>
    </table>
    <div align="center" class="form-ignored">
			<input type="submit" name="mid_138" name="submit" value="Отправить">
		</div>
		
			<!--input type="hidden" id="form_document" name="form_document"-->
			<?php //= $form_text ?>

			<div class="errormsg"></div>

		</form>
	<?php } ?>

<?php } ?>
	
</div>

