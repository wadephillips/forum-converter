<?php


namespace wadelphillips\ForumConverter\Models;

use Corcel\Model\Post;

class ForumAttachment extends Post
{
    protected $postType = 'attachment';

    public function bpDocument()
    {
        return $this->hasOne(BpDocument::class);//Todo need to make an Illuminate/Model
    }

    public function bpDocumentMeta()
    {
        return $this->hasMany(BpDocumentMeta::class); // todo need to make an Illuminate/Model
    }

}
