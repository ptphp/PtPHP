<?php

use Phinx\Seed\AbstractSeed;

class Mission extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
//        $data = array();
//        for($i = 1;$i < 5;$i++){
//            $data[] = array(
//                "title"=>"title".$i,
//                "desc"=>"desc",
//                "addtime"=>date("Y-m-d H:i:s"),
//                "tag"=>1,
//                "type"=>1,
//                "status"=>1,
//                "thumb"=>"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160411160942/69745/T16xRhXkxbXXXXXXXX.svg",
//            );
//        }
//        $posts = $this->table('mission');
//        $posts->insert($data)
//            ->save();

        $sql =<<<sql
INSERT INTO `ldt_mission` (`id`, `title`, `desc`, `add_time`, `com_name`, `tag`, `type`, `thumb`, `btn_name`, `sno`, `platform`, `platform_name`, `wechat_account`, `remain_times`, `award`, `start_time`, `end_time`, `tips`, `example`, `status`, `is_rec`, `note`, `ord`, `join_nums`, `finish_nums`)
VALUES
	(1, 'title1', 'desc1', '2016-04-13 14:02:03', '', 1, 6, 'http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160411160942/69745/T16xRhXkxbXXXXXXXX.svg', '', '', NULL, '', '', NULL, 50.0, NULL, NULL, '<p>任务说明</p><p>1 .子任务1</p><p>2.子任务1</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220444/91014/nopic.png\" width=\"200\" height=\"200\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">&nbsp;</span><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p>', '<p>示例文字:</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220444/91014/nopic.png\" width=\"200\" height=\"200\">&nbsp;<img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p><p><br></p><p>1 .子任务1</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p><p>2.子任务1</p><p>任务说明</p><p>1 .子任务1</p><p>2.子任务1</p>', 1, 0, '', 0, 0, 0),
	(2, 'title2', 'desc2', '2016-04-13 14:02:03', '', 1, 6, 'http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160411160942/69745/T16xRhXkxbXXXXXXXX.svg', '', '', NULL, '', '', NULL, 50.0, NULL, NULL, '<p>任务说明</p><p>1 .子任务1</p><p>2.子任务1</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220444/91014/nopic.png\" width=\"200\" height=\"200\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">&nbsp;</span><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p>', '<p>示例文字:</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220444/91014/nopic.png\" width=\"200\" height=\"200\">&nbsp;<img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p><p><br></p><p>1 .子任务1</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p><p>2.子任务1</p><p>任务说明</p><p>1 .子任务1</p><p>2.子任务1</p>', 1, 0, '', 0, 0, 0),
	(3, 'title3', 'desc3', '2016-04-13 14:02:03', '', 1, 6, 'http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160411160942/69745/T16xRhXkxbXXXXXXXX.svg', '', '', NULL, '', '', NULL, 50.0, NULL, NULL, '<p>任务说明</p><p>1 .子任务1</p><p>2.子任务1</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220444/91014/nopic.png\" width=\"200\" height=\"200\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">&nbsp;</span><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p>', '<p>示例文字:</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220444/91014/nopic.png\" width=\"200\" height=\"200\">&nbsp;<img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p><p><br></p><p>1 .子任务1</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p><p>2.子任务1</p><p>任务说明</p><p>1 .子任务1</p><p>2.子任务1</p>', 1, 0, '', 0, 0, 0),
	(4, 'title4', 'desc4', '2016-04-13 14:02:03', '', 1, 6, 'http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160411160942/69745/T16xRhXkxbXXXXXXXX.svg', '', '', NULL, '', '', NULL, 50.0, NULL, NULL, '<p>任务说明</p><p>1 .子任务1</p><p>2.子任务1</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220444/91014/nopic.png\" width=\"200\" height=\"200\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">&nbsp;</span><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p>', '<p>示例文字:</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220444/91014/nopic.png\" width=\"200\" height=\"200\">&nbsp;<img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p><p><br></p><p>1 .子任务1</p><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220452/24493/nopic.png\" width=\"200\" height=\"200\"><br></p><p>2.子任务1</p><p>任务说明</p><p>1 .子任务1</p><p>2.子任务1</p>', 1, 1, '', 0, 0, 0);

INSERT INTO `ldt_mission_task` (`id`, `award`, `mission_id`, `title`, `key`, `example`)
VALUES
	(10, 11.0, 3, '子任务1', 1, '<p><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></p><ol><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></span></span></li></ol><p><br></p><hr><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220757/27310/nopic.png\" width=\"200\" height=\"200\"><br></p>'),
	(11, 22.0, 3, '子任务2', 2, NULL),
	(12, 33.0, 3, '子任务3', 3, NULL);

INSERT INTO `ldt_mission_task` (`id`, `award`, `mission_id`, `title`, `key`, `example`)
VALUES
	(1, 11.0, 4, '子任务1', 1, '<p><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></p><ol><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></span></span></li></ol><p><br></p><hr><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220757/27310/nopic.png\" width=\"200\" height=\"200\"><br></p>'),
	(2, 22.0, 4, '子任务2', 2, NULL),
	(3, 33.0, 4, '子任务3', 3, NULL);
INSERT INTO `ldt_mission_task` (`id`, `award`, `mission_id`, `title`, `key`, `example`)
VALUES
	(4, 11.0, 2, '子任务1', 1, '<p><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></p><ol><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></span></span></li></ol><p><br></p><hr><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220757/27310/nopic.png\" width=\"200\" height=\"200\"><br></p>'),
	(5, 22.0, 2, '子任务2', 2, NULL),
	(6, 33.0, 2, '子任务3', 3, NULL);
INSERT INTO `ldt_mission_task` (`id`, `award`, `mission_id`, `title`, `key`, `example`)
VALUES
	(7, 11.0, 1, '子任务1', 1, '<p><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></p><ol><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></span></li><li><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\"><span style=\"color: rgb(51, 51, 51); font-size: 16px;\">攻略</span><br></span></span></span></span></li></ol><p><br></p><hr><p><img alt=\"nopic.png\" src=\"http://7xn6n8.com1.z0.glb.clouddn.com/upload/img/20160413220757/27310/nopic.png\" width=\"200\" height=\"200\"><br></p>'),
	(8, 22.0, 1, '子任务2', 2, NULL),
	(9, 33.0, 1, '子任务3', 3, NULL);
sql;
        $this->execute($sql);
    }
}
