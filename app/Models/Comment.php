<?php

namespace App\Models;

use App\Models\User;
use App\Models\Reply;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = ['content'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCommentReplies() {
        return $this->hasMany(Reply::class, 'comment_id');
    }
}
