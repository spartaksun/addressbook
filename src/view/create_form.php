<?php
/**
 * Form for adding employee
 * @var $form \spartaksun\addresses\form\EmployeeForm
 */
$supervisor = $form->getSupervisor();
?>


<h1>Add employee for <?= $supervisor['name'] ?></h1>

<?= \spartaksun\addresses\components\Html::errorSummary($form) ?>
<div class="employee-form">
    <form method="post" action="" name="create-employee">
        <div class="form-input">
            <label for="employee[title]">Title
                <input <?= $form->attributeCssClass('title') ?>
                    type="text"
                    name="employee[title]" value="<?= $form->getAttribute('title') ?>"/>
            </label>
        </div>
        <div class="form-input">
            <label for="employee[name]">Name
                <input <?= $form->attributeCssClass('name') ?>
                    type="text"
                    name="employee[name]" value="<?= $form->getAttribute('name') ?>"/>
            </label>
        </div>
        <div class="form-input">Email
            <label for="employee[email]">
                <input <?= $form->attributeCssClass('email') ?>
                    type="text" name="employee[email]"
                    value="<?= $form->getAttribute('email') ?>"/>
            </label>
        </div>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>
</div>
