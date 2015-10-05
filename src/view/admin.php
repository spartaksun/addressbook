<?php
/**
 * Index admin page template
 * @var $this \spartaksun\addresses\Application
 * @var $tree \spartaksun\addresses\components\EmployeeTree
 */
?>
<h1>Control panel</h1>
<a href="<?= \spartaksun\addresses\components\Html::createUrl('/admin/create', array('id' => 0)) ?>">Add</a>
<?= $tree->toHtml(true) ?>

<script type="text/javascript">
    var e = new EmployeeTree({
        del_class: 'delete',
        del_text: 'A you sure?',
        n_del_class: 'nondelete',
        n_del_text: 'Please delete nested entries fist.'
    });
    e.subscribe();
</script>
