<?php











namespace Composer\Repository\Vcs;

use Composer\Cache;
use Composer\Config;
use Composer\Downloader\TransportException;
use Composer\Json\JsonFile;
use Composer\IO\IOInterface;
use Composer\Util\Bitbucket;




class GitBitbucketDriver extends VcsDriver implements VcsDriverInterface
{



protected $cache;
protected $owner;
protected $repository;
protected $tags;
protected $branches;
protected $rootIdentifier;
protected $infoCache = array();
private $hasIssues;



private $gitDriver;




public function initialize()
{
preg_match('#^https?://bitbucket\.org/([^/]+)/(.+?)\.git$#', $this->url, $match);
$this->owner = $match[1];
$this->repository = $match[2];
$this->originUrl = 'bitbucket.org';
$this->cache = new Cache($this->io, $this->config->get('cache-repo-dir').'/'.$this->originUrl.'/'.$this->owner.'/'.$this->repository);
}




public function getRootIdentifier()
{
if ($this->gitDriver) {
return $this->gitDriver->getRootIdentifier();
}

if (null === $this->rootIdentifier) {
$resource = $this->getScheme() . '://api.bitbucket.org/1.0/repositories/'.$this->owner.'/'.$this->repository;
$repoData = JsonFile::parseJson($this->getContentsWithOAuthCredentials($resource, true), $resource);
$this->hasIssues = !empty($repoData['has_issues']);
$this->rootIdentifier = !empty($repoData['main_branch']) ? $repoData['main_branch'] : 'master';
}

return $this->rootIdentifier;
}




public function getUrl()
{
if ($this->gitDriver) {
return $this->gitDriver->getUrl();
}

return 'https://' . $this->originUrl . '/'.$this->owner.'/'.$this->repository.'.git';
}




public function getSource($identifier)
{
if ($this->gitDriver) {
return $this->gitDriver->getSource($identifier);
}

return array('type' => 'git', 'url' => $this->getUrl(), 'reference' => $identifier);
}




public function getDist($identifier)
{
$url = $this->getScheme() . '://bitbucket.org/'.$this->owner.'/'.$this->repository.'/get/'.$identifier.'.zip';

return array('type' => 'zip', 'url' => $url, 'reference' => $identifier, 'shasum' => '');
}




public function getComposerInformation($identifier)
{
if ($this->gitDriver) {
return $this->gitDriver->getComposerInformation($identifier);
}

if (preg_match('{[a-f0-9]{40}}i', $identifier) && $res = $this->cache->read($identifier)) {
$this->infoCache[$identifier] = JsonFile::parseJson($res);
}

if (!isset($this->infoCache[$identifier])) {
$resource = $this->getScheme() . '://api.bitbucket.org/1.0/repositories/'.$this->owner.'/'.$this->repository.'/src/'.$identifier.'/composer.json';
$file = JsonFile::parseJson($this->getContentsWithOAuthCredentials($resource), $resource);
if (!is_array($file) || ! array_key_exists('data', $file)) {
return array();
}

$composer = JsonFile::parseJson($file['data'], $resource);

if (empty($composer['time'])) {
$resource = $this->getScheme() . '://api.bitbucket.org/1.0/repositories/'.$this->owner.'/'.$this->repository.'/changesets/'.$identifier;
$changeset = JsonFile::parseJson($this->getContentsWithOAuthCredentials($resource), $resource);
$composer['time'] = $changeset['timestamp'];
}
if (!isset($composer['support']['source'])) {
$label = array_search($identifier, $this->getTags()) ?: array_search($identifier, $this->getBranches()) ?: $identifier;

if (array_key_exists($label, $tags = $this->getTags())) {
$hash = $tags[$label];
} elseif (array_key_exists($label, $branches = $this->getBranches())) {
$hash = $branches[$label];
}

if (! isset($hash)) {
$composer['support']['source'] = sprintf('https://%s/%s/%s/src', $this->originUrl, $this->owner, $this->repository);
} else {
$composer['support']['source'] = sprintf(
'https://%s/%s/%s/src/%s/?at=%s',
$this->originUrl,
$this->owner,
$this->repository,
$hash,
$label
);
}
}
if (!isset($composer['support']['issues']) && $this->hasIssues) {
$composer['support']['issues'] = sprintf('https://%s/%s/%s/issues', $this->originUrl, $this->owner, $this->repository);
}

if (preg_match('{[a-f0-9]{40}}i', $identifier)) {
$this->cache->write($identifier, json_encode($composer));
}

$this->infoCache[$identifier] = $composer;
}

return $this->infoCache[$identifier];
}




public function getTags()
{
if ($this->gitDriver) {
return $this->gitDriver->getTags();
}

if (null === $this->tags) {
$resource = $this->getScheme() . '://api.bitbucket.org/1.0/repositories/'.$this->owner.'/'.$this->repository.'/tags';
$tagsData = JsonFile::parseJson($this->getContentsWithOAuthCredentials($resource), $resource);
$this->tags = array();
foreach ($tagsData as $tag => $data) {
$this->tags[$tag] = $data['raw_node'];
}
}

return $this->tags;
}




public function getBranches()
{
if ($this->gitDriver) {
return $this->gitDriver->getBranches();
}

if (null === $this->branches) {
$resource = $this->getScheme() . '://api.bitbucket.org/1.0/repositories/'.$this->owner.'/'.$this->repository.'/branches';
$branchData = JsonFile::parseJson($this->getContentsWithOAuthCredentials($resource), $resource);
$this->branches = array();
foreach ($branchData as $branch => $data) {
$this->branches[$branch] = $data['raw_node'];
}
}

return $this->branches;
}




public static function supports(IOInterface $io, Config $config, $url, $deep = false)
{
if (!preg_match('#^https?://bitbucket\.org/([^/]+)/(.+?)\.git$#', $url)) {
return false;
}

if (!extension_loaded('openssl')) {
$io->writeError('Skipping Bitbucket git driver for '.$url.' because the OpenSSL PHP extension is missing.', true, IOInterface::VERBOSE);

return false;
}

return true;
}

protected function attemptCloneFallback()
{
try {
$this->setupGitDriver($this->generateSshUrl());

return;
} catch (\RuntimeException $e) {
$this->gitDriver = null;

$this->io->writeError('<error>Failed to clone the '.$this->generateSshUrl().' repository, try running in interactive mode so that you can enter your Bitbucket OAuth consumer credentials</error>');
throw $e;
}
}






private function generateSshUrl()
{
return 'git@' . $this->originUrl . ':' . $this->owner.'/'.$this->repository.'.git';
}









protected function getContentsWithOAuthCredentials($url, $fetchingRepoData = false)
{
try {
return parent::getContents($url);
} catch (TransportException $e) {
$bitbucketUtil = new Bitbucket($this->io, $this->config, $this->process, $this->remoteFilesystem);

switch ($e->getCode()) {
case 403:
if (!$this->io->hasAuthentication($this->originUrl) && $bitbucketUtil->authorizeOAuth($this->originUrl)) {
return parent::getContents($url);
}

if (!$this->io->isInteractive() && $fetchingRepoData) {
return $this->attemptCloneFallback();
}

throw $e;

default:
throw $e;
}
}
}




private function setupGitDriver($url)
{
$this->gitDriver = new GitDriver(
array('url' => $url),
$this->io,
$this->config,
$this->process,
$this->remoteFilesystem
);
$this->gitDriver->initialize();
}
}
