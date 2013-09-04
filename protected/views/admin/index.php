<div class="span12"><h1 class="name">Панель управления</h1>


    <p style="font-size:14px;"><b>Всего:</b> водителей <span
            class="label label-success"><?= Driver::model()->count(); ?></span>, клиентов <span class="label label-success"><?= Order::CountClients(); ?></span></p>

    <p></p>

    <p></p>

    <p style="font-size:14px;"><b>Заказы</b> (<a href="#">За сегодня</a>), (<a href="#">За месяц</a>), (<a
            href="#">Все</a>)</p>
    <table class="table table-hover table-striped table-bordered table-condensed">
        <thead>
        <tr>
            <th>Выполненные</th>
            <th>Такси не найдено</th>
            <th>Клиент отклонил</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=Order::model()->countByAttributes(array('status' => 'success'));?></td>
            <td><?=Order::model()->countByAttributes(array('status' => 'taxi_no_found'));?></td>
            <td><?=Order::model()->countByAttributes(array('status' => 'cancelled'));?></td>
        </tr>
        </tbody>
    </table>


    <p></p>

    <p></p>

    <p style="font-size:14px;"><b>Водители на проверку</b></p>
    <table class="table table-hover table-striped table-bordered table-condensed">
        <thead>
        <tr>
            <th>Водитель</th>
            <th>Вод. удостоверение</th>
            <th>Номер телефона</th>
            <th>Служба такси</th>
            <th>Авто</th>
            <th>Подтверждение</th>
        </tr>
        </thead>
        <tbody>
        <? foreach (Driver::model()->findAllByAttributes(array('accepted' => 0)) as $driver): ?>
            <tr>
                <td><?= $driver->name . ' ' . $driver->surname; ?></td>
                <td><?= $driver->document_number; ?></td>
                <td><?= $driver->phone ?></td>
                <td><?= $driver->organization ?></td>
                <td><?= $driver->car . ', ' . $driver->car_color . ', ' . $driver->car_number ?></td>
                <td><a href="#" class="btn btn-primary btn-mini btn-decline"
                       data-id="<?= $driver->id ?>">Подтвердить</a></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>

    <script>
        $('.btn-decline').each(function () {
            $(this).click(function () {
                $.get('/admin/accept/id/' + $(this).data('id'));
                $(this).parents('tr').remove();
                return false;
            });
        });
    </script>