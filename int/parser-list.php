<?php
	$bbCode->addParser(
    'size',
    '/\[size\=(.*?)\](.*?)\[\/size\]/s',
    '<font size="$1">$2</font>',
    '$2'
  );

	$bbCode->addParser(
    'ul',
    '/\[ul\](.*?)\[\/ul\]/s',
    '<ul>$1</ul>',
    '$1'
  );
	$bbCode->addParser(
    'ol',
    '/\[ol\](.*?)\[\/ol\]/s',
    '<ol>$1</ol>',
    '$1'
  );
	$bbCode->addParser(
    'li',
    '/\[li\](.*?)\[\/li\]/s',
    '<li>$1</li>',
    '$1'
  );

	$bbCode->addParser(
    'left',
    '/\[left\](.*?)\[\/left\]/s',
    '<div align="left">$1</div>',
    '$1'
  );
	$bbCode->addParser(
    'right',
    '/\[right\](.*?)\[\/right\]/s',
    '<div align="right">$1</div>',
    '$1'
  );
	$bbCode->addParser(
    'center',
    '/\[center\](.*?)\[\/center\]/s',
    '<div align="center">$1</div>',
    '$1'
  );
	$bbCode->addParser(
    'justify',
    '/\[justify\](.*?)\[\/justify\]/s',
    '<div align="justify">$1</div>',
    '$1'
  );

	$bbCode->addParser(
    'img',
    '/\[img\=(.*?)x(.*?)\](.*?)\[\/img\]/s',
    '<img width="$1" heigth="$2" src="$3">',
    ''
  );

	$bbCode->addParser(
    'hr',
    '/\[hr\]/s',
    '<hr>',
    ''
  );
	$bbCode->addParser(
    'br',
    '/\[br\]/s',
    '<br>',
    ''
  );
