<?php











namespace Composer\Json;

use Composer\Repository\PlatformRepository;




class JsonManipulator
{
private static $RECURSE_BLOCKS;
private static $RECURSE_ARRAYS;
private static $JSON_VALUE;
private static $JSON_STRING;

private $contents;
private $newline;
private $indent;

public function __construct($contents)
{
if (!self::$RECURSE_BLOCKS) {
self::$RECURSE_BLOCKS = '(?:[^{}]*+|\{(?:[^{}]*+|\{(?:[^{}]*+|\{(?:[^{}]*+|\{[^{}]*+\})*\})*\})*\})*';
self::$RECURSE_ARRAYS = '(?:[^\]]*+|\[(?:[^\]]*+|\[(?:[^\]]*+|\[(?:[^\]]*+|\[[^\]]*+\])*\])*\])*\]|'.self::$RECURSE_BLOCKS.')*';
self::$JSON_STRING = '"(?:[^\0-\x09\x0a-\x1f\\\\"]+|\\\\["bfnrt/\\\\]|\\\\u[a-fA-F0-9]{4})*+"';
self::$JSON_VALUE = '(?:[0-9.]+|null|true|false|'.self::$JSON_STRING.'|\['.self::$RECURSE_ARRAYS.'\]|\{'.self::$RECURSE_BLOCKS.'\})';
}

$contents = trim($contents);
if ($contents === '') {
$contents = '{}';
}
if (!$this->pregMatch('#^\{(.*)\}$#s', $contents)) {
throw new \InvalidArgumentException('The json file must be an object ({})');
}
$this->newline = false !== strpos($contents, "\r\n") ? "\r\n" : "\n";
$this->contents = $contents === '{}' ? '{' . $this->newline . '}' : $contents;
$this->detectIndenting();
}

public function getContents()
{
return $this->contents . $this->newline;
}

public function addLink($type, $package, $constraint, $sortPackages = false)
{
$decoded = JsonFile::parseJson($this->contents);


 if (!isset($decoded[$type])) {
return $this->addMainKey($type, array($package => $constraint));
}

$regex = '{^(\s*\{\s*(?:'.self::$JSON_STRING.'\s*:\s*'.self::$JSON_VALUE.'\s*,\s*)*?)'.
'('.preg_quote(JsonFile::encode($type)).'\s*:\s*)('.self::$JSON_VALUE.')(.*)}s';
if (!$this->pregMatch($regex, $this->contents, $matches)) {
return false;
}

$links = $matches[3];

if (isset($decoded[$type][$package])) {

 $packageRegex = str_replace('/', '\\\\?/', preg_quote($package));
$links = preg_replace_callback('{"'.$packageRegex.'"(\s*:\s*)'.self::$JSON_STRING.'}i', function ($m) use ($package, $constraint) {
return JsonFile::encode($package) . $m[1] . '"' . $constraint . '"';
}, $links);
} else {
if ($this->pregMatch('#^\s*\{\s*\S+.*?(\s*\}\s*)$#s', $links, $match)) {

 $links = preg_replace(
'{'.preg_quote($match[1]).'$}',

 addcslashes(',' . $this->newline . $this->indent . $this->indent . JsonFile::encode($package).': '.JsonFile::encode($constraint) . $match[1], '\\$'),
$links
);
} else {

 $links = '{' . $this->newline .
$this->indent . $this->indent . JsonFile::encode($package).': '.JsonFile::encode($constraint) . $this->newline .
$this->indent . '}';
}
}

if (true === $sortPackages) {
$requirements = json_decode($links, true);
$this->sortPackages($requirements);
$links = $this->format($requirements);
}

$this->contents = $matches[1] . $matches[2] . $links . $matches[4];

return true;
}








private function sortPackages(array &$packages = array())
{
$prefix = function ($requirement) {
if (preg_match(PlatformRepository::PLATFORM_PACKAGE_REGEX, $requirement)) {
return preg_replace(
array(
'/^php/',
'/^hhvm/',
'/^ext/',
'/^lib/',
'/^\D/',
),
array(
'0-$0',
'1-$0',
'2-$0',
'3-$0',
'4-$0',
),
$requirement
);
}

return '5-'.$requirement;
};

uksort($packages, function ($a, $b) use ($prefix) {
return strnatcmp($prefix($a), $prefix($b));
});
}

public function addRepository($name, $config)
{
return $this->addSubNode('repositories', $name, $config);
}

public function removeRepository($name)
{
return $this->removeSubNode('repositories', $name);
}

public function addConfigSetting($name, $value)
{
return $this->addSubNode('config', $name, $value);
}

public function removeConfigSetting($name)
{
return $this->removeSubNode('config', $name);
}

public function addProperty($name, $value)
{
if (substr($name, 0, 6) === 'extra.') {
return $this->addSubNode('extra', substr($name, 6), $value);
}

return $this->addMainKey($name, $value);
}

public function removeProperty($name)
{
if (substr($name, 0, 6) === 'extra.') {
return $this->removeSubNode('extra', substr($name, 6));
}

return $this->removeMainKey($name);
}

public function addSubNode($mainNode, $name, $value)
{
$decoded = JsonFile::parseJson($this->contents);

$subName = null;
if (in_array($mainNode, array('config', 'repositories', 'extra')) && false !== strpos($name, '.')) {
list($name, $subName) = explode('.', $name, 2);
}


 if (!isset($decoded[$mainNode])) {
if ($subName !== null) {
$this->addMainKey($mainNode, array($name => array($subName => $value)));
} else {
$this->addMainKey($mainNode, array($name => $value));
}

return true;
}


 $nodeRegex = '{^(\s*\{\s*(?:'.self::$JSON_STRING.'\s*:\s*'.self::$JSON_VALUE.'\s*,\s*)*?)'.
'('.preg_quote(JsonFile::encode($mainNode)).'\s*:\s*\{)('.self::$RECURSE_BLOCKS.')(\})(.*)}s';
try {
if (!$this->pregMatch($nodeRegex, $this->contents, $match)) {
return false;
}
} catch (\RuntimeException $e) {
if ($e->getCode() === PREG_BACKTRACK_LIMIT_ERROR) {
return false;
}
throw $e;
}

$children = $match[3];


 if (!@json_decode('{'.$children.'}')) {
return false;
}

$that = $this;


 if ($this->pregMatch('{("'.preg_quote($name).'"\s*:\s*)('.self::$JSON_VALUE.')(,?)}', $children, $matches)) {
$children = preg_replace_callback('{("'.preg_quote($name).'"\s*:\s*)('.self::$JSON_VALUE.')(,?)}', function ($matches) use ($name, $subName, $value, $that) {
if ($subName !== null) {
$curVal = json_decode($matches[2], true);
if (!is_array($curVal)) {
$curVal = array();
}
$curVal[$subName] = $value;
$value = $curVal;
}

return $matches[1] . $that->format($value, 1) . $matches[3];
}, $children);
} elseif ($this->pregMatch('#[^\s](\s*)$#', $children, $match)) {
if ($subName !== null) {
$value = array($subName => $value);
}


 $children = preg_replace(
'#'.$match[1].'$#',
addcslashes(',' . $this->newline . $this->indent . $this->indent . JsonFile::encode($name).': '.$this->format($value, 1) . $match[1], '\\$'),
$children
);
} else {
if ($subName !== null) {
$value = array($subName => $value);
}


 $children = $this->newline . $this->indent . $this->indent . JsonFile::encode($name).': '.$this->format($value, 1) . $children;
}

$this->contents = preg_replace_callback($nodeRegex, function ($m) use ($children) {
return $m[1] . $m[2] . $children . $m[4] . $m[5];
}, $this->contents);

return true;
}

public function removeSubNode($mainNode, $name)
{
$decoded = JsonFile::parseJson($this->contents);


 if (empty($decoded[$mainNode])) {
return true;
}


 $nodeRegex = '{^(\s*\{\s*(?:'.self::$JSON_STRING.'\s*:\s*'.self::$JSON_VALUE.'\s*,\s*)*?)'.
'('.preg_quote(JsonFile::encode($mainNode)).'\s*:\s*\{)('.self::$RECURSE_BLOCKS.')(\})(.*)}s';
try {
if (!$this->pregMatch($nodeRegex, $this->contents, $match)) {
return false;
}
} catch (\RuntimeException $e) {
if ($e->getCode() === PREG_BACKTRACK_LIMIT_ERROR) {
return false;
}
throw $e;
}

$children = $match[3];


 if (!@json_decode('{'.$children.'}', true)) {
return false;
}

$subName = null;
if (in_array($mainNode, array('config', 'repositories', 'extra')) && false !== strpos($name, '.')) {
list($name, $subName) = explode('.', $name, 2);
}


 if (!isset($decoded[$mainNode][$name]) || ($subName && !isset($decoded[$mainNode][$name][$subName]))) {
return true;
}


 if ($this->pregMatch('{"'.preg_quote($name).'"\s*:}i', $children)) {

 if (preg_match_all('{"'.preg_quote($name).'"\s*:\s*(?:'.self::$JSON_VALUE.')}', $children, $matches)) {
$bestMatch = '';
foreach ($matches[0] as $match) {
if (strlen($bestMatch) < strlen($match)) {
$bestMatch = $match;
}
}
$childrenClean = preg_replace('{,\s*'.preg_quote($bestMatch).'}i', '', $children, -1, $count);
if (1 !== $count) {
$childrenClean = preg_replace('{'.preg_quote($bestMatch).'\s*,?\s*}i', '', $childrenClean, -1, $count);
if (1 !== $count) {
return false;
}
}
}
} else {
$childrenClean = $children;
}


 if (!trim($childrenClean)) {
$this->contents = preg_replace($nodeRegex, '$1$2'.$this->newline.$this->indent.'$4$5', $this->contents);


 if ($subName !== null) {
$curVal = json_decode('{'.$children.'}', true);
unset($curVal[$name][$subName]);
$this->addSubNode($mainNode, $name, $curVal[$name]);
}

return true;
}

$that = $this;
$this->contents = preg_replace_callback($nodeRegex, function ($matches) use ($that, $name, $subName, $childrenClean) {
if ($subName !== null) {
$curVal = json_decode('{'.$matches[3].'}', true);
unset($curVal[$name][$subName]);
$childrenClean = substr($that->format($curVal, 0), 1, -1);
}

return $matches[1] . $matches[2] . $childrenClean . $matches[4] . $matches[5];
}, $this->contents);

return true;
}

public function addMainKey($key, $content)
{
$decoded = JsonFile::parseJson($this->contents);
$content = $this->format($content);


 $regex = '{^(\s*\{\s*(?:'.self::$JSON_STRING.'\s*:\s*'.self::$JSON_VALUE.'\s*,\s*)*?)'.
'('.preg_quote(JsonFile::encode($key)).'\s*:\s*'.self::$JSON_VALUE.')(.*)}s';
if (isset($decoded[$key]) && $this->pregMatch($regex, $this->contents, $matches)) {

 if (!@json_decode('{'.$matches[2].'}')) {
return false;
}

$this->contents = $matches[1] . JsonFile::encode($key).': '.$content . $matches[3];

return true;
}


 if ($this->pregMatch('#[^{\s](\s*)\}$#', $this->contents, $match)) {
$this->contents = preg_replace(
'#'.$match[1].'\}$#',
addcslashes(',' . $this->newline . $this->indent . JsonFile::encode($key). ': '. $content . $this->newline . '}', '\\$'),
$this->contents
);

return true;
}


 $this->contents = preg_replace(
'#\}$#',
addcslashes($this->indent . JsonFile::encode($key). ': '.$content . $this->newline . '}', '\\$'),
$this->contents
);

return true;
}

public function removeMainKey($key)
{
$decoded = JsonFile::parseJson($this->contents);

if (!isset($decoded[$key])) {
return true;
}


 $regex = '{^(\s*\{\s*(?:'.self::$JSON_STRING.'\s*:\s*'.self::$JSON_VALUE.'\s*,\s*)*?)'.
'('.preg_quote(JsonFile::encode($key)).'\s*:\s*'.self::$JSON_VALUE.')\s*,?\s*(.*)}s';
if ($this->pregMatch($regex, $this->contents, $matches)) {

 if (!@json_decode('{'.$matches[2].'}')) {
return false;
}

$this->contents = $matches[1] . $matches[3];
if (preg_match('#^\{\s*\}\s*$#', $this->contents)) {
$this->contents = "{\n}";
}

return true;
}

return false;
}

public function format($data, $depth = 0)
{
if (is_array($data)) {
reset($data);

if (is_numeric(key($data))) {
foreach ($data as $key => $val) {
$data[$key] = $this->format($val, $depth + 1);
}

return '['.implode(', ', $data).']';
}

$out = '{' . $this->newline;
$elems = array();
foreach ($data as $key => $val) {
$elems[] = str_repeat($this->indent, $depth + 2) . JsonFile::encode($key). ': '.$this->format($val, $depth + 1);
}

return $out . implode(','.$this->newline, $elems) . $this->newline . str_repeat($this->indent, $depth + 1) . '}';
}

return JsonFile::encode($data);
}

protected function detectIndenting()
{
if ($this->pregMatch('{^([ \t]+)"}m', $this->contents, $match)) {
$this->indent = $match[1];
} else {
$this->indent = '    ';
}
}

protected function pregMatch($re, $str, &$matches = array())
{
$count = preg_match($re, $str, $matches);

if ($count === false) {
switch (preg_last_error()) {
case PREG_NO_ERROR:
throw new \RuntimeException('Failed to execute regex: PREG_NO_ERROR', PREG_NO_ERROR);
case PREG_INTERNAL_ERROR:
throw new \RuntimeException('Failed to execute regex: PREG_INTERNAL_ERROR', PREG_INTERNAL_ERROR);
case PREG_BACKTRACK_LIMIT_ERROR:
throw new \RuntimeException('Failed to execute regex: PREG_BACKTRACK_LIMIT_ERROR', PREG_BACKTRACK_LIMIT_ERROR);
case PREG_RECURSION_LIMIT_ERROR:
throw new \RuntimeException('Failed to execute regex: PREG_RECURSION_LIMIT_ERROR', PREG_RECURSION_LIMIT_ERROR);
case PREG_BAD_UTF8_ERROR:
throw new \RuntimeException('Failed to execute regex: PREG_BAD_UTF8_ERROR', PREG_BAD_UTF8_ERROR);
case PREG_BAD_UTF8_OFFSET_ERROR:
throw new \RuntimeException('Failed to execute regex: PREG_BAD_UTF8_OFFSET_ERROR', PREG_BAD_UTF8_OFFSET_ERROR);
case 6: 
 if (PHP_VERSION_ID > 70000) {
throw new \RuntimeException('Failed to execute regex: PREG_JIT_STACKLIMIT_ERROR', 6);
}


default:
throw new \RuntimeException('Failed to execute regex: Unknown error');
}
}

return $count;
}
}
