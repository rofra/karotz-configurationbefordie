<?php

$string = file_get_contents('config', FILE_USE_INCLUDE_PATH);
if ($string === false) {
	echo "File not found";
	exit;
}

// Initiate the stack
$splstack = new \SplStack();

// Initiate the dom document
$dom = new DOMDocument('1.0', 'utf-8');
$splstack->push($dom);

$arr = preg_split("/(\r\n|\n|\r)/", $string);
foreach ($arr as $line) {
  $chain = trim($line);

	if (preg_match('/^(.+) {$/', $chain, $matches)) {
		$englober = $matches[1];
    $element = $dom->createElement($englober);
    
    $englober = $splstack->pop();
    $englober->appendChild($element);
    $splstack->push($englober);
    $splstack->push($element);
		continue;
	} 

  if (strcmp('}', $chain) === 0) {
    $englober = $splstack->pop();
		continue;
  }

  //echo $chain . "\n";
	$matches = explode(': ', $chain);
  if ( count($matches) == 2) {
		$a = $matches[0];
		$b = $matches[1];

    $element = $dom->createElement($a, htmlentities($b));
    $englober = $splstack->pop();
    $englober->appendChild($element);
		$splstack->push($englober);

    continue;
  }
}

echo $dom->saveXML();


