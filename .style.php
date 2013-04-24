<?php
function get_files($path, $files=array()) {
    $items = scandir($path);
    $exclude = array('.', '..', '.git', '.lint.php', '.style.php', '.gitignore');
    $excludepaths = array(
        '/lib/powerstack/plugins/template/lib/',
        '/lib/powerstack/plugins/captcha/lib/',
    );
    $excludepath = false;

    foreach ($excludepaths as $expath) {
        if (preg_match('#' . $expath . '#', $path)) {
            $excludepath = true;
        }
    }

    if (!$excludepath) {
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
    }

    return $files;
}

$files = get_files(dirname(__FILE__));
$results = array();

foreach ($files as $file) {
    $lines = file($file);
    $results[$file] = array();

    foreach ($lines as $key => $line) {
        if (!preg_match('#^\s+?(\*|/\*)#', $line) && !preg_match('#^(\*|/\*)#', $line)) {

            if (preg_match('#^\t+#', $line)) { 
                if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                $results[$file][($key + 1)][] = 'Use for 4 spaces instead of tabs';
            }

            if (preg_match('#\s+\n$#', $line)) {
                if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                $results[$file][($key + 1)][] = 'White space at end of line';
            }

            if (preg_match('#\s+function#', $line) && !preg_match('#\$function#', $line) && !preg_match('#("|\').+?function.+?("|\')#', $line)) {
                if (!preg_match('#\{$#', $line)) {
                    if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                    $results[$file][($key + 1)][] = 'Curly bracket { is required to be on the same line as function declaration';
                }
            }

            if (preg_match('#class\s[a-zA-z\-\_0-9]+\s+?$#', $line)) {
                if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                $results[$file][($key + 1)][] = 'Curly bracket { is required to be on the same line as class declaration';
            }

            if (preg_match('#\belse\b#', $line) && !preg_match('#else if#', $line)) {
                if (!preg_match('#\}\selse\s{$#', $line)) {
                    if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                    $results[$file][($key + 1)][] = 'Else statements should be wrapped with curly brackets } else {';
                }
            }

            if (preg_match('#\btry\b#', $line)) {
                if (!preg_match('#try\s\{#', $line)) {
                    if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                    $results[$file][($key + 1)][] = 'Curly bracket { is required to be on the same line as try statement';
                }
            }

            if (preg_match('#\b(else if|foreach|if|for|while|switch|catch)\b#', $line, $match)) {
                if (!preg_match('#("|\').+?' . $match[1] .'.+?("|\')#', $line)) {
                    if (!preg_match('#' . $match[1] . '\s\(#', $line)) {
                        if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                        $results[$file][($key + 1)][] = 'A space is required between ' . $match[1] .' and (';
                    }

                    if (!preg_match('#\s\{$#', $line)) {
                        if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                        $results[$file][($key + 1)][] = 'A space is required between ) and {';
                    }

                    if (!preg_match('#\{$#', $line)) {
                        if (!isset($results[$file][($key + 1)])) { $results[$file][($key + 1)] = array(); }
                        $results[$file][($key + 1)][] = 'Curly bracket { is required to be on the same line as ' . $match[1] . ' statement';
                    }
                }
            }
        }
    }
}

$error = false;

foreach ($results as $file => $lines) {
    if (!empty($lines)) {
        $error = true;
        foreach ($lines as $line => $errors) {
            foreach ($errors as $err) {
                echo "Coding Standard Error in " . $file . ":" . $line . ", " . $err . "\n";
            }
        }
    }
}

if ($error) {
    exit(1);
}

echo "No coding standard errors found.\n";
exit(0);
?>
