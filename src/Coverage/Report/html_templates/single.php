<!DOCTYPE html>
<html>
<head>
    <title>Code Coverage For <?php echo htmlspecialchars($filename, ENT_HTML5); ?></title>
    <style type="text/css">
        <?php
        include __DIR__ .'/normalize.php.css';
        include __DIR__ .'/styles.php.css';
        ?>
    </style>
</head>
<body>
    <div class="wrap">
        <h2><?php echo htmlspecialchars($filename, ENT_HTML5); ?></h2>
        <p><?php printf('%.3f%% Covered', $coveredPercent); ?></p>
        <p><a href="index.html">&laquo; Back</a></p>
        <div class="file">
            <?php foreach ($lines as $lineno => $line): ?>
            <div class="line <?php if (isset($covered[$lineno+1])): ?>covered<?php endif; ?>">
                <?php if (isset($covered[$lineno+1])): ?>
                <dl class="testcases">
                    <?php foreach ($covered[$lineno+1] as $testcase): ?>
                    <dt><?php echo htmlspecialchars($testcase->getName(), ENT_HTML5); ?></dt>
                    <dd><?php echo htmlspecialchars(sprintf(
                        '%s:%s',
                        $testcase->filename(),
                        $testcase->lineno()
                    ), ENT_HTML5); ?></dd>
                    <?php endforeach; ?>
                </dl>
                <?php endif; ?>
                <pre class="line-content"><?php echo htmlspecialchars(rtrim($line), ENT_HTML5) ?: '&nbsp;' ?></pre>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
