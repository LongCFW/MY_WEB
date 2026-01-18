<h1>Chào mừng đến với Ecostore</h1>
<p>Danh sách User từ Database:</p>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user['name'] ?> (<?= $user['email'] ?>)</li>
    <?php endforeach; ?>
</ul>