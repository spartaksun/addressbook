<?php
/**
 * Main page template
 * @var $tree \spartaksun\addresses\components\EmployeeTree
 * @var $this \spartaksun\addresses\Application
 */

?>
<h1>Employees</h1>
<pre>
    <?= $tree->toHtml() ?>
</pre>

