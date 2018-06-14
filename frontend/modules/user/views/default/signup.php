<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin()?>
    <?php echo $form->field($model, 'nickname');?>
    <?php echo $form->field($model, 'email');?>
    <?php echo $form->field($model, 'password');?>
    <?php echo Html::submitButton('Register',['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>
