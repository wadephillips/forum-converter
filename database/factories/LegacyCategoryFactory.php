<?php

namespace wadephillips\ForumConverter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use wadephillips\ForumConverter\Models\LegacyCategory;


class LegacyCategoryFactory extends Factory
{
    protected $model = LegacyCategory::class;

    public function definition()
    {
        return [
         'forum_id' => 1,
         'board_id' => 1,
         'forum_name' => "Public Forums",
         'forum_description' => "",
         'forum_is_cat' => "y",
         'forum_parent' => null,
         'forum_order' => 79,
         'forum_status' => "o",
         'forum_total_topics' => 0,
         'forum_total_posts' => 0,
         'forum_last_post_id' => null,
         'forum_last_post_type' => "p",
         'forum_last_post_title' => null,
         'forum_last_post_date' => 0,
         'forum_last_post_author_id' => null,
         'forum_last_post_author' => null,
         'forum_permissions' => 'a:8:{s:14:"can_view_forum";s:42:"|8|11|13|17|18|15|16|5|3|10|7|4|14|9|12|6|";s:15:"can_view_hidden";s:6:"|11|6|";s:15:"can_view_topics";s:0:"";s:15:"can_post_topics";s:0:"";s:14:"can_post_reply";s:0:"";s:10:"can_report";s:0:"";s:16:"can_upload_files";s:0:"";s:10:"can_search";s:0:"";}',
         'forum_topics_perpage' => 0,
         'forum_posts_perpage' => 0,
         'forum_topic_order' => "r",
         'forum_post_order' => "a",
         'forum_hot_topic' => 0,
         'forum_max_post_chars' => 0,
         'forum_post_timelock' => 0,
         'forum_display_edit_date' => "n",
         'forum_text_formatting' => "xhtml",
         'forum_html_formatting' => "safe",
         'forum_allow_img_urls' => "n",
         'forum_auto_link_urls' => "y",
         'forum_notify_moderators_topics' => "n",
         'forum_notify_moderators_replies' => "n",
         'forum_notify_emails' => null,
         'forum_notify_emails_topics' => null,
         'forum_enable_rss' => "n",
         'forum_use_http_auth' => "n",
        ];
    }
}

