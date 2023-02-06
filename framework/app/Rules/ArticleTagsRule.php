<?php

namespace App\Rules;

use App\Repositories\Blog\TagRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

/**
 * Class ArticleTagsRule
 * @package App\Rules
 */
class ArticleTagsRule implements Rule
{
    /**
     * @var Collection
     */
    private $tags;

    /**
     * ArticleTagsRule constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tags = $tagRepository->get()->map(function ($tag) {
            return $tag->id;
        })->flip();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $tag) {
            if (!$this->tags->has($tag)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid tags list.';
    }
}
