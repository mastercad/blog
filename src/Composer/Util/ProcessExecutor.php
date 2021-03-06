<?php











namespace Composer\Util;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;
use Composer\IO\IOInterface;




class ProcessExecutor
{
protected static $timeout = 300;

protected $captureOutput;
protected $errorOutput;
protected $io;

public function __construct(IOInterface $io = null)
{
$this->io = $io;
}










public function execute($command, &$output = null, $cwd = null)
{
if ($this->io && $this->io->isDebug()) {
$safeCommand = preg_replace_callback('{(://)(?P<user>[^:/\s]+):(?P<password>[^@\s/]+)}i', function ($m) {
if (preg_match('{^[a-f0-9]{12,}$}', $m['user'])) {
return '://***:***';
}

return '://'.$m['user'].':***';
}, $command);
$this->io->writeError('Executing command ('.($cwd ?: 'CWD').'): '.$safeCommand);
}


 
 if (null === $cwd && Platform::isWindows() && false !== strpos($command, 'git') && getcwd()) {
$cwd = realpath(getcwd());
}

$this->captureOutput = count(func_get_args()) > 1;
$this->errorOutput = null;
$process = new Process($command, $cwd, null, null, static::getTimeout());

$callback = is_callable($output) ? $output : array($this, 'outputHandler');
$process->run($callback);

if ($this->captureOutput && !is_callable($output)) {
$output = $process->getOutput();
}

$this->errorOutput = $process->getErrorOutput();

return $process->getExitCode();
}

public function splitLines($output)
{
$output = trim($output);

return ((string) $output === '') ? array() : preg_split('{\r?\n}', $output);
}






public function getErrorOutput()
{
return $this->errorOutput;
}

public function outputHandler($type, $buffer)
{
if ($this->captureOutput) {
return;
}

echo $buffer;
}

public static function getTimeout()
{
return static::$timeout;
}

public static function setTimeout($timeout)
{
static::$timeout = $timeout;
}








public static function escape($argument)
{
return ProcessUtils::escapeArgument($argument);
}
}
