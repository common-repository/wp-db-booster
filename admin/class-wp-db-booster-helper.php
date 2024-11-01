<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wpmaniax.com
 * @since      1.0.0
 *
 * @package    Wp_Db_Booster
 * @subpackage Wp_Db_Booster/admin
 */

/**
 * The helper-specific functionality of the plugin.
 *
 *
 * @package    Wp_Db_Booster
 * @subpackage Wp_Db_Booster/admin
 * @author     WPManiax <plugins@wpmaniax.com>
 */
class Wp_Db_Booster_Helper
{
    const S_NA = "N/A";
    const S_OK = "OK";
    const S_BAD = "BAD";
    const S_WARN = "WARNING";

    public function get_tables()
    {
        global $wpdb;
        $arr_tables = array();
        $tables = $wpdb->get_results("SHOW TABLES");
        foreach ($tables as $table) {
            foreach ($table as $t) {
                array_push($arr_tables, $t);
            }
        }
        return $arr_tables;
    }

    public function filesize_formatted($size)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }

    public function compare($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? 1 : -1;
    }

    public function test_password($password)
    {
        if (strlen($password) == 0) {
            return 1;
        }

        $strength = 0;

        /*** get the length of the password ***/
        $length = strlen($password);

        /*** check if password is not all lower case ***/
        if (strtolower($password) != $password) {
            $strength += 1;
        }

        /*** check if password is not all upper case ***/
        if (strtoupper($password) == $password) {
            $strength += 1;
        }

        /*** check string length is 8 -15 chars ***/
        if ($length >= 8 && $length <= 15) {
            $strength += 1;
        }

        /*** check if lenth is 16 - 35 chars ***/
        if ($length >= 16 && $length <= 35) {
            $strength += 2;
        }

        /*** check if length greater than 35 chars ***/
        if ($length > 35) {
            $strength += 3;
        }

        /*** get the numbers in the password ***/
        preg_match_all('/[0-9]/', $password, $numbers);
        $strength += count($numbers[0]);

        /*** check for special chars ***/
        preg_match_all('/[|!@#$%&*\/=?,;.:\-_+~^\\\]/', $password, $specialchars);
        $strength += sizeof($specialchars[0]);

        /*** get the number of unique chars ***/
        $chars = str_split($password);
        $num_unique_chars = sizeof(array_unique($chars));
        $strength += $num_unique_chars * 2;

        /*** strength is a number 1-10; ***/
        $strength = $strength > 99 ? 99 : $strength;
        $strength = floor($strength / 10 + 1);

        return $strength;
    }

    private function countContain($strPassword, $strCheck)
    {
        // Declare variables
        $nCount = 0;

        for ($i = 0; $i < strlen($strPassword); $i++) {
            if (strpos($strCheck, substr($strPassword, $i, 1)) !== false) {
                $nCount++;
            }
        }

        return $nCount;
    }

    public function check_password($strPassword)
    {

        $m_strUpperCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $m_strLowerCase = "abcdefghijklmnopqrstuvwxyz";
        $m_strNumber = "0123456789";
        $m_strCharacters = "!@#$%^&*?_~";
        // Reset combination count
        $nScore = 0;

        // Password length
        // -- Less than 4 characters
        if (strlen($strPassword) < 5) {
            $nScore += 5;
        } // -- 5 to 7 characters
        else if (strlen($strPassword) > 4 && strlen($strPassword) < 8) {
            $nScore += 10;
        } // -- 8 or more
        else if (strlen($strPassword) > 7) {
            $nScore += 25;
        }

        // Letters
        $nUpperCount = $this->countContain($strPassword, $m_strUpperCase);
        $nLowerCount = $this->countContain($strPassword, $m_strLowerCase);
        $nLowerUpperCount = $nUpperCount + $nLowerCount;
        // -- Letters are all lower case
        if ($nUpperCount == 0 && $nLowerCount != 0) {
            $nScore += 10;
        } // -- Letters are upper case and lower case
        else if ($nUpperCount != 0 && $nLowerCount != 0) {
            $nScore += 20;
        }

        // Numbers
        $nNumberCount = $this->countContain($strPassword, $m_strNumber);
        // -- 1 number
        if ($nNumberCount == 1) {
            $nScore += 10;
        }
        // -- 3 or more numbers
        if ($nNumberCount >= 3) {
            $nScore += 20;
        }

        // Characters
        $nCharacterCount = $this->countContain($strPassword, $m_strCharacters);
        // -- 1 character
        if ($nCharacterCount == 1) {
            $nScore += 10;
        }
        // -- More than 1 character
        if ($nCharacterCount > 1) {
            $nScore += 25;
        }

        // Bonus
        // -- Letters and numbers
        if ($nNumberCount != 0 && $nLowerUpperCount != 0) {
            $nScore += 2;
        }
        // -- Letters, numbers, and characters
        if ($nNumberCount != 0 && $nLowerUpperCount != 0 && $nCharacterCount != 0) {
            $nScore += 3;
        }
        // -- Mixed case letters, numbers, and characters
        if ($nNumberCount != 0 && $nUpperCount != 0 && $nLowerCount != 0 && $nCharacterCount != 0) {
            $nScore += 5;
        }


        return $nScore;
    }

    public function check_password_text($nScore)
    {
        if ($nScore >= 80) {
            $strText = __("Very Strong", 'wp-db-booster');

        } // -- Strong
        else if ($nScore >= 60) {
            $strText = __("Strong", 'wp-db-booster');
        } // -- Average
        else if ($nScore >= 40) {
            $strText = __("Average", 'wp-db-booster');
        } // -- Weak
        else if ($nScore >= 20) {
            $strText = __("Weak", 'wp-db-booster');
        } // -- Very Weak
        else {
            $strText = __("Very Weak", 'wp-db-booster');
        }
        return $strText;
    }

    public function common_pass_test()
    {
        $arr = file(plugin_dir_path(dirname(__FILE__)) . 'admin/dictionary_full.txt', FILE_IGNORE_NEW_LINES);
        //echo "<pre>"; print_r($arr); echo "</pre>";
        /*if (in_array(DB_PASSWORD, $arr))
            return "<i class=\"fa fa-times-circle\" style=\"color:#d02c21\"></i> " . __("Failed", 'wp-db-booster');
        else
            return "<i class=\"fa fa-check-circle\" style=\"color:#579e34\"></i> " . __("Passed", 'wp-db-booster');*/
        $status = (in_array(DB_PASSWORD, $arr)) ? self::S_BAD : self::S_OK;
        return self::r($status);

    }

    public function get_cleanup_info($type)
    {
        global $wpdb;
        $sql = '';
        $var = 0;
        switch ($type) {
            case "autodraft":
                $sql = "SELECT COUNT(*) FROM `$wpdb->posts` WHERE post_status = 'auto-draft'";
                break;

            case "trash_post":
                $sql = "SELECT COUNT(*) FROM `$wpdb->posts` WHERE post_status = 'trash'";
                break;

            case "transient_feed":
                $sql = "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
                break;

            case "draft":
                $sql = $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = %s AND (post_type = %s OR post_type = %s)", "draft", "page", "post");
                break;

            case "orphan_comment_meta":
                $sql = "SELECT COUNT(*) FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
                break;

            case "duplicated_comment_meta":
                $cols = $wpdb->get_col("SELECT COUNT(*) AS count FROM $wpdb->commentmeta GROUP BY comment_id, meta_key, meta_value HAVING count > 1");
                if (is_array($cols)) {
                    foreach ($cols as $col) {
                        $var += $col - 1;
                    }
                }
                break;

            case "orphan_post_meta":
                $sql = "SELECT COUNT(*) FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
                break;

            case "duplicated_post_meta":
                $cols = $wpdb->get_col("SELECT COUNT(meta_id) AS count FROM $wpdb->postmeta GROUP BY post_id, meta_key, meta_value HAVING count > 1");
                if (is_array($cols)) {
                    foreach ($cols as $col) {
                        $var += $col - 1;
                    }
                }
                break;

            case 'orphan_user_meta':
                $sql = "SELECT COUNT(umeta_id) FROM $wpdb->usermeta WHERE user_id NOT IN (SELECT ID FROM $wpdb->users)";
                break;

            case "duplicated_user_meta":
                $cols = $wpdb->get_col($wpdb->prepare("SELECT COUNT(umeta_id) AS count FROM $wpdb->usermeta GROUP BY user_id, meta_key, meta_value HAVING count > %d", 1));
                if (is_array($cols)) {
                    foreach ($cols as $col) {
                        $var += $col - 1;
                    }
                }
                break;
            case 'orphan_term_meta':
                $sql = "SELECT COUNT(meta_id) FROM $wpdb->termmeta WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)";
                break;

            case 'duplicated_term_meta':
                $cols = $wpdb->get_col($wpdb->prepare("SELECT COUNT(meta_id) AS count FROM $wpdb->termmeta GROUP BY term_id, meta_key, meta_value HAVING count > %d", 1));
                if (is_array($cols)) {
                    foreach ($cols as $col) {
                        $var += $col - 1;
                    }
                }
                break;
            case "orphan_term_relationships":
                $sql = "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
                break;

            case 'unused_tags':
                $var = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->terms WHERE term_id IN (SELECT term_id FROM $wpdb->term_taxonomy WHERE COUNT = 0)");
                $var += $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)");
                $var += $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM $wpdb->term_taxonomy)");
                break;

            case "revisions":
                $sql = "SELECT COUNT(*) FROM `$wpdb->posts` WHERE post_type = 'revision'";
                break;

            case "pingbacks":
                $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_type='pingback'";
                break;

            case "transient_options":
                $sql = "SELECT COUNT(*) FROM `$wpdb->options` WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'";
                break;

            case "trackbacks":
                $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_type='trackback'";
                break;

            case "unaproved_comment":
                $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'";
                break;

            case "spam_comment":
                $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_approved = 'spam'";
                break;

            case "trash_comment":
                $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_approved = 'trash' OR comment_approved = 'post-trashed'";
                break;

            case 'oembed_post_meta':
                $sql = "SELECT COUNT(meta_id) FROM $wpdb->postmeta WHERE meta_key LIKE('%\_oembed\_%')";
                break;

            default:
                $message .= __('nothing', 'wp-db-booster');
                break;

        }
        if ($sql != '')
            $var = $wpdb->get_var($sql);
        if ($var == '') $var = 0;
        return $var;
    }

    public function do_cleanup($type)
    {
        global $wpdb;
        switch ($type) {
            case "autodraft":
                $sql = "DELETE FROM `$wpdb->posts` WHERE post_status = 'auto-draft'";
                break;

            case "trash_post":
                $sql = "DELETE FROM `$wpdb->posts` WHERE post_status = 'trash'";
                break;

            case "transient_feed":
                $sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
                break;

            case "draft":
                $sql = $wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_status = %s AND (post_type = %s OR post_type = %s)", "draft", "page", "post");
                break;

            case "orphan_comment_meta":
                $sql = "DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
                break;

            case "duplicated_comment_meta":
                $var = 0;
                $result = $wpdb->get_results($wpdb->prepare("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, comment_id, COUNT(*) AS count FROM $wpdb->commentmeta GROUP BY comment_id, meta_key, meta_value HAVING count > %d", 1));
                if ($result) {
                    foreach ($result as $meta) {
                        $ids = array_map('intval', explode(',', $meta->ids));
                        array_pop($ids);
                        $var += $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->commentmeta WHERE meta_id IN (" . implode(',', $ids) . ") AND comment_id = %d", intval($meta->comment_id)));
                    }
                }
                break;

            case "orphan_post_meta":
                $sql = "DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
                break;

            case "duplicated_post_meta":
                $var = 0;
                $result = $wpdb->get_results($wpdb->prepare("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, post_id, COUNT(*) AS count FROM $wpdb->postmeta GROUP BY post_id, meta_key, meta_value HAVING count > %d", 1));
                if ($result) {
                    foreach ($result as $meta) {
                        $ids = array_map('intval', explode(',', $meta->ids));
                        array_pop($ids);
                        $var += $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->postmeta WHERE meta_id IN (" . implode(',', $ids) . ") AND post_id = %d", intval($meta->post_id)));
                    }
                }
                break;

            case 'orphan_user_meta':
                $sql = "DELETE FROM $wpdb->usermeta WHERE user_id NOT IN (SELECT ID FROM $wpdb->users)";
                break;

            case "duplicated_user_meta":
                $var = 0;
                $result = $wpdb->get_results($wpdb->prepare("SELECT GROUP_CONCAT(umeta_id ORDER BY umeta_id DESC) AS ids, user_id, COUNT(*) AS count FROM $wpdb->usermeta GROUP BY user_id, meta_key, meta_value HAVING count > %d", 1));
                if ($result) {
                    foreach ($result as $meta) {
                        $ids = array_map('intval', explode(',', $meta->ids));
                        array_pop($ids);
                        $var += $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE umeta_id IN (" . implode(',', $ids) . ") AND user_id = %d", intval($meta->user_id)));
                    }
                }
                break;

            case 'orphan_term_meta':
                $sql = "DELETE FROM $wpdb->termmeta WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)";
                break;

            case 'duplicated_term_meta':
                $query = $wpdb->get_results($wpdb->prepare("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, term_id, COUNT(*) AS count FROM $wpdb->termmeta GROUP BY term_id, meta_key, meta_value HAVING count > %d", 1));
                if ($query) {
                    foreach ($query as $meta) {
                        $ids = array_map('intval', explode(',', $meta->ids));
                        array_pop($ids);
                        $var += $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->termmeta WHERE meta_id IN (" . implode(',', $ids) . ") AND term_id = %d", intval($meta->term_id)));
                    }
                }
                break;

            case "orphan_term_relationships":
                $sql = "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
                break;

            case 'unused_tags':
                $var = $wpdb->get_results("DELETE FROM $wpdb->terms WHERE term_id IN (SELECT term_id FROM $wpdb->term_taxonomy WHERE COUNT = 0)");
                $var += $wpdb->get_results("DELETE FROM $wpdb->term_taxonomy WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)");
                $var += $wpdb->get_results("DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM $wpdb->term_taxonomy)");
                break;

            case "revisions":
                $sql = "DELETE FROM `$wpdb->posts` WHERE post_type = 'revision'";
                break;

            case "pingbacks":
                $sql = "DELETE FROM `$wpdb->comments` WHERE comment_type='pingback';";
                break;

            case "transient_options":
                $sql = "DELETE FROM `$wpdb->options` WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'";
                break;

            case "trackbacks":
                $sql = "DELETE FROM `$wpdb->comments` WHERE comment_type='trackback';";
                break;

            case "unaproved_comment":
                $sql = "DELETE FROM `$wpdb->comments` WHERE comment_approved = '0'";
                break;

            case "spam_comment":
                $sql = "DELETE FROM `$wpdb->comments` WHERE comment_approved = 'spam'";
                $var = $wpdb->query($sql);
                $sql = "DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
                $wpdb->query($sql);
                $sql = '';
                break;

            case "trash_comment":
                $sql = "DELETE FROM `$wpdb->comments` WHERE comment_approved = 'trash' OR comment_approved = 'post-trashed'";
                $var = $wpdb->query($sql);
                $sql = "DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
                $wpdb->query($sql);
                $sql = '';
                break;

            case 'oembed_post_meta':
                $sql = "DELETE FROM `$wpdb->postmeta` WHERE meta_key LIKE('%\_oembed\_%')";
                break;

            default:
                $message .= __('nothing', 'wp-optimize');
                break;

        }
        if ($sql != '') $var = $wpdb->query($sql);
        return $var;
    }

    static function r($status, $params = 0)
    {
        return array('status' => $status, 'params' => $params);
    }

    public function status($status)
    {
        if ($status['status'] == 'OK') return "<div class=\"wpdbboo_result wpdbboo_result_ok\">" . __("OK", "wp-db-booster") . "</div>";
        if ($status['status'] == 'BAD') return "<div class=\"wpdbboo_result wpdbboo_result_bad\">" . __("BAD", "wp-db-booster") . "</div>";
    }

    // check database prefix
    public function database_prefix()
    {
        global $wpdb;
        $prefixes = array('wordpress_', 'wp_'); // commonly used prefixes

        $status = in_array($wpdb->prefix, $prefixes) ? self::S_BAD : self::S_OK;
        return self::r($status);
    }

    // check security keys and salts
    public function check_keys_salts()
    {
        // list of keys
        $ks = array('AUTH_KEY', 'AUTH_SALT', 'SECURE_AUTH_KEY', 'SECURE_AUTH_SALT',
            'LOGGED_IN_KEY', 'LOGGED_IN_SALT', 'NONCE_KEY', 'NONCE_SALT');

        // check keys and salts
        $bad = array();
        while (list(, $v) = @each($ks)) {
            $c = @constant($v);
            if (empty($c) || strlen($c) < 50) {
                $bad[] = $v;
            }
        }

        $status = count($bad) ? self::S_BAD : self::S_OK;
        return self::r($status, array(@implode(', ', $bad)));
    }


    // check strength of database password
    public function database_password()
    {
        $status = self::S_BAD;
        $pass = DB_PASSWORD;
        if (empty($pass)) {
            $message = __('password is empty', wphealthcare::ld);
        } else {
            // read dictionary file
            $dict = file($this->path . '/tests/dictionary.txt', FILE_IGNORE_NEW_LINES);

            if (in_array($pass, $dict)) {
                $message = __('password is a word from the dictionary', wphealthcare::ld);
            } else
                if (strlen($pass) < 7) {
                    $message = __('password is too short', wphealthcare::ld);
                } else
                    if (sizeof(count_chars($pass, 1)) < 6) {
                        $message = __('password is too simple', wphealthcare::ld);
                    } else {
                        $status = self::S_OK;
                        $message = '';
                    }
        }

        return self::r($status, array($message));
    }

    // check if debug mode is enabled in the wordpress
    public function debug_mode()
    {
        $status = defined('WP_DEBUG') && WP_DEBUG ? self::S_BAD : self::S_OK;
        return self::r($status);
    }

    // check if database debug mode is enabled
    public function database_debug_mode()
    {
        global $wpdb;
        $status = $wpdb->show_errors ? self::S_BAD : self::S_OK;
        return self::r($status);
    }
}