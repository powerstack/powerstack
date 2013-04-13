<?php
function get_files($path, $files=array()) {
    $items = scandir($path);
    $exclude = array('.', '..', '.git', '.lint.php', '.gitignore');

    foreach ($items as $item) {
        if (!in_array($item, $exclude)) {
            if (is_file(realpath($path) . '/' . $item)) {
                if (preg_match('#\.php$#', $item)) {
                    $files[] = realpath($path) . '/' . $item;
                }
            } else {
                $files = get_files(realpath($path) . '/' . $item, $files);
            }
        }
    }

    return $files;
}

$phpfiles = get_files(dirname(__FILE__));
$results = array();

foreach ($phpfiles as $php) {
    $output = exec('php -l ' . $php);

    if (preg_match('#^No syntax errors#', $output)) {
        $results[$php] = true;
    } else {
        $results[$php] = false;
    }
}

$key = array_search(false, $results);
if ($key === false) {
    echo "No syntax errors found. \n";
    exit(0);
} else {
    echo "Syntax Erorr in: " . $key . "\n";
    exit(1);
}
?>
