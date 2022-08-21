<?php

namespace App\Command;

use App\Admin\Model\UserLogin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Ip2region extends Command
{
    protected static $defaultName = 'ip2region';
    protected static $defaultDescription = 'ip2region';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('ip', InputArgument::OPTIONAL, 'ip 转换为具体地址');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        UserLogin::where('login_location', '')->chunk(200, function ($models) {
            foreach ($models as $model) {
                if ($model->ipaddr) {
                    $region = ip2region($model->ipaddr);
                    if ($region) {
                        $model->login_location = $region;
                        $model->save();
                    }
                }
            }
        });

        return self::SUCCESS;
    }

}
