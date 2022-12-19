<?php

namespace App\Command;

use support\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class BocaiData extends Command
{
    protected static $defaultName        = 'BocaiData';
    protected static $defaultDescription = 'BocaiData';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeln('Hello bocaiData');

        $this->generateBankData();
        $this->generateUserData();
        return self::SUCCESS;
    }

    function generateUserData()
    {
        $data = [];
        Db::table('bocai_user')->orderBy('uid')->chunk(200, function ($models) use (&$data) {
            foreach ($models as $model) {
                $type = 0;
                if ($model->level == '小恶魔' && $model->jifen_start < 20000) {
                    $type = 1;
                } elseif ($model->xe_add >= 100000) {
                    $type = 2;
                } elseif ($model->xe_add <= -100000) {
                    $type = 3;
                } elseif ($model->xe_add > -1000 && $model->xe_add < 1000) {
                    $type = 4;
                } elseif ($model->level == '亡灵') {
                    $type = 5;
                } elseif ($model->bat_num >= 35) {
                    $type = 6;
                } elseif ($model->bat_num <= 5) {
                    $type = 7;
                } elseif (($model->win_num / $model->bat_num) > 0.7) {
                    $type = 8;
                } elseif (($model->win_num / $model->bat_num) < 0.3) {
                    $type = 9;
                }

                $data[$model->uid] = [$model->xe, $model->xe_add, $model->bat_num, $model->win_num, [], $type];
            }
        });
        Db::table('bocai_bat')->orderBy('uid')->orderBy('game_num')->chunk(200, function ($models) use (&$data) {
            foreach ($models as $model) {
                if (isset($data[$model->uid])) {
                    $data[$model->uid][4][] = [
                        $model->game_num,
                        $model->income,
                        str_replace('&nbsp;', '', $model->content),
                    ];

                }
            }
        });
//        foreach ($data as $uid => $item) {
//            echo "save $uid \r\n";
//            file_put_contents("/mnt/c/dev/bocai/game/json/{$uid}.json", json_encode($item));
//        }
        file_put_contents('/mnt/c/dev/bocai/game/user.json', json_encode($data));
        echo 'success';
    }


    function generateBankData()
    {
        $gameModels     = Db::table('bocai_game')->get(['id', 'title', 'url', 'bat_user', 'win_user', 'xe_in', 'xe_out'])->keyBy('id');
        $gameDetailData = [
            'count'   => count($gameModels), // 比赛场数
            'items'   => $gameModels->toArray(),
            'max_in'  => [
                'id'  => 0,
                'num' => 0,
            ], // 单独最大支出
            'max_out' => [
                'id'  => 0,
                'num' => 0,
            ], // 单局最大收入
            'in'      => 0, // 累计加分
            'out'     => 0, // 累计扣分
            'set'     => 0, // 结算
        ];
        foreach ($gameModels as $gameModel) {
            // 庄家加分
            $gameDetailData['out'] += $gameModel->xe_out;
            if ($gameModel->xe_out + $gameModel->xe_in > $gameDetailData['max_out']['num']) {
                $gameDetailData['max_out']['id']  = $gameModel->id;
                $gameDetailData['max_out']['num'] = $gameModel->xe_out + $gameModel->xe_in;
            }
            // 庄家扣分
            $gameDetailData['in'] += $gameModel->xe_in;
            if ($gameModel->xe_out + $gameModel->xe_in < $gameDetailData['max_in']['num']) {
                $gameDetailData['max_in']['id']  = $gameModel->id;
                $gameDetailData['max_in']['num'] = $gameModel->xe_out + $gameModel->xe_in;
            }
            $gameDetailData['set'] += $gameModel->xe_out + $gameModel->xe_in;
        }
        $gameDetailData['max_in']['num']  = round($gameDetailData['max_in']['num'] / 10000);
        $gameDetailData['max_out']['num'] = round($gameDetailData['max_out']['num'] / 10000);
        $gameDetailData['in']             = round($gameDetailData['in'] / 10000);
        $gameDetailData['out']            = round($gameDetailData['out'] / 10000);
        $gameDetailData['set']            = round($gameDetailData['set'] / 10000);
        $batData                          = [
            'bat_user' => Db::table('bocai_bat')->distinct('uid')->count(),
            'bat_num'  => Db::table('bocai_bat')->count(),
        ];
        // 积分榜
        $jifenDesc     = Db::table('bocai_user')->select(DB::raw('uid, username, bat_num, win_num, win_num*2-(bat_num-win_num) as a, win_num/bat_num as b'))
                           ->orderBy('a', 'desc')
                           ->orderBy('bat_num', 'desc')
                           ->limit(15)
                           ->get();
        $jifenDescData = [];
        foreach ($jifenDesc as $v) {
            $jifenDescData[] = [
                'uid'     => $v->uid,
                'name'    => $v->username,
                'bat_num' => $v->bat_num,
                'win_num' => $v->win_num,
                'a'       => $v->a,
                'b'       => $v->b,
            ];
        }

        $jifenAsc     = Db::table('bocai_user')->select(DB::raw('uid, username, bat_num, win_num, win_num*2-(bat_num-win_num) as a, win_num/bat_num as b'))
                          ->orderBy('a', 'asc')
                          ->orderBy('bat_num', 'asc')
                          ->limit(15)
                          ->get();
        $jifenAscData = [];
        foreach ($jifenAsc as $v) {
            $jifenAscData[] = [
                'uid'     => $v->uid,
                'name'    => $v->username,
                'bat_num' => $v->bat_num,
                'win_num' => $v->win_num,
                'a'       => $v->a,
                'b'       => $v->b,
            ];
        }
        // 累计收入榜
        $inBank     = Db::table('bocai_user')->orderBy('xe_add', 'DESC')->limit(15)->get();
        $inBankData = [];
        foreach ($inBank as $v) {
            $inBankData[] = [
                'uid'     => $v->uid,
                'name'    => $v->username,
                'bat_num' => $v->bat_num,
                'win_num' => $v->win_num,
                'xe_add'  => $v->xe_add,
            ];
        }
        // 累计亏损
        $outBank     = Db::table('bocai_user')->orderBy('xe_add', 'ASC')->limit(15)->get();
        $outBankData = [];
        foreach ($outBank as $v) {
            $outBankData[] = [
                'uid'     => $v->uid,
                'name'    => $v->username,
                'bat_num' => $v->bat_num,
                'win_num' => $v->win_num,
                'xe_add'  => $v->xe_add,
            ];
        }
        // 财富飙升榜
        $growBank = Db::table('bocai_user')->select(DB::raw('uid, username, bat_num, win_num, xe, xe_add, xe-xe_add,xe_add/(xe-xe_add) as a'))
                      ->where('xe_add', '>', 0)
                      ->orderBy('a', 'DESC')
                      ->limit(15)->get();

        $growBankData = [];
        foreach ($growBank as $v) {
            $growBankData[] = [
                'uid'     => $v->uid,
                'name'    => $v->username,
                'bat_num' => $v->bat_num,
                'win_num' => $v->win_num,
                'xe'      => $v->xe,
                'xe_add'  => $v->xe_add,
                'a'       => $v->a,
            ];
        }
        // 参与次数榜
        $numBank     = Db::table('bocai_user')->orderBy('bat_num', 'DESC')->limit(15)->get();
        $numBankData = [];
        foreach ($numBank as $v) {
            $numBankData[] = [
                'uid'     => $v->uid,
                'name'    => $v->username,
                'bat_num' => $v->bat_num,
                'win_num' => $v->win_num,
                'xe_add'  => $v->xe_add,
            ];
        }
        // 用户等级分布
        $levelList = Db::table('bocai_user')->select(DB::raw('`level`, COUNT(*) as a'))->groupBy('level')->get();
        $levelData = [];
        foreach ($levelList as $v) {
            if (in_array($v->level, ['禁止访问', '禁止发言', '认证商家', '亡灵苦工', '见习-迪亚波罗', '圣魔使-迪亚波罗'])) {
                continue;
            }
            $levelData[] = [
                'name'  => $v->level,
                'value' => $v->a,
            ];
        }
        $winData = [
            [
                'name'  => '盈利人数',
                'value' => Db::table('bocai_user')->where('bat_num', '>', 0)->where('xe_add', '>', 0)->count(),
            ],
            [
                'name'  => '亏损人数',
                'value' => Db::table('bocai_user')->where('bat_num', '>', 0)->where('xe_add', '<', 0)->count(),
            ], [
                'name'  => '持平人数',
                'value' => Db::table('bocai_user')->where('bat_num', '>', 0)->where('xe_add', 0)->count(),
            ],

        ];

        $jsonData = [
            'gameData'      => $gameDetailData,
            'batData'       => $batData,
            'jifenDescData' => $jifenDescData,
            'jifenAscData'  => $jifenAscData,
            'inBankData'    => $inBankData,
            'outBankData'   => $outBankData,
            'growBankData'  => $growBankData,
            'numBankData'   => $numBankData,
            'levelData'     => $levelData,
            'winData'       => $winData,
        ];

        file_put_contents('/mnt/c/dev/bocai/game/data.json', json_encode($jsonData));
    }

}
