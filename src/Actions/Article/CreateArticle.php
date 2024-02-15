<?php


namespace Transave\ScolaBookstore\Actions\Article;


use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Resource;

class CreateArticle
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData, $formData, $uploader;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setContributors();
            $this->setPublicationInfo();
            $this->setConferenceInfo();
            $this->setDedicatees();
            $this->setEditors();
            $this->setInstitutionalAffiliation();
            $this->setJournalInfo();
            $this->setReportInfo();
            $this->uploadFile();
            $this->uploadCoverImage();
            return $this->createArticle();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function createArticle()
    {
        $article = Resource::query()->create($this->validatedData);
        return $this->sendSuccess($article->load('author'), 'resource created successfully');
    }

    private function setContributors()
    {
        if (Arr::exists($this->formData, 'contributors')
            && $this->formData['contributors']
            && is_array($this->formData['contributors'])
            && count($this->formData['contributors']) > 0)
        {
            $this->validatedData['contributors'] = json_encode($this->formData['contributors']);
        }
    }

    private function setPublicationInfo()
    {
        if (Arr::exists($this->formData, 'publication_info')
            && $this->formData['publication_info']
            && is_array($this->formData['publication_info'])
            && count($this->formData['publication_info']) > 0)
        {
            $this->validatedData['publication_info'] = json_encode($this->formData['publication_info']);
        }
    }

    private function setReportInfo()
    {
        if (Arr::exists($this->formData, 'report_info')
            && $this->formData['report_info']
            && is_array($this->formData['report_info'])
            && count($this->formData['report_info']) > 0)
        {
            $this->validatedData['report_info'] = json_encode($this->formData['report_info']);
        }
    }

    private function setEditors()
    {
        if (Arr::exists($this->formData, 'editors')
            && $this->formData['editors']
            && is_array($this->formData['editors'])
            && count($this->formData['editors']) > 0)
        {
            $this->validatedData['editors'] = json_encode($this->formData['editors']);
        }
    }

    private function setDedicatees()
    {
        if (Arr::exists($this->formData, 'dedicatees')
            && $this->formData['dedicatees']
            && is_array($this->formData['dedicatees'])
            && count($this->formData['dedicatees']) > 0)
        {
            $this->validatedData['dedicatees'] = json_encode($this->formData['dedicatees']);
        }
    }

    private function setJournalInfo()
    {
        if (Arr::exists($this->formData, 'journal_info')
            && $this->formData['journal_info']
            && is_array($this->formData['journal_info'])
            && count($this->formData['journal_info']) > 0)
        {
            $this->validatedData['journal_info'] = json_encode($this->formData['journal_info']);
        }
    }

    private function setConferenceInfo()
    {
        if (Arr::exists($this->formData, 'conference_info')
            && $this->formData['conference_info']
            && is_array($this->formData['conference_info'])
            && count($this->formData['conference_info']) > 0)
        {
            $this->validatedData['conference_info'] = json_encode($this->formData['conference_info']);
        }
    }

    private function setInstitutionalAffiliation()
    {
        if (Arr::exists($this->formData, 'institutional_affiliations')
            && $this->formData['institutional_affiliations']
            && is_array($this->formData['institutional_affiliations'])
            && count($this->formData['institutional_affiliations']) > 0)
        {
            $this->validatedData['institutional_affiliations'] = json_encode($this->formData['institutional_affiliations']);
        }
    }

    private function uploadFile()
    {
        if (Arr::exists($this->formData, 'file') && $this->formData['file'])
        {
            $response = $this->uploader->uploadFile($this->formData['file'], 'bookstore/resources');
            if ($response['success']) $this->validatedData['file_path'] = $response['upload_url'];
        }
    }

    private function uploadCoverImage()
    {
        if (Arr::exists($this->formData, 'cover_image') && $this->formData['cover_image'])
        {
            $response = $this->uploader->uploadFile($this->formData['cover_image'], 'bookstore/resources/cover');
            if ($response['success']) $this->validatedData['cover_image'] = $response['upload_url'];
        }
    }

    private function validateRequest()
    {
        $this->formData = $this->validate($this->request, [
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:500',
            'subtitle' => 'sometimes|required|string|max:500',
            'preface' => 'sometimes|required|string|max:766',
            'source' => 'sometimes|required|string|max:766',
            'page_url' => 'sometimes|required|string|max:766',
            'pages' => 'nullable',
            'contributors' => 'nullable|array', // json_encoded
            'contributors.*' => 'nullable|string', // 'required_if:contributors,!=,null|string|name',
            'abstract' => 'string|nullable',
            'content' => 'nullable|string',
            'ISBN' => 'nullable|string|max:100',
            'publication_info' => 'nullable|array',
            'publication_info.*' => 'nullable|string',//'required_if:publication_info,!=,null|in:date,publisher,place',
            'report_info' => 'nullable|array',
            'report_info.*' => 'nullable|string', // 'required_if:report_info,!=,null|in:report_number,organization,funding,license',
            'editors' => 'nullable|array',
            'editors.*' => 'nullable|string', // 'required_if:editors,!=,null|in:name',
            'dedicatees' => 'nullable|array',
            'dedicatees.*' => 'nullable|string', // 'required_if:dedicatees,!=,null|in:name',
            'journal_info' => 'nullable|array',
            'journal_info.*' => 'nullable|string', // 'required_if:journal_info,!=,null|in:volume,page_start,page_end,editorial',
            'edition' => 'nullable|string|max:80',
            'keywords' => 'sometimes|required|string|max:766',
            'summary' => 'sometimes|required|string|max:766',
            'overview' => 'sometimes|required|string|max:766',
            'conference_info' => 'nullable|array',
            'conference_info.*' => 'nullable|string', // 'required_if:conference_info,!=,null|in:name,date,location',  // validate 'name, year, date, location';
            'institutional_affiliations' => 'nullable|array',
            'institutional_affiliations.*' => 'nullable|string', // 'required_if:institutional_affiliations,!=,null|string|in:name',
            'file' => 'sometimes|required|file|max:10000000',
            'cover_image' => 'sometimes|required|string|max:700',
            'price' => 'required|numeric|gte:0',
            'percentage_share' => 'required|numeric|gte:3',
        ]);

        $this->validatedData = Arr::except($this->formData, [
            'file',
            'cover_image',
            'institutional_affiliations',
            'conference_info',
            'journal_info',
            'dedicatees',
            'editors',
            'report_info',
            'publication_info',
            'contributors'
        ]);
    }
}