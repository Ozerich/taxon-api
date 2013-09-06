<div id="page_driver">

    <h1>Водитель <?=$model->name . ' ' . $model->surname?></h1>

    <?php

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'horizontalForm',
        'type'=>'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )); ?>

    <div class="row">
        <?=$form->dropDownListRow($model, 'organization_id', Organization::All(), array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->textFieldRow($model, 'name', array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->textFieldRow($model, 'surname', array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->textFieldRow($model, 'phone', array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->textFieldRow($model, 'car', array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->textFieldRow($model, 'car_number', array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->textFieldRow($model, 'car_color', array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->dropDownListRow($model, 'car_type', Driver::$car_types, array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->textFieldRow($model, 'document_number', array('class' => 'span6')); ?>
    </div>

    <div class="row">
        <?=$form->textFieldRow($model, 'phone', array('class' => 'span6')); ?>
    </div>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Сохранить')); ?>
        <a href="/admin/drivers" class="btn btn-danger">Назад</a>
    </div>

    <? $this->endWidget(); ?>

</div>
