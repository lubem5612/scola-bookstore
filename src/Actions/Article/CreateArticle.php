<?php


namespace Transave\ScolaBookstore\Actions\Article;


use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;

class CreateArticle
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setDefaultAddress();
            return $this->createAddress();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setContributors()
    {
        
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:500',
            'subtitle' => 'sometimes|required|string|max:500',
            'preface' => 'sometimes|required|string|max:766',
            'source' => 'sometimes|required|string|max:766',
            'page_url' => 'sometimes|required|string|max:766',
            'pages' => 'nullable',
            'contributors' => 'nullable|array', // json_encoded
            'contributors.*' => 'required_if:contributors,!=,null|string|in:name',
            'abstract' => 'string|nullable',
            'content' => 'nullable|string',
            'ISBN' => 'nullable|string|max:100',
            'publication_info' => 'nullable|array',
            'publication_info.*' => 'required_if:publication_info,!=,null|in:date,publisher,place',
            'report_info' => 'nullable|array',
            'report_info.*' => 'required_if:report_info,!=,null|in:report_number,organization,funding,license',
            'editors' => 'nullable|array',
            'editors.*' => 'required_if:editors,!=,null|in:name',
            'dedicatees' => 'nullable|array',
            'dedicatees.*' => 'required_if:dedicatees,!=,null|in:name',
            'journal_info' => 'nullable|array',
            'journal_info.*' => 'required_if:journal_info,!=,null|in:volume,page_start,page_end,editorial',
            'edition' => 'nullable|string|max:80',
            'keywords' => 'sometimes|required|string|max:766',
            'summary' => 'sometimes|required|string|max:766',
            'overview' => 'sometimes|required|string|max:766',
            'conference_info' => 'nullable|array',
            'conference_info.*' => 'required_if:conference_info,!=,null',  // validate 'name, year, date, location';
            'institutional_affiliations' => 'nullable|array',
            'institutional_affiliations.*' => 'required_if:institutional_affiliations,!=,null|string',
            'file_path' => 'sometimes|required|string|max:700',
            'cover_image' => 'sometimes|required|string|max:700',
            'price' => 'required|numeric|gte:0',
            'percentage_share' => 'required|numeric|gte:3',
        ]);
    }
}