<!DOCTYPE html>
<html>
<head>
    <title>Code Coverage Report</title>
    <style type="text/css">
        <?php
        include __DIR__ .'/normalize.php.css';
        include __DIR__ .'/styles.php.css';
        ?>
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Code Coverage Report</h1>
        <table>
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Covered</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $filename => $parts): list($link, $coveredPercent) = $parts; ?>
                <tr>
                    <td>
                        <a href="<?php echo $link; ?>">
                            <?php echo htmlspecialchars($filename, ENT_HTML5); ?>
                        </a>
                    </td>
                    <td><?php printf('%.3f%%', $coveredPercent); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
