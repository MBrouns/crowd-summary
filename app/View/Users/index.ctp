<h1>Users</h1>
<table>
    <tr>
        <th>Id</th>
        <th>User</th>
        <th>Created</th>
        <th>Modified</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
        <td><?php echo $user['User']['username']; ?></td>
        <td><?php echo $user['User']['created']; ?></td>
        <td><?php echo $user['User']['modified']; ?></td>
        <td><?php //echo $this->Html->link(' edit', array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?></td>
        <td><?php echo $this->Form->postLink('delete', array('action' => 'delete', $user['User']['id']), array('confirm' => 'Are you sure?')); ?></td>               
    </tr>
    <?php endforeach; ?>
    <?php unset($user); ?>
</table>