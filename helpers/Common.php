<?php
/**
* Return an array of posts with the user id in the $list
*/
namespace MVC\Helpers;


use MVC\Helpers\Constants;
use MVC\Core\Application;


class Common
{
    function get_posts_list_by_user_id(\PDO $pdo, array $list, int $limit, int $offset): array
    {
        $placeholder = str_repeat('?,', count($list) - 1) . '?';

        $sql = "SELECT * 
                FROM $POST_TABLE 
                WHERE authorID in ($placeholder) LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit);
        $stmt->bindValue(':offset', $offset);
        $stmt->execute($list);

        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Return an array of posts with the module id in the $list
    */
    function get_posts_list_by_module_id(\PDO $pdo, array $list, int $limit, int $offset): array
    {
        $placeholder = str_repeat('?,', count($list) - 1) . '?';

        $sql = "SELECT * 
                FROM $POST_TABLE
                WHERE moduleID in ($placeholder) LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit);
        $stmt->bindValue(':offset', $offset);
        $stmt->execute($list);

        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Return an array of comments with the post id in the $list
    */
    function get_comments_by_post_id(\PDO $pdo, array $list, int $limit, int $offset): array
    {
        $placeholder = str_repeat('?,', count($list) - 1) . '?';

        $sql = "SELECT * 
                FROM $POST_COMMENT_TABLE 
                WHERE postID in ($placeholder) LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit);
        $stmt->bindValue(':offset', $offset);
        $stmt->execute($list);

        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Find posts by thread based on a pattern
    */
    function find_posts_by_title(\PDO $pdo, string $keyword): array
    {
        $pattern = '%' . $keyword . '%';

        $sql = 'SELECT * 
            FROM $POST_TABLE 
            WHERE thread LIKE :pattern';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':pattern' => $pattern]);

        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function dataGet($arr, $key) {

        if (!is_array($arr) || empty($key)) {
            return null;
        }

        $keysArr = explode(".", $key);
        $searchedKey = $keysArr[count($keysArr) - 1];

        $i = 0;

        if(array_key_exists($keysArr[$i], $arr)) {

            $nextArr = $arr[$keysArr[$i]];

            while($i < count($keysArr)) {

                $i++;

                if(!array_key_exists($keysArr[$i], $nextArr)) break;

                if($keysArr[$i] === $searchedKey) return $nextArr[$keysArr[$i]];

                $nextArr = $nextArr[$keysArr[$i]];
            }
        }

        return null;
    }
    
    public static function upload_file()
    {
        if (isset($_FILES['image'])){
            //there is 1 file to be uploaded
            $folder = '/uploads/';		//You MUST create a folder in your SERVER Directory
            $tmpfile = $_FILES['image']['tmp_name'];
            $filename = basename($_FILES['image']['name']);
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . $folder;	//the file inside your web root folder
            $target_file = $target_dir . $filename;

            $result_upload_file = move_uploaded_file($tmpfile, $target_file);
            if($result_upload_file) return $folder.$filename;
        }
        return;
    }
}
?>