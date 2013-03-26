<?php foreach($form->getOptions() as $value_key => $label): ?>
    <label>
        <input type="radio"
               name="<?php echo $form->getAttrName() ?>"
               value="<?php echo $value_key ?>"
               <?php echo $form->checked($value_key)?>
               <?php $form->displayAttrs() ?> ><?php echo $label ?>
    </label>
<?php endforeach; ?>
