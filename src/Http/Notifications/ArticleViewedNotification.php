<?php

namespace Transave\ScolaBookstore\Http\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\User;

class ArticleViewedNotification extends Notification
{
    public User $user;
    public Article $article;


    public function __construct(User $user, Article $article)
    {
        $this->user = $user;
        $this->article = $article;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'article_id' => $this->article->id,
            'message' => $this->user->first_name . " " . $this->user->last_name . ' just viewed ' . $this->article->title,
        ];
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }




}