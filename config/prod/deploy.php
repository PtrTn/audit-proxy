<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            ->server('root@peterton.nl')
            ->deployDir('/var/www/audit-proxy/')
            ->repositoryUrl('git@github.com:PtrTn/audit-proxy.git')
            ->repositoryBranch('master')
            ->composerInstallFlags('--prefer-dist --no-interaction')
            ->sharedFilesAndDirs([
                './.env',
                './.env.local',
                ])
            ->resetOpCacheFor('http://peterton.nl/')
        ;
    }
    public function beforeStartingDeploy()
    {
        $this->runLocal('bin/phpunit');
    }

    public function beforePreparing()
    {
        // Add a fresh .env file to the shared folder from Git if it does not exist
        $this->runRemote(sprintf('cp {{ deploy_dir }}/repo/.env {{ deploy_dir }}/shared/.env 2>/dev/null'));
        $this->runRemote(sprintf('cp -n {{ deploy_dir }}/repo/.env {{ deploy_dir }}/shared/.env.local 2>/dev/null'));
    }
};
