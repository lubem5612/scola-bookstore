<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\User;

class ArticleViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Article $article;

    public function __construct(User $user, Article $article)
    {
        $this->user = $user;
        $this->article = $article;
    }
}