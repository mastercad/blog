<?php











namespace Composer\Util;

use Composer\Config;
use Composer\IO\IOInterface;




class Git
{
private static $version;


protected $io;

protected $config;

protected $process;

protected $filesystem;

public function __construct(IOInterface $io, Config $config, ProcessExecutor $process, Filesystem $fs)
{
$this->io = $io;
$this->config = $config;
$this->process = $process;
$this->filesystem = $fs;
}

public function runCommand($commandCallable, $url, $cwd, $initialClone = false)
{

 $this->config->prohibitUrlByConfig($url, $this->io);

if ($initialClone) {
$origCwd = $cwd;
$cwd = null;
}

if (preg_match('{^ssh://[^@]+@[^:]+:[^0-9]+}', $url)) {
throw new \InvalidArgumentException('The source URL '.$url.' is invalid, ssh URLs should have a port number after ":".'."\n".'Use ssh://git@example.com:22/path or just git@example.com:path if you do not want to provide a password or custom port.');
}

if (!$initialClone) {

 $this->process->execute('git remote -v', $output, $cwd);
if (preg_match('{^(?:composer|origin)\s+https?://(.+):(.+)@([^/]+)}im', $output, $match)) {
$this->io->setAuthentication($match[3], urldecode($match[1]), urldecode($match[2]));
}
}

$protocols = $this->config->get('github-protocols');
if (!is_array($protocols)) {
throw new \RuntimeException('Config value "github-protocols" must be an array, got '.gettype($protocols));
}

 if (preg_match('{^(?:https?|git)://'.self::getGitHubDomainsRegex($this->config).'/(.*)}', $url, $match)) {
$messages = array();
foreach ($protocols as $protocol) {
if ('ssh' === $protocol) {
$protoUrl = "git@" . $match[1] . ":" . $match[2];
} else {
$protoUrl = $protocol ."://" . $match[1] . "/" . $match[2];
}

if (0 === $this->process->execute(call_user_func($commandCallable, $protoUrl), $ignoredOutput, $cwd)) {
return;
}
$messages[] = '- ' . $protoUrl . "\n" . preg_replace('#^#m', '  ', $this->process->getErrorOutput());
if ($initialClone) {
$this->filesystem->removeDirectory($origCwd);
}
}


 $this->throwException('Failed to clone ' . $url .' via '.implode(', ', $protocols).' protocols, aborting.' . "\n\n" . implode("\n", $messages), $url);
}


 $bypassSshForGitHub = preg_match('{^git@'.self::getGitHubDomainsRegex($this->config).':(.+?)\.git$}i', $url) && !in_array('ssh', $protocols, true);

$command = call_user_func($commandCallable, $url);

$auth = null;
if ($bypassSshForGitHub || 0 !== $this->process->execute($command, $ignoredOutput, $cwd)) {

 if (preg_match('{^git@'.self::getGitHubDomainsRegex($this->config).':(.+?)\.git$}i', $url, $match)) {
if (!$this->io->hasAuthentication($match[1])) {
$gitHubUtil = new GitHub($this->io, $this->config, $this->process);
$message = 'Cloning failed using an ssh key for authentication, enter your GitHub credentials to access private repos';

if (!$gitHubUtil->authorizeOAuth($match[1]) && $this->io->isInteractive()) {
$gitHubUtil->authorizeOAuthInteractively($match[1], $message);
}
}

if ($this->io->hasAuthentication($match[1])) {
$auth = $this->io->getAuthentication($match[1]);
$authUrl = 'https://' . rawurlencode($auth['username']) . ':' . rawurlencode($auth['password']) . '@' . $match[1] . '/' . $match[2] . '.git';
$command = call_user_func($commandCallable, $authUrl);
if (0 === $this->process->execute($command, $ignoredOutput, $cwd)) {
return;
}
}
} elseif (preg_match('{^https://(bitbucket\.org)/(.*)(\.git)?$}U', $url, $match)) { 
 $bitbucketUtil = new Bitbucket($this->io, $this->config, $this->process);

if (!$this->io->hasAuthentication($match[1])) {
$message = 'Enter your Bitbucket credentials to access private repos';

if (!$bitbucketUtil->authorizeOAuth($match[1]) && $this->io->isInteractive()) {
$bitbucketUtil->authorizeOAuthInteractively($match[1], $message);
$token = $bitbucketUtil->getToken();
$this->io->setAuthentication($match[1], 'x-token-auth', $token['access_token']);
}
} else { 
 $auth = $this->io->getAuthentication($match[1]);


 if ($auth['username'] !== 'x-token-auth') {
$token = $bitbucketUtil->requestToken($match[1], $auth['username'], $auth['password']);
if (! empty($token)) {
$this->io->setAuthentication($match[1], 'x-token-auth', $token['access_token']);
}
}
}

if ($this->io->hasAuthentication($match[1])) {
$auth = $this->io->getAuthentication($match[1]);
$authUrl = 'https://' . rawurlencode($auth['username']) . ':' . rawurlencode($auth['password']) . '@' . $match[1] . '/' . $match[2] . '.git';

$command = call_user_func($commandCallable, $authUrl);
if (0 === $this->process->execute($command, $ignoredOutput, $cwd)) {
return;
}
} else { 
 $sshUrl = 'git@bitbucket.org:' . $match[2] . '.git';
$this->io->writeError('    No bitbucket authentication configured. Falling back to ssh.');
$command = call_user_func($commandCallable, $sshUrl);
if (0 === $this->process->execute($command, $ignoredOutput, $cwd)) {
return;
}
}
} elseif ($this->isAuthenticationFailure($url, $match)) { 
 if (strpos($match[2], '@')) {
list($authParts, $match[2]) = explode('@', $match[2], 2);
}

$storeAuth = false;
if ($this->io->hasAuthentication($match[2])) {
$auth = $this->io->getAuthentication($match[2]);
} elseif ($this->io->isInteractive()) {
$defaultUsername = null;
if (isset($authParts) && $authParts) {
if (false !== strpos($authParts, ':')) {
list($defaultUsername, ) = explode(':', $authParts, 2);
} else {
$defaultUsername = $authParts;
}
}

$this->io->writeError('    Authentication required (<info>'.parse_url($url, PHP_URL_HOST).'</info>):');
$auth = array(
'username' => $this->io->ask('      Username: ', $defaultUsername),
'password' => $this->io->askAndHideAnswer('      Password: '),
);
$storeAuth = $this->config->get('store-auths');
}

if ($auth) {
$authUrl = $match[1].rawurlencode($auth['username']).':'.rawurlencode($auth['password']).'@'.$match[2].$match[3];

$command = call_user_func($commandCallable, $authUrl);
if (0 === $this->process->execute($command, $ignoredOutput, $cwd)) {
$this->io->setAuthentication($match[2], $auth['username'], $auth['password']);
$authHelper = new AuthHelper($this->io, $this->config);
$authHelper->storeAuth($match[2], $storeAuth);

return;
}
}
}

if ($initialClone) {
$this->filesystem->removeDirectory($origCwd);
}
$this->throwException('Failed to execute ' . $command . "\n\n" . $this->process->getErrorOutput(), $url);
}
}

public function syncMirror($url, $dir)
{

 if (is_dir($dir) && 0 === $this->process->execute('git rev-parse --git-dir', $output, $dir) && trim($output) === '.') {
try {
$commandCallable = function ($url) {
return sprintf('git remote set-url origin %s && git remote update --prune origin', ProcessExecutor::escape($url));
};
$this->runCommand($commandCallable, $url, $dir);
} catch (\Exception $e) {
return false;
}

return true;
}


 $this->filesystem->removeDirectory($dir);

$commandCallable = function ($url) use ($dir) {
return sprintf('git clone --mirror %s %s', ProcessExecutor::escape($url), ProcessExecutor::escape($dir));
};

$this->runCommand($commandCallable, $url, $dir, true);

return true;
}

private function isAuthenticationFailure($url, &$match)
{
if (!preg_match('{(https?://)([^/]+)(.*)$}i', $url, $match)) {
return false;
}

$authFailures = array('fatal: Authentication failed', 'remote error: Invalid username or password.');
foreach ($authFailures as $authFailure) {
if (strpos($this->process->getErrorOutput(), $authFailure) !== false) {
return true;
}
}

return false;
}

public static function cleanEnv()
{
if (ini_get('safe_mode') && false === strpos(ini_get('safe_mode_allowed_env_vars'), 'GIT_ASKPASS')) {
throw new \RuntimeException('safe_mode is enabled and safe_mode_allowed_env_vars does not contain GIT_ASKPASS, can not set env var. You can disable safe_mode with "-dsafe_mode=0" when running composer');
}


 if (getenv('GIT_ASKPASS') !== 'echo') {
putenv('GIT_ASKPASS=echo');
unset($_SERVER['GIT_ASKPASS']);
}


 if (getenv('GIT_DIR')) {
putenv('GIT_DIR');
unset($_SERVER['GIT_DIR']);
}
if (getenv('GIT_WORK_TREE')) {
putenv('GIT_WORK_TREE');
unset($_SERVER['GIT_WORK_TREE']);
}


 if (getenv('LANGUAGE') !== 'C') {
putenv('LANGUAGE=C');
}


 putenv("DYLD_LIBRARY_PATH");
unset($_SERVER['DYLD_LIBRARY_PATH']);
}

public static function getGitHubDomainsRegex(Config $config)
{
return '('.implode('|', array_map('preg_quote', $config->get('github-domains'))).')';
}

public static function sanitizeUrl($message)
{
return preg_replace_callback('{://(?P<user>[^@]+?):(?P<password>.+?)@}', function ($m) {
if (preg_match('{^[a-f0-9]{12,}$}', $m[1])) {
return '://***:***@';
}

return '://'.$m[1].':***@';
}, $message);
}

private function throwException($message, $url)
{

 clearstatcache();

if (0 !== $this->process->execute('git --version', $ignoredOutput)) {
throw new \RuntimeException(self::sanitizeUrl('Failed to clone '.$url.', git was not found, check that it is installed and in your PATH env.' . "\n\n" . $this->process->getErrorOutput()));
}

throw new \RuntimeException(self::sanitizeUrl($message));
}






public function getVersion()
{
if (isset(self::$version)) {
return self::$version;
}
if (0 !== $this->process->execute('git --version', $output)) {
return;
}
if (preg_match('/^git version (\d+(?:\.\d+)+)/m', $output, $matches)) {
return self::$version = $matches[1];
}
}
}
