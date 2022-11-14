
<h2>Manage Permissions</h2>

<div class="well" align="center">
<table class="perms">
    <form method="post">
        <tr>
            <td><label for="profileId">Profile</label></td>
            <td><?= $this->tag->select(['profileId', $profiles, 'using' => ['id', 'name'], 'useEmpty' => true, 'emptyText' => '...', 'emptyValue' => '']) ?></td>
            <td><?= $this->tag->submitButton(['Search', 'class' => 'btn btn-primary', 'name' => 'search']) ?></td>
        </tr>
</table>
</div>
<?php if ($this->request->isPost() && $profile) { ?>

<?php foreach ($this->acl->getResources() as $resource => $actions) { ?>

<h3><?= $resource ?></h3>
    <table class="table table-bordered table-striped" align="center">
        <thead>
        <tr>
            <th width="5%"></th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($actions as $action) { ?>
        <tr>
            <td align="center"><input type="checkbox" name="permissions[]" value="<?= $resource . '.' . $action ?>"  <?php if (isset($permissions[$resource . '.' . $action])) { ?> checked="checked" <?php } ?>></td>
            <td><?= $this->acl->getActionDescription($action) . ' ' . $resource ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>

<?php } ?>

<?= $this->tag->submitButton(['Submit', 'class' => 'btn btn-primary', 'name' => 'submit']) ?>
</form>
<?php } ?>
