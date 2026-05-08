<?php
$file = __DIR__ . '/config/categories.php';
$content = file_get_contents($file);

// Find the আন্তর্জাতিক line and insert সারাদেশ block after it
$needle = "'slug' => 'world'";

$insertBlock = "\n        [\n" .
    "            'name_bn' => '\u09b8\u09be\u09b0\u09be\u09a6\u09c7\u09b6',\n" .
    "            'name_en' => 'Country News',\n" .
    "            'slug' => 'country-news',\n" .
    "            'meta_title' => '\u09b8\u09be\u09b0\u09be\u09a6\u09c7\u09b6 \u09b8\u0982\u09ac\u09be\u09a6 | Dhaka Magazine',\n" .
    "            'meta_description' => '\u09a6\u09c7\u09b6\u09c7\u09b0 \u09ac\u09bf\u09ad\u09bf\u09a8\u09cd\u09a8 \u099c\u09c7\u09b2\u09be \u0993 \u0985\u099e\u09cd\u099a\u09b2\u09c7\u09b0 \u09b8\u09b0\u09cd\u09ac\u09b6\u09c7\u09b7 \u0996\u09ac\u09b0, \u0998\u099f\u09a8\u09be, \u099c\u09a8\u099c\u09c0\u09ac\u09a8 \u0993 \u09b8\u09cd\u09a5\u09be\u09a8\u09c0\u09af\u09bc \u0986\u09aa\u09a1\u09c7\u099f \u09aa\u09a1\u09bc\u09c1\u09a8 Dhaka Magazine-\u098f\u0964',\n" .
    "            'children' => [],\n" .
    "        ],";

// Find the position of the end of the আন্তর্জাতিক line (after the closing ],)
$pos = strpos($content, $needle);
if ($pos === false) {
    echo "Needle not found!\n";
    exit(1);
}

// Find the ], that closes the আন্তর্জাতিক array entry
$lineEnd = strpos($content, "],", $pos);
if ($lineEnd === false) {
    echo "Line end not found!\n";
    exit(1);
}
$lineEnd += 2; // include the ],

// Insert the new block right after
$newContent = substr($content, 0, $lineEnd) . $insertBlock . substr($content, $lineEnd);
file_put_contents($file, $newContent);
echo "SUCCESS: সারাদেশ inserted after আন্তর্জাতিক\n";
