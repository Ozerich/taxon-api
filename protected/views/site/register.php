<div class="span4">
    <h1 class="name">Регистрация водителя</h1>

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'register-form',
        'action' => '/register',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal')));?>
    <fieldset>


        <div class="control-group">
            <?= $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->textField($model, 'name', array('class' => 'required span3')); ?>
                <?=$form->error($model, 'name');?>
            </div>
        </div>

        <div class="control-group">
            <?= $form->labelEx($model, 'surname', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->textField($model, 'surname', array('class' => 'required span3')); ?>
                <?=$form->error($model, 'surname');?>
            </div>
        </div>

        <div class="control-group">
            <?= $form->labelEx($model, 'phone', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->textField($model, 'phone', array('class' => 'required span3', 'placeholder' => '+375296334455')); ?>
                <?=$form->error($model, 'phone');?>
            </div>
        </div>

        <div class="control-group">
            <?= $form->labelEx($model, 'license_num', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->textField($model, 'license_num', array('class' => 'required span3')); ?>
                <?=$form->error($model, 'license_num');?>
            </div>
        </div>

        <div class="control-group">
            <?= $form->labelEx($model, 'organization', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->dropDownList($model, 'organization', Organization::All(), array('class' => 'required span3')); ?>
                <?=$form->error($model, 'organization');?>
            </div>
        </div>

        <div class="control-group">
            <?= $form->labelEx($model, 'car_model', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->textField($model, 'car_model', array('class' => 'required span3')); ?>
                <?=$form->error($model, 'car_model');?>
            </div>
        </div>

        <div class="control-group">
            <?= $form->labelEx($model, 'car_color', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->textField($model, 'car_color', array('class' => 'required span3')); ?>
                <?=$form->error($model, 'car_color');?>
            </div>
        </div>
        <div class="control-group">
            <?= $form->labelEx($model, 'car_type', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->dropDownList($model, 'car_type', RegisterForm::$car_types, array('class' => 'required span3')); ?>
                <?=$form->error($model, 'car_type');?>
            </div>
        </div>

        <div class="control-group">
            <?= $form->labelEx($model, 'car_num', array('class' => 'control-label')); ?>
            <div class="controls">
                <?= $form->textField($model, 'car_num', array('class' => 'required span3')); ?>
                <?=$form->error($model, 'car_num');?>
            </div>
        </div>


        <div class="control-group">
            <div class="controls">
                <?=CHtml::submitButton('Подать заявку', array('class' => 'btn btn-warning'));?>
            </div>
        </div>

    </fieldset>


    <? $this->endWidget(); ?>


</div>