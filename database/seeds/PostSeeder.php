<?php

use App\Enums\ImageEnum;
use App\Enums\PostEnum;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medias = [
            PostEnum::MEDIA_TYPE_IMAGE,
            PostEnum::MEDIA_TYPE_VIDEO
        ];

        $images = [
            'https://9mobi.vn/cf/images/2015/03/nkk/anh-dep-1.jpg',
            'https://taimienphi.vn/tmp/cf/aut/anh-dep-2.jpg',
            'https://i.9mobi.vn/cf/images/2015/03/nkk/anh-dep-3.jpg',
            'https://i.9mobi.vn/cf/images/2015/03/nkk/anh-dep-4.jpg',
            'https://i.9mobi.vn/cf/images/2015/03/nkk/anh-dep-5.jpg',
            'https://i.9mobi.vn/cf/images/2015/03/nkk/anh-dep-6.jpg',
            'https://i.9mobi.vn/cf/images/2015/03/nkk/anh-dep-7.jpg',
            'https://i.9mobi.vn/cf/images/2015/03/nkk/anh-dep-8.jpg'
        ];

        $videos = [
            'https://file.tinnhac.com/crop/620x346/2017/04/30/max-changmin-image-m-6380.gif',
            'https://i-ione.vnecdn.net/2019/07/21/ezgif-4-d36e84b3d664-3957-1563718951_m_460x0.gif',
            'https://media.ohay.tv/v1/content/2015/06/tum-2-ohay-tv-1457.gif',
            'https://i.gifer.com/LT9K.gif',
            'https://data.whicdn.com/images/292370466/original.gif',
            'https://image.tienphong.vn/w665/Uploaded/2020/vvh_vicbuuwobu/2020_03_20/chungha_ecfc.gif',
            'https://i.pinimg.com/originals/c5/b3/cd/c5b3cd76178ebf69258a67f142c3f3d5.gif',
            'https://i.pinimg.com/originals/92/7a/e4/927ae4a9cdd5e9a4eaf25632295e787a.gif',
            'https://cellphones.com.vn/sforum/wp-content/uploads/2018/10/ScreenGif.gif',
            'https://media4.giphy.com/media/1YuTvWJlgVpDezvzwo/giphy.gif',
            'https://static1.bestie.vn/Mlog/ImageContent/201712/yawalq6dfdodkb9wpwss-40b9-20171210145952.gif',
            'https://i.pinimg.com/originals/e5/22/d9/e522d9d93b71d320b652383614f9e12b.gif',
            'https://kenh14cdn.com/2017/xsoji-1483692550776-1490781691527.gif',
            'https://files.vivo.vn/86files/upload/images/2017/06/e86dcbf0jw1f8xh0wm8chg208q04v4qp.gif',
            'https://baobinhduong.org.vn/wp-content/uploads/2017/11/anh-dong-vui-nhon-17.gif',
            'https://baobinhduong.org.vn/wp-content/uploads/2017/11/anh-dong-vui-nhon-17.gif'
        ];
        $titles = [
            'Flame of Flame',
            'The Devoted Consort',
            'Emerald in the Planet',
            'Swollen Emperor',
            'The Years of the Boyfriend',
            'Servant of Twilight',
            'The Lost Lords',
            'Unwilling Word',
            'Kiss in the Dying',
            'Servant of Twilight',
            'The Rising Tale',
            'The Final Healer',
            'The Servant of the Game',
            'Gate in the Return',
            'Sliver of Girl',
            'Crying in the Consort',
            'The Years of the Boyfriend',
            'The Rising Tale',
            'The Black Spirit',
            'Crying in the Consort',
            'Sons of Scent',
            'The Prophecy of the Bridge',
            'Invisible Dreamer',
            'The Sorcerer of the Star'
        ];

        foreach ($titles as $title) {
            $mediaId = mt_rand(0, 1);
            $thumbnail = $mediaId == 0 ? $images[mt_rand(0, (count($images)-1))] : $videos[mt_rand(0, (count($videos)-1))];
            $userId = mt_rand(1, 10);
            $post = Post::create([
                'title' => $title,
                'content' => 'Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Ciceros De Finibus Bonorum et Malorum for use in a type specimen book.',
                'location_name' => $title,
                'location_lat' => mt_rand(50, 100),
                'location_long' => mt_rand(50, 100),
                'status' => PostEnum::STATUS_ACTIVE,
                'view' => mt_rand(50, 100),
                'media_type' => $medias[$mediaId],
                'thumbnail' => $thumbnail,
                'thumbnail_width' => mt_rand(250, 350),
                'thumbnail_height' => mt_rand(250, 350),
                'account_type' => mt_rand(0, 1),
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
            if ($mediaId == 0) {
                $max = mt_rand(1, 3);
                $inserts = [];
                for ($i = 0; $i < $max; $i++) {
                    $inserts[] = [
                        'user_id' => $userId,
                        'imageable_type' => ImageEnum::MODEL_POST,
                        'width' => mt_rand(250, 350),
                        'height' => mt_rand(250, 350),
                        'image_path' => $images[mt_rand(0, (count($images)-1))],
                        'created_by' => $userId
                    ];
                }
                $post->images()->createMany($inserts);
            } else {
                $post->videos()->create([
                    'user_id' => $userId,
                    'videoable_type' => ImageEnum::MODEL_POST,
                    'width' => mt_rand(250, 350),
                    'height' => mt_rand(250, 350),
                    'video_path' => 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599727812/video-1599710219_wJouQGSC.mp4',
                    'created_by' => $userId
                ]);
            }
        }
    }
}
