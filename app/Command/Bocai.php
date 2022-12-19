<?php

namespace App\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use PHPHtmlParser\Dom;
use support\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Bocai extends Command
{
    protected static $defaultName        = 'Bocai';
    protected static $defaultDescription = 'Bocai';

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
        $output->writeln('Hello Bocai');
//        $this->generateBatData();
        $this->getPageData();
        $this->generateUserData();
        $this->generateGameData();
        return self::SUCCESS;
    }

    function generateBatData()
    {
        ini_set("auto_detect_line_endings", true);

        $csvPath = '/mnt/c/dev/bocai/csv/';
        for ($i = 1; $i < 32; $i++) {
            $filePath = $csvPath . $i . '.csv';
            if (is_file($filePath)) {
                $data = file($filePath);
                foreach ($data as $k => $rows) {
                    $rows     = explode(',', $rows);
                    $income   = $rows['6'];
                    $xe       = $rows['4'];
                    $level    = $rows['5'];
                    $username = $rows['3'];
                    $avatar   = $rows['12'];
                    if ($income == 'null') {
                        continue;
                    }
                    $income = intval($income);
                    if ($k == 0 || $k == 1) {
                        continue;
                    }
                    echo "处理$i: $username : $income \r\n";
                    $uid = str_replace(['https://www.chiphell.com/space-uid-', '.html', ''], '', $rows[14]);
                    $uid = trim($uid);
                    if (!$uid) {
                        echo 'uid not found';
                        die();
                    }
                    // 保存用户基本表
                    $userModel = Db::table('bocai_user')->where('uid', $uid)->first();
                    if (!$userModel) {
                        $res = Db::table('bocai_user')->insert(
                            [
                                'uid'      => $uid,
                                'username' => $username,
                                'xe'       => $xe,
                                'level'    => $level,
                                'avatar'   => $avatar,
                                'xe_add'   => $income,
                                'bat_num'  => 1,
                                'win_num'  => $income > 0 ? 1 : 0,
                            ]
                        );
                        if (!$res) {
                            echo 'user save fail';
                            die();
                        }
                    } else {
                        Db::table('bocai_user')->where('uid', $uid)->update(
                            [
                                'xe'      => $xe,
                                'level'   => $level,
                                'bat_num' => $userModel->bat_num + 1,
                                'xe_add'  => $userModel->xe_add + $income,
                                'win_num' => $income > 0 ? $userModel->win_num + 1 : $userModel->win_num,
                            ]
                        );
                    }
                    // 保存下注
                    $res = Db::table('bocai_bat')->updateOrInsert(
                        [
                            'uid'      => $uid,
                            'game_num' => $i
                        ],
                        [
                            'uid'      => $uid,
                            'game_num' => $i,
                            'income'   => $income
                        ]
                    );
                }
            }
        }
    }

    function generateUserData()
    {
        Db::table('bocai_user')->where('update', 1)->orderBy('id')->chunk(200, function ($models) {
            foreach ($models as $model) {
                $uid = $model->uid;
                echo $model->id . "\r\n";
                $bat_num = Db::table('bocai_bat')->where('uid', $uid)->where('income', '<>', 0)->count();
                $win_num = Db::table('bocai_bat')->where('uid', $uid)->where('income', '>', 0)->count();
                $xe_add  = Db::table('bocai_bat')->where('uid', $uid)->sum('income');
                Db::table('bocai_user')->where('id', $model->id)->update([
                    'bat_num' => $bat_num,
                    'win_num' => $win_num,
                    'xe_add'  => $xe_add,
                    'update'  => 0,
                ]);
            }
        });
    }

    function generateGameData()
    {
        Db::table('bocai_game')->orderBy('id')->chunk(200, function ($models) {
            foreach ($models as $model) {
                $bat_user = Db::table('bocai_bat')->where('game_num', $model->gid)->count();
                $win_user = Db::table('bocai_bat')->where('game_num', $model->gid)->where('income', '>', 0)->count();
                $xe_in    = Db::table('bocai_bat')->where('game_num', $model->gid)->where('income', '<', 0)->sum('income');
                $xe_out   = Db::table('bocai_bat')->where('game_num', $model->gid)->where('income', '>', 0)->sum('income');
                Db::table('bocai_game')->where('id', $model->id)->update([
                    'bat_user' => $bat_user,
                    'win_user' => $win_user,
                    'xe_in'    => $xe_in,
                    'xe_out'   => $xe_out,
                ]);
            }
        });
    }

    function getPageData()
    {
        $url   = 'https://www.chiphell.com/thread-2468586-5-1.html';
//        $this->getPageItemData($url, 32);
        $games = Db::table('bocai_game')->where('gid', '>', 41)->orderBy('id')->get();
        foreach ($games as $game) {
            $this->getPageItemData($game->url, $game->id);
        }
    }

    function getPageItemData($url, $num)
    {
        echo "处理第{$num}场：$url";
        $client  = new Client(['cookies' => true]);
        $headers = [
            'cookie' => '__yjs_duid=1_c01b8e8a02cb7c4a78616b029f0091821668646851923; v2x4_48dd_saltkey=oqaaAARQ; v2x4_48dd_lastvisit=1668643252; _ga=GA1.2.1332075574.1668646853; v2x4_48dd_auth=54adqiCwRw/yTa9omaDvccVHDjxdvs1ajbwNdX1xOUEZFWBY9gcxbmS9u7mLO7qz7J3Lq3tu4tg6fOLg0n+r/yLwz6Y; v2x4_48dd_lastcheckfeed=339292|1668650287; v2x4_48dd_nofavfid=1; v2x4_48dd_smile=5D1; _gid=GA1.2.2882463.1669596664; v2x4_48dd_editormode_e=-1; v2x4_48dd_home_diymode=1; v2x4_48dd_ignore_notice=1; v2x4_48dd_visitedfid=316D22D320D319D321D283D286; v2x4_48dd_ulastactivity=49712Mbbixo0JQD6O0IqpO5dyrohA+XReUeytbQ50VvZM1mJWC4e; v2x4_48dd_sid=L3Elna; v2x4_48dd_lip=111.194.221.244,1670291482; v2x4_48dd_sendmail=1; v2x4_48dd_noticeTitle=1; v2x4_48dd_st_t=339292|1670291547|57818cf2b9c5c8d41e0b9e0c78b9426a; v2x4_48dd_forum_lastvisit=D_286_1669785006D_283_1669792904D_321_1669858004D_320_1670232106D_316_1670291547; v2x4_48dd_viewid=tid_2470144; v2x4_48dd_lastact=1670291561	forum.php	viewthread; v2x4_48dd_st_p=339292|1670291561|bb5ab7d9e25ce6b51845fbdab3d8ba2f',
        ];
        $request = new Request('GET', $url, $headers);
        try {
            $response = $client->send($request, ['timeout' => 2]);
            $body     = $response->getBody();
            $dom      = new Dom;
            $dom->loadStr($body);

            $items = $dom->find('.plhin');
            foreach ($items as $item) {
                $profile = $item->find('.pls');
                if (!$profile) {
                    continue;
                }
                $huifu   = $item->find('.plc');
                if (!$huifu) {
                    continue;
                }
                $uid     = $profile->find('.authi a')->href;
                $uid     = str_replace(['space-uid-', '.html'], '', $uid);
                $name    = $profile->find('.authi a')->text;
                echo "用户：{$name} ID: $uid";
                $level   = $profile->find('.favatar p em a')->text;
                echo "level: $level";
                if (!$level) {
                    $level  = $profile->find('.favatar p em a font')->text;
                }
                $zhuti   = $profile->find('.xg2 th')[0]->find('p a')->text;
                if ($zhuti == NULL) {
                    $zhuti  = $profile->find('.xg2 th')[0]->find('p span')->title;
                }
                echo "zhuti: $zhuti";
                $huitie  = $profile->find('.xg2 th')[1]->find('p a')->text;
                if ($huitie == NULL) {
                    $huitie  = $profile->find('.xg2 th')[1]->find('p span')->title;
                }
                echo "huitie: $huitie";

                $jinghua = $profile->find('.pil dd')[0]->text;
                echo "jinghua: $jinghua";
                $menhu   = $profile->find('.pil dd')[1]->text;
                echo "menhu: $menhu";
                $avatar  = $profile->find('.avatar img')->src;
                $xe      = $profile->find('.pil dd')[2]->text;
                echo "xe: $xe";
                $content = count($huifu->find('.t_fsz .t_f')) == 1 ? $huifu->find('.t_fsz .t_f')->text : '';
                $add     = 0;
                if ($huifu->find('.ratl .xw1') !== NULL && isset($huifu->find('.ratl .xw1')[1])) {
                    if ($huifu->find('.ratl .xw1')[1]) {
                        $add = $huifu->find('.ratl .xw1')[1]->find('.xi1')->text;
                    }
                }
                echo "add: $add \r\n";
                if ($name == 'lionnor') {
                    continue;
                }
                // 更新用户信息
                $user = Db::table('bocai_user')->where('uid', $uid)->first();
                if ($user) {
                    Db::table('bocai_user')->where('uid', $uid)->update([
                        'xe'       => $xe,
                        'level'    => $level,
                        'avatar'   => $avatar,
                        'xe_add'   => $user->xe_add + $add,
                        'bat_num'  => $user->bat_num + 1,
                        'zhuti'    => $zhuti,
                        'huitie'   => $huitie,
                        'jinghua'  => $jinghua,
                        'menhu'    => $menhu,
                        'update'   => 1,
                    ]);
                } else {
                    Db::table('bocai_user')->insert(
                        [
                            'uid'      => $uid,
                            'username' => $name,
                            'xe'       => $xe,
                            'level'    => $level,
                            'avatar'   => $avatar,
                            'xe_add'   => $add,
                            'bat_num'  => 1,
                            'zhuti'    => $zhuti,
                            'huitie'   => $huitie,
                            'jinghua'  => $jinghua,
                            'menhu'    => $menhu,
                            'update'   => 1,
                        ]
                    );
                }
                // 更新下注信息
                Db::table('bocai_bat')->updateOrInsert(
                    [
                        'uid'      => $uid,
                        'game_num' => $num
                    ],
                    [
                        'uid'      => $uid,
                        'game_num' => $num,
                        'income'   => $add,
                        'content'  => $content,
                    ]
                );
            }
            // 获取分页数据
            $nextUrl = $dom->find('.nxt', 0)->href ?? false;
            if ($nextUrl) {
                $nextUrl = 'https://www.chiphell.com/' . $nextUrl;
                $this->getPageItemData($nextUrl, $num);
            }
        } catch (GuzzleException $e) {
            echo '抓取失败：' . $e->getMessage();
        }
    }

}
