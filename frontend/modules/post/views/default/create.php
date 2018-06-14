<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>
<?php $form = ActiveForm::begin();?>

    <?php echo $form->field($model, 'picture')->fileInput();?>
    <?php echo $form->field($model, 'description');?>
    <?php echo Html::submitButton('add');?>

<?php ActiveForm::end();?>
