<div id="page_drivers">
    <h1>Водители</h1>
    <?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $dataProvider,
        'template' => "{items}",
        'columns' => array(
            array('name' => 'id', 'header' => '#'),
            array('name' => 'organization_id', 'header' => 'Организация', 'value' => function ($row, $data) {
                return $row->organization->name;
            }),
            array('name' => 'name', 'header' => 'ФИО', 'value' => function ($row, $data) {
                return $row->name . ' ' . $row->surname;
            }),
            array('name' => 'car', 'header' => 'Машина', 'value' => function ($row, $data) {
                return $row->car . ' ' . $row->car_type . ' ' . $row->car_color . ' ' . $row->car_number;
            }),
            array('name' => 'phone', 'header' => 'Телефон'),
            array('name' => 'document_number', 'header' => 'Документ'),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{update}{delete}',
                'buttons' => array(
                    'update' => array(
                        'url' => function ($row) {
                            return '/admin/driver/' . $row->id;
                        },
                    ),

                    'delete' => array(
                        'url' => function ($row) {
                            return 'admin/delete_driver/' . $row->id;
                        },
                        'click' => 'function(){return confirm("Вы уверены что хотите удалить водителя?");}',
                    )
                ),

                'htmlOptions' => array('style' => 'width: 30px'),
            ),

        ),
    )); ?>

</div>