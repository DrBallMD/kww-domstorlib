<select name="<?php echo $form->getAttrName() ?>" id="<?php echo $form->getAttrId() ?>" <?php echo $form->multiple() ?>>
    <?php if( !$form->isRequired() and !$form->isMultiple() ): ?>
    <option value=""></option>
    <?php endif ?>
    <?php foreach($form->getOptions() as $key => $value): ?>
    <option value="<?php echo $key ?>" <?php echo $form->selected($key) ?>><?php echo $value ?></option>
    <?php endforeach ?>
</select>
