<?php


namespace wadelphillips\ForumConverter\Models;

use Corcel\Model\Post;

class ForumAttachment extends Post
{
    protected $postType = 'attachment';

//    public function bpDocument()
//    {
//        return $this->hasOne(BpDocument::class);
//    }
//
//    public function bpDocumentMeta()
//    {
//        return $this->hasMany(BpDocumentMeta::class);
//    }
}
